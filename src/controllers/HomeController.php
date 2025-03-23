<?php
declare(strict_types=1);

namespace App\Controllers;

require_once __DIR__ . '/../services/ProductService.php';
require_once __DIR__ . '/../services/CategoryService.php';

use App\Services\ProductService;
use App\Services\CategoryService;

/**
 * Home Controller
 * 
 * Controller for homepage and other static pages
 */
class HomeController extends Controller
{
    /**
     * @var ProductService Product service instance
     */
    private $productService;
    
    /**
     * @var CategoryService Category service instance
     */
    private $categoryService;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productService = new ProductService();
        $this->categoryService = new CategoryService();
    }
    
    /**
     * Homepage action
     * 
     * Renders the homepage
     */
    public function index()
    {
        $featuredProducts = $this->productService->getFeaturedProducts(8);
        $newProducts = $this->productService->getNewProducts(8);
        $popularProducts = $this->productService->getPopularProducts(8);
        $featuredCategories = $this->categoryService->getFeaturedCategories();
        
        echo $this->render('home/index', [
            'pageTitle' => SITE_NAME . ' - Home',
            'featuredProducts' => $featuredProducts,
            'newProducts' => $newProducts,
            'popularProducts' => $popularProducts,
            'featuredCategories' => $featuredCategories
        ]);
    }
    
    /**
     * About page action
     * 
     * Renders the about page
     */
    public function about()
    {
        echo $this->render('home/about', [
            'pageTitle' => 'About Us - ' . SITE_NAME
        ]);
    }
    
    /**
     * Contact page action
     * 
     * Renders the contact page
     */
    public function contact()
    {
        echo $this->render('home/contact', [
            'pageTitle' => 'Contact Us - ' . SITE_NAME
        ]);
    }
    
    /**
     * Process contact form submission
     */
    public function processContact()
    {
        // Process form submission
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $subject = $_POST['subject'] ?? '';
        $message = $_POST['message'] ?? '';
        
        // Validate inputs
        $errors = [];
        
        if (empty($name)) {
            $errors[] = 'Name is required';
        }
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required';
        }
        
        if (empty($subject)) {
            $errors[] = 'Subject is required';
        }
        
        if (empty($message)) {
            $errors[] = 'Message is required';
        }
        
        if (!empty($errors)) {
            $_SESSION['validation_errors'] = $errors;
            $_SESSION['old_input'] = [
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'message' => $message
            ];
            
            $this->redirect('/contact');
            return;
        }
        
        // Send email (this is a placeholder - implement actual email sending)
        $sent = mail(MAIL_FROM_ADDRESS, 'Contact Form: ' . $subject, $message, [
            'From' => $email,
            'Reply-To' => $email
        ]);
        
        if ($sent) {
            $this->setFlash('success', 'Your message has been sent. We will contact you shortly!');
        } else {
            $this->setFlash('error', 'Sorry, there was a problem sending your message. Please try again later.');
        }
        
        $this->redirect('/contact');
    }
} 