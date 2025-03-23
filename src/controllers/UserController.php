<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\UserService;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function profile(): void
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }

        $userId = (int)$_SESSION['user_id'];
        $user = $this->userService->getUserById($userId);

        if (!$user) {
            $this->redirect('/login');
            return;
        }

        $this->render('users/profile', [
            'user' => $user,
            'pageTitle' => 'My Profile'
        ]);
    }

    public function addresses(): void
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }

        $userId = (int)$_SESSION['user_id'];
        $addresses = $this->userService->getUserAddresses($userId);

        $this->render('users/addresses', [
            'addresses' => $addresses,
            'pageTitle' => 'My Addresses'
        ]);
    }

    public function orders(): void
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }

        $userId = (int)$_SESSION['user_id'];
        $orders = $this->userService->getUserOrders($userId);

        $this->render('users/orders', [
            'orders' => $orders,
            'pageTitle' => 'My Orders'
        ]);
    }

    public function login(): void
    {
        // If user is already logged in, redirect to profile
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/profile');
            return;
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->postParam('email', '');
            $password = $this->postParam('password', '');

            if (empty($email) || empty($password)) {
                $error = 'Please enter your email and password';
            } else {
                $user = $this->userService->authenticateUser($email, $password);

                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['fullname'];
                    $_SESSION['user_role'] = $user['role'];

                    // Update last login time
                    $this->userService->updateLastLogin($user['id']);

                    $this->redirect('/profile');
                    return;
                } else {
                    $error = 'Invalid email or password';
                }
            }
        }

        $this->render('users/login', [
            'error' => $error,
            'pageTitle' => 'Login'
        ]);
    }

    public function register(): void
    {
        // If user is already logged in, redirect to profile
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/profile');
            return;
        }

        $error = '';
        $formData = [
            'fullname' => '',
            'email' => '',
            'phone' => '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = [
                'fullname' => $this->filterInput($this->postParam('fullname', '')),
                'email' => $this->filterInput($this->postParam('email', '')),
                'phone' => $this->filterInput($this->postParam('phone', '')),
            ];
            
            $password = $this->postParam('password', '');
            $confirmPassword = $this->postParam('confirm_password', '');

            // Validate form data
            if (empty($formData['fullname']) || empty($formData['email']) || empty($password)) {
                $error = 'Please fill in all required fields';
            } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
                $error = 'Please enter a valid email address';
            } elseif (strlen($password) < 6) {
                $error = 'Password must be at least 6 characters long';
            } elseif ($password !== $confirmPassword) {
                $error = 'Passwords do not match';
            } elseif ($this->userService->emailExists($formData['email'])) {
                $error = 'Email address is already registered';
            } else {
                $userId = $this->userService->registerUser(
                    $formData['fullname'],
                    $formData['email'],
                    $password,
                    $formData['phone']
                );

                if ($userId) {
                    $_SESSION['user_id'] = $userId;
                    $_SESSION['user_name'] = $formData['fullname'];
                    $_SESSION['user_role'] = 'customer';

                    $this->redirect('/profile');
                    return;
                } else {
                    $error = 'Registration failed. Please try again later.';
                }
            }
        }

        $this->render('users/register', [
            'formData' => $formData,
            'error' => $error,
            'pageTitle' => 'Register'
        ]);
    }

    public function logout(): void
    {
        // Clear all session variables
        $_SESSION = [];

        // Destroy the session
        session_destroy();

        // Redirect to the home page
        $this->redirect('/');
    }

    public function updateProfile(): void
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }

        $userId = (int)$_SESSION['user_id'];
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = $this->filterInput($this->postParam('fullname', ''));
            $email = $this->filterInput($this->postParam('email', ''));
            $phone = $this->filterInput($this->postParam('phone', ''));
            $address = $this->filterInput($this->postParam('address', ''));
            $province = $this->filterInput($this->postParam('province', ''));
            $district = $this->filterInput($this->postParam('district', ''));
            $ward = $this->filterInput($this->postParam('ward', ''));
            
            // Validate form data
            if (empty($fullname) || empty($email)) {
                $error = 'Please fill in all required fields';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Please enter a valid email address';
            } else {
                $updated = $this->userService->updateUserProfile(
                    $userId,
                    $fullname,
                    $email,
                    $phone,
                    $address,
                    $province,
                    $district,
                    $ward
                );

                if ($updated) {
                    $success = 'Profile updated successfully';
                    // Update session user name if it was changed
                    $_SESSION['user_name'] = $fullname;
                } else {
                    $error = 'Profile update failed. Please try again later.';
                }
            }
        }

        $user = $this->userService->getUserById($userId);

        $this->render('users/edit_profile', [
            'user' => $user,
            'error' => $error,
            'success' => $success,
            'pageTitle' => 'Edit Profile'
        ]);
    }

    public function changePassword(): void
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }

        $userId = (int)$_SESSION['user_id'];
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $this->postParam('current_password', '');
            $newPassword = $this->postParam('new_password', '');
            $confirmPassword = $this->postParam('confirm_password', '');

            // Validate form data
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $error = 'Please fill in all fields';
            } elseif (strlen($newPassword) < 6) {
                $error = 'New password must be at least 6 characters long';
            } elseif ($newPassword !== $confirmPassword) {
                $error = 'New passwords do not match';
            } else {
                $changed = $this->userService->changePassword($userId, $currentPassword, $newPassword);

                if ($changed) {
                    $success = 'Password changed successfully';
                } else {
                    $error = 'Current password is incorrect';
                }
            }
        }

        $this->render('users/change_password', [
            'error' => $error,
            'success' => $success,
            'pageTitle' => 'Change Password'
        ]);
    }
} 