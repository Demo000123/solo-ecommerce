<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\OrderService;
use App\Services\CartService;
use App\Services\ShippingMethodService;
use App\Services\CouponService;

class OrderController extends Controller
{
    private OrderService $orderService;
    private CartService $cartService;
    private ShippingMethodService $shippingMethodService;
    private CouponService $couponService;

    public function __construct()
    {
        $this->orderService = new OrderService();
        $this->cartService = new CartService();
        $this->shippingMethodService = new ShippingMethodService();
        $this->couponService = new CouponService();
    }

    public function checkout(): void
    {
        // Get the cart - first check if user is logged in
        $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
        $sessionId = session_id();
        
        // Get the cart
        $cart = $this->cartService->getCart($userId, $sessionId);
        
        // If cart is empty, redirect to cart page
        if (empty($cart['items'])) {
            $this->redirect('/cart');
            return;
        }
        
        $shippingMethods = $this->shippingMethodService->getActiveShippingMethods();
        $addresses = [];
        $error = '';
        $success = '';
        $formData = [
            'fullname' => $userId ? $_SESSION['user_name'] : '',
            'email' => '',
            'phone' => '',
            'address' => '',
            'province' => '',
            'district' => '',
            'ward' => '',
            'notes' => '',
            'shipping_method' => 'standard',
            'payment_method' => 'cod',
        ];
        
        // If user is logged in, get their addresses
        if ($userId) {
            $addresses = $this->orderService->getUserAddresses($userId);
            
            // If they have a default address, pre-populate the form
            foreach ($addresses as $address) {
                if (isset($address['is_default']) && $address['is_default']) {
                    $formData['fullname'] = $address['fullname'];
                    $formData['phone'] = $address['phone'];
                    $formData['address'] = $address['address'];
                    $formData['province'] = $address['province'];
                    $formData['district'] = $address['district'];
                    $formData['ward'] = $address['ward'];
                    break;
                }
            }
        }
        
        // Check if the form was submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get the form data
            $formData = [
                'fullname' => $this->filterInput($this->postParam('fullname', '')),
                'email' => $this->filterInput($this->postParam('email', '')),
                'phone' => $this->filterInput($this->postParam('phone', '')),
                'address' => $this->filterInput($this->postParam('address', '')),
                'province' => $this->filterInput($this->postParam('province', '')),
                'district' => $this->filterInput($this->postParam('district', '')),
                'ward' => $this->filterInput($this->postParam('ward', '')),
                'notes' => $this->filterInput($this->postParam('notes', '')),
                'shipping_method' => $this->filterInput($this->postParam('shipping_method', 'standard')),
                'payment_method' => $this->filterInput($this->postParam('payment_method', 'cod')),
                'save_address' => (bool)$this->postParam('save_address', false),
            ];
            
            // Validate the form data
            if (empty($formData['fullname']) || empty($formData['phone']) || 
                empty($formData['address']) || empty($formData['province']) || 
                empty($formData['district']) || empty($formData['ward'])) {
                $error = 'Please fill in all required shipping information fields';
            } elseif (empty($formData['email']) || !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
                $error = 'Please enter a valid email address';
            } else {
                // Create the order
                $orderId = $this->orderService->createOrder(
                    $userId,
                    $cart,
                    $formData['fullname'],
                    $formData['email'],
                    $formData['phone'],
                    $formData['address'],
                    $formData['province'],
                    $formData['district'],
                    $formData['ward'],
                    $formData['shipping_method'],
                    $formData['payment_method'],
                    $formData['notes']
                );
                
                if ($orderId) {
                    // If user is logged in and they want to save the address
                    if ($userId && $formData['save_address']) {
                        $this->orderService->saveUserAddress(
                            $userId,
                            $formData['fullname'],
                            $formData['phone'],
                            $formData['address'],
                            $formData['province'],
                            $formData['district'],
                            $formData['ward']
                        );
                    }
                    
                    // Clear the cart
                    $this->cartService->clearCart($userId, $sessionId);
                    
                    // Redirect to order confirmation page
                    $this->redirect('/order/confirmation/' . $orderId);
                    return;
                } else {
                    $error = 'Failed to create order. Please try again.';
                }
            }
        }
        
        // Calculate shipping cost based on selected method
        $shippingCost = 0;
        foreach ($shippingMethods as $method) {
            if ($method['name'] === $formData['shipping_method']) {
                $shippingCost = $method['price'];
                break;
            }
        }
        
        // Calculate order totals
        $subtotal = $cart['subtotal'];
        $total = $subtotal + $shippingCost;
        
        // Check for coupon in session
        $couponDiscount = 0;
        $couponCode = isset($_SESSION['coupon_code']) ? $_SESSION['coupon_code'] : null;
        
        if ($couponCode) {
            $coupon = $this->couponService->getCouponByCode($couponCode);
            if ($coupon && $this->couponService->validateCoupon($coupon, $subtotal)) {
                $couponDiscount = $this->couponService->calculateDiscount($coupon, $subtotal);
                $total -= $couponDiscount;
            }
        }
        
        $this->render('orders/checkout', [
            'cart' => $cart,
            'addresses' => $addresses,
            'shippingMethods' => $shippingMethods,
            'formData' => $formData,
            'error' => $error,
            'success' => $success,
            'subtotal' => $subtotal,
            'shippingCost' => $shippingCost,
            'couponDiscount' => $couponDiscount,
            'total' => $total,
            'pageTitle' => 'Checkout'
        ]);
    }
    
    public function confirmation(): void
    {
        $orderId = (int)$this->getParam('id', 0);
        
        if ($orderId <= 0) {
            $this->redirect('/');
            return;
        }
        
        // Get the order details
        $order = $this->orderService->getOrderById($orderId);
        
        // If order doesn't exist or doesn't belong to the current user
        if (!$order || (isset($_SESSION['user_id']) && $order['user_id'] !== $_SESSION['user_id'])) {
            $this->redirect('/');
            return;
        }
        
        $this->render('orders/confirmation', [
            'order' => $order,
            'pageTitle' => 'Order Confirmation'
        ]);
    }
    
    public function detail(): void
    {
        $orderId = (int)$this->getParam('id', 0);
        
        if ($orderId <= 0) {
            $this->redirect('/account/orders');
            return;
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }
        
        $userId = (int)$_SESSION['user_id'];
        
        // Get the order details
        $order = $this->orderService->getOrderById($orderId);
        
        // If order doesn't exist or doesn't belong to the current user
        if (!$order || ($order['user_id'] !== $userId && $_SESSION['user_role'] !== 'admin')) {
            $this->redirect('/account/orders');
            return;
        }
        
        $this->render('orders/detail', [
            'order' => $order,
            'pageTitle' => 'Order #' . $order['order_number']
        ]);
    }
    
    public function cancel(): void
    {
        $orderId = (int)$this->getParam('id', 0);
        
        if ($orderId <= 0) {
            $this->redirect('/account/orders');
            return;
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }
        
        $userId = (int)$_SESSION['user_id'];
        
        // Get the order details
        $order = $this->orderService->getOrderById($orderId);
        
        // If order doesn't exist or doesn't belong to the current user
        if (!$order || ($order['user_id'] !== $userId && $_SESSION['user_role'] !== 'admin')) {
            $this->redirect('/account/orders');
            return;
        }
        
        // If order is already cancelled or delivered
        if ($order['status'] === 'cancelled' || $order['status'] === 'delivered') {
            $this->redirect('/order/detail/' . $orderId);
            return;
        }
        
        // Cancel the order
        $cancelled = $this->orderService->cancelOrder($orderId);
        
        if ($cancelled) {
            // Redirect to order detail page with success message
            $_SESSION['order_message'] = 'Order has been cancelled successfully.';
        } else {
            // Redirect to order detail page with error message
            $_SESSION['order_error'] = 'Failed to cancel order. Please try again.';
        }
        
        $this->redirect('/order/detail/' . $orderId);
    }
    
    public function applyCoupon(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/checkout');
            return;
        }
        
        $couponCode = $this->filterInput($this->postParam('coupon_code', ''));
        
        if (empty($couponCode)) {
            $_SESSION['coupon_error'] = 'Please enter a coupon code.';
            $this->redirect('/checkout');
            return;
        }
        
        // Validate the coupon
        $coupon = $this->couponService->getCouponByCode($couponCode);
        
        if (!$coupon) {
            $_SESSION['coupon_error'] = 'Invalid coupon code.';
            $this->redirect('/checkout');
            return;
        }
        
        // Get the cart subtotal
        $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
        $sessionId = session_id();
        $cart = $this->cartService->getCart($userId, $sessionId);
        $subtotal = $cart['subtotal'];
        
        // Validate the coupon against the cart subtotal
        if (!$this->couponService->validateCoupon($coupon, $subtotal)) {
            $_SESSION['coupon_error'] = 'This coupon cannot be applied to your order.';
            $this->redirect('/checkout');
            return;
        }
        
        // Save the coupon code to session
        $_SESSION['coupon_code'] = $couponCode;
        $_SESSION['coupon_message'] = 'Coupon applied successfully.';
        
        $this->redirect('/checkout');
    }
    
    public function removeCoupon(): void
    {
        // Remove the coupon from session
        unset($_SESSION['coupon_code']);
        $_SESSION['coupon_message'] = 'Coupon removed.';
        
        $this->redirect('/checkout');
    }
} 