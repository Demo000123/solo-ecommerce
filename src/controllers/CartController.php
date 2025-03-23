<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\ProductService;

class CartController extends Controller
{
    private ProductService $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
        
        // Initialize cart session if not exists
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function index(): void
    {
        $cart = $_SESSION['cart'];
        $cartItems = [];
        $totalPrice = 0;
        
        foreach ($cart as $productId => $quantity) {
            $product = $this->productService->getProductById((int) $productId);
            
            if ($product) {
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product->getPrice() * $quantity
                ];
                
                $totalPrice += $product->getPrice() * $quantity;
            }
        }
        
        $this->render('cart/index', [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
            'pageTitle' => 'Shopping Cart'
        ]);
    }

    public function add(): void
    {
        $productId = (int) $this->postParam('product_id', 0);
        $quantity = (int) $this->postParam('quantity', 1);
        
        if ($productId <= 0 || $quantity <= 0) {
            $this->redirect('/cart');
            return;
        }
        
        $product = $this->productService->getProductById($productId);
        
        if (!$product || !$product->isInStock()) {
            $this->redirect('/cart');
            return;
        }
        
        // Add to cart
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
        
        $this->redirect('/cart');
    }

    public function remove(): void
    {
        $productId = (int) $this->postParam('product_id', 0);
        
        if ($productId <= 0) {
            $this->redirect('/cart');
            return;
        }
        
        // Remove from cart
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
        
        $this->redirect('/cart');
    }
} 