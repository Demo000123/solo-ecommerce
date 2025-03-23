<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\CouponService;

class CouponController extends Controller
{
    private CouponService $couponService;

    public function __construct()
    {
        $this->couponService = new CouponService();
    }

    public function adminList(): void
    {
        // Check if user is admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
            return;
        }

        $page = max(1, (int)$this->getParam('page', 1));
        $perPage = 10;

        $coupons = $this->couponService->getAllCoupons($page, $perPage);
        $totalCoupons = $this->couponService->getTotalCoupons();
        $totalPages = ceil($totalCoupons / $perPage);

        $this->render('admin/coupons/index', [
            'pageTitle' => 'Manage Coupons',
            'coupons' => $coupons,
            'currentPage' => $page,
            'totalPages' => $totalPages
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
            $code = strtoupper($this->filterInput($this->postParam('code', '')));
            $type = $this->filterInput($this->postParam('type', ''));
            $value = (float)$this->postParam('value', 0);
            $minOrderAmount = (float)$this->postParam('min_order_amount', 0);
            $maxDiscountAmount = !empty($this->postParam('max_discount_amount')) ? (float)$this->postParam('max_discount_amount') : null;
            $startDate = $this->filterInput($this->postParam('start_date', ''));
            $endDate = $this->filterInput($this->postParam('end_date', ''));
            $usageLimit = !empty($this->postParam('usage_limit')) ? (int)$this->postParam('usage_limit') : null;
            $status = (int)$this->postParam('status', 1);

            // Validate coupon data
            if (empty($code) || empty($type) || $value <= 0) {
                $error = 'Please fill in all required fields';
            } elseif ($this->couponService->codeExists($code)) {
                $error = 'Coupon code already exists';
            } elseif ($type === 'percentage' && $value > 100) {
                $error = 'Percentage discount cannot exceed 100%';
            } else {
                // Create coupon
                $couponId = $this->couponService->createCoupon(
                    $code,
                    $type,
                    $value,
                    $minOrderAmount,
                    $maxDiscountAmount,
                    $startDate,
                    $endDate,
                    $usageLimit,
                    $status
                );

                if ($couponId) {
                    $success = 'Coupon created successfully';
                    // Reset form
                    $code = $type = '';
                    $value = $minOrderAmount = 0;
                    $maxDiscountAmount = $startDate = $endDate = null;
                    $usageLimit = null;
                    $status = 1;
                } else {
                    $error = 'Failed to create coupon';
                }
            }
        }

        $this->render('admin/coupons/create', [
            'pageTitle' => 'Create Coupon',
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

        $couponId = (int)$this->getParam('id', 0);

        if ($couponId <= 0) {
            $this->redirect('/admin/coupons');
            return;
        }

        $coupon = $this->couponService->getCouponById($couponId);

        if (!$coupon) {
            $this->redirect('/admin/coupons');
            return;
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = strtoupper($this->filterInput($this->postParam('code', '')));
            $type = $this->filterInput($this->postParam('type', ''));
            $value = (float)$this->postParam('value', 0);
            $minOrderAmount = (float)$this->postParam('min_order_amount', 0);
            $maxDiscountAmount = !empty($this->postParam('max_discount_amount')) ? (float)$this->postParam('max_discount_amount') : null;
            $startDate = $this->filterInput($this->postParam('start_date', ''));
            $endDate = $this->filterInput($this->postParam('end_date', ''));
            $usageLimit = !empty($this->postParam('usage_limit')) ? (int)$this->postParam('usage_limit') : null;
            $status = (int)$this->postParam('status', 1);

            // Validate coupon data
            if (empty($code) || empty($type) || $value <= 0) {
                $error = 'Please fill in all required fields';
            } elseif ($code !== $coupon['code'] && $this->couponService->codeExists($code)) {
                $error = 'Coupon code already exists';
            } elseif ($type === 'percentage' && $value > 100) {
                $error = 'Percentage discount cannot exceed 100%';
            } else {
                // Update coupon
                $updated = $this->couponService->updateCoupon(
                    $couponId,
                    $code,
                    $type,
                    $value,
                    $minOrderAmount,
                    $maxDiscountAmount,
                    $startDate,
                    $endDate,
                    $usageLimit,
                    $status
                );

                if ($updated) {
                    $success = 'Coupon updated successfully';
                    // Refresh the coupon data
                    $coupon = $this->couponService->getCouponById($couponId);
                } else {
                    $error = 'Failed to update coupon';
                }
            }
        }

        $this->render('admin/coupons/edit', [
            'pageTitle' => 'Edit Coupon',
            'coupon' => $coupon,
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
            $this->redirect('/admin/coupons');
            return;
        }

        $couponId = (int)$this->postParam('coupon_id', 0);

        if ($couponId <= 0) {
            $this->redirect('/admin/coupons');
            return;
        }

        $deleted = $this->couponService->deleteCoupon($couponId);

        if ($deleted) {
            $_SESSION['admin_message'] = 'Coupon deleted successfully';
        } else {
            $_SESSION['admin_error'] = 'Failed to delete coupon';
        }

        $this->redirect('/admin/coupons');
    }
} 