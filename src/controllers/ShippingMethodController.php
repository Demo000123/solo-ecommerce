<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\ShippingMethodService;

class ShippingMethodController extends Controller
{
    private ShippingMethodService $shippingMethodService;

    public function __construct()
    {
        $this->shippingMethodService = new ShippingMethodService();
    }

    public function adminList(): void
    {
        // Check if user is admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
            return;
        }

        $shippingMethods = $this->shippingMethodService->getAllShippingMethods();

        $this->render('admin/shipping/index', [
            'pageTitle' => 'Manage Shipping Methods',
            'shippingMethods' => $shippingMethods
        ]);
    }

    public function adminCreate(): void
    {
        // Check if user is admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
            return;
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $this->filterInput($this->postParam('name', ''));
            $description = $this->filterInput($this->postParam('description', ''));
            $price = (float)$this->postParam('price', 0);
            $estimatedDays = $this->filterInput($this->postParam('estimated_days', ''));
            $status = (int)$this->postParam('status', 1);

            // Validate shipping method data
            if (empty($name) || $price < 0) {
                $error = 'Please fill in all required fields';
            } else {
                // Create shipping method
                $methodId = $this->shippingMethodService->createShippingMethod(
                    $name,
                    $description,
                    $price,
                    $estimatedDays,
                    $status
                );

                if ($methodId) {
                    $success = 'Shipping method created successfully';
                    // Reset form
                    $name = $description = $estimatedDays = '';
                    $price = 0;
                    $status = 1;
                } else {
                    $error = 'Failed to create shipping method';
                }
            }
        }

        $this->render('admin/shipping/create', [
            'pageTitle' => 'Create Shipping Method',
            'error' => $error,
            'success' => $success
        ]);
    }

    public function adminEdit(): void
    {
        // Check if user is admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
            return;
        }

        $methodId = (int)$this->getParam('id', 0);

        if ($methodId <= 0) {
            $this->redirect('/admin/shipping');
            return;
        }

        $shippingMethod = $this->shippingMethodService->getShippingMethodById($methodId);

        if (!$shippingMethod) {
            $this->redirect('/admin/shipping');
            return;
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $this->filterInput($this->postParam('name', ''));
            $description = $this->filterInput($this->postParam('description', ''));
            $price = (float)$this->postParam('price', 0);
            $estimatedDays = $this->filterInput($this->postParam('estimated_days', ''));
            $status = (int)$this->postParam('status', 1);

            // Validate shipping method data
            if (empty($name) || $price < 0) {
                $error = 'Please fill in all required fields';
            } else {
                // Update shipping method
                $updated = $this->shippingMethodService->updateShippingMethod(
                    $methodId,
                    $name,
                    $description,
                    $price,
                    $estimatedDays,
                    $status
                );

                if ($updated) {
                    $success = 'Shipping method updated successfully';
                    // Refresh the shipping method data
                    $shippingMethod = $this->shippingMethodService->getShippingMethodById($methodId);
                } else {
                    $error = 'Failed to update shipping method';
                }
            }
        }

        $this->render('admin/shipping/edit', [
            'pageTitle' => 'Edit Shipping Method',
            'shippingMethod' => $shippingMethod,
            'error' => $error,
            'success' => $success
        ]);
    }

    public function adminDelete(): void
    {
        // Check if user is admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
            return;
        }

        // Only process POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/shipping');
            return;
        }

        $methodId = (int)$this->postParam('method_id', 0);

        if ($methodId <= 0) {
            $this->redirect('/admin/shipping');
            return;
        }

        $deleted = $this->shippingMethodService->deleteShippingMethod($methodId);

        if ($deleted) {
            $_SESSION['admin_message'] = 'Shipping method deleted successfully';
        } else {
            $_SESSION['admin_error'] = 'Failed to delete shipping method';
        }

        $this->redirect('/admin/shipping');
    }

    public function adminToggleStatus(): void
    {
        // Check if user is admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
            return;
        }

        // Only process POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/shipping');
            return;
        }

        $methodId = (int)$this->postParam('method_id', 0);

        if ($methodId <= 0) {
            $this->redirect('/admin/shipping');
            return;
        }

        $updated = $this->shippingMethodService->toggleShippingMethodStatus($methodId);

        if ($updated) {
            $_SESSION['admin_message'] = 'Shipping method status updated successfully';
        } else {
            $_SESSION['admin_error'] = 'Failed to update shipping method status';
        }

        $this->redirect('/admin/shipping');
    }
} 