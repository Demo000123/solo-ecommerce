<?php

namespace App\Core;

/**
 * Application Class
 * 
 * Main application class that initializes components and processes requests
 */
class Application
{
    /**
     * @var Router The router instance
     */
    private $router;
    
    /**
     * @var Request The request instance
     */
    private $request;
    
    /**
     * @var Response The response instance
     */
    private $response;
    
    /**
     * Constructor
     * 
     * Initialize the application components
     */
    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        
        $this->registerRoutes();
    }
    
    /**
     * Run the application
     * 
     * Process the current request and send the response
     */
    public function run()
    {
        try {
            $this->router->resolve();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * Register application routes
     */
    private function registerRoutes()
    {
        // Home routes
        $this->router->get('/', 'HomeController@index');
        
        // Auth routes
        $this->router->get('/login', 'AuthController@showLogin');
        $this->router->post('/auth/login', 'AuthController@login');
        $this->router->get('/register', 'AuthController@showRegister');
        $this->router->post('/auth/register', 'AuthController@register');
        $this->router->get('/logout', 'AuthController@logout');
        
        // Product routes
        $this->router->get('/products', 'ProductController@index');
        $this->router->get('/products/{id}', 'ProductController@show');
        $this->router->get('/search', 'ProductController@search');
        
        // Category routes
        $this->router->get('/category/{slug}', 'CategoryController@show');
        $this->router->get('/categories', 'CategoryController@index');
        
        // Cart routes
        $this->router->get('/cart', 'CartController@index');
        $this->router->post('/cart/add', 'CartController@add');
        $this->router->post('/cart/update', 'CartController@update');
        $this->router->post('/cart/remove', 'CartController@remove');
        
        // Checkout routes
        $this->router->get('/checkout', 'CheckoutController@index');
        $this->router->post('/checkout/process', 'CheckoutController@process');
        $this->router->get('/checkout/confirmation/{id}', 'CheckoutController@confirmation');
        
        // Account routes
        $this->router->get('/account/dashboard', 'AccountController@dashboard');
        $this->router->get('/account/profile', 'AccountController@profile');
        $this->router->post('/account/profile/update', 'AccountController@updateProfile');
        $this->router->post('/account/profile/password', 'AccountController@updatePassword');
        $this->router->get('/account/orders', 'AccountController@orders');
        $this->router->get('/account/orders/{id}', 'AccountController@orderDetail');
        $this->router->get('/account/addresses', 'AccountController@addresses');
        $this->router->post('/account/addresses/add', 'AccountController@addAddress');
        $this->router->post('/account/addresses/update', 'AccountController@updateAddress');
        $this->router->post('/account/addresses/delete', 'AccountController@deleteAddress');
        
        // Admin routes
        $this->router->get('/admin/dashboard', 'Admin\DashboardController@index');
        $this->router->get('/admin/products', 'Admin\ProductController@index');
        $this->router->get('/admin/products/create', 'Admin\ProductController@create');
        $this->router->post('/admin/products/store', 'Admin\ProductController@store');
        $this->router->get('/admin/products/edit/{id}', 'Admin\ProductController@edit');
        $this->router->post('/admin/products/update/{id}', 'Admin\ProductController@update');
        $this->router->post('/admin/products/delete/{id}', 'Admin\ProductController@delete');
        
        $this->router->get('/admin/categories', 'Admin\CategoryController@index');
        $this->router->get('/admin/categories/create', 'Admin\CategoryController@create');
        $this->router->post('/admin/categories/store', 'Admin\CategoryController@store');
        $this->router->get('/admin/categories/edit/{id}', 'Admin\CategoryController@edit');
        $this->router->post('/admin/categories/update/{id}', 'Admin\CategoryController@update');
        $this->router->post('/admin/categories/delete/{id}', 'Admin\CategoryController@delete');
        
        $this->router->get('/admin/orders', 'Admin\OrderController@index');
        $this->router->get('/admin/orders/view/{id}', 'Admin\OrderController@view');
        $this->router->get('/admin/orders/edit/{id}', 'Admin\OrderController@edit');
        $this->router->post('/admin/orders/update-status', 'Admin\OrderController@updateStatus');
        
        $this->router->get('/admin/users', 'Admin\UserController@index');
        $this->router->get('/admin/users/create', 'Admin\UserController@create');
        $this->router->post('/admin/users/store', 'Admin\UserController@store');
        $this->router->get('/admin/users/edit/{id}', 'Admin\UserController@edit');
        $this->router->post('/admin/users/update/{id}', 'Admin\UserController@update');
        $this->router->post('/admin/users/delete/{id}', 'Admin\UserController@delete');

        $this->router->get('/admin/settings', 'Admin\SettingController@index');
        $this->router->post('/admin/settings/update', 'Admin\SettingController@update');
    }
    
    /**
     * Handle exceptions
     * 
     * @param \Exception $e The exception that occurred
     */
    private function handleException(\Exception $e)
    {
        if ($e instanceof RouteNotFoundException) {
            $this->response->setStatusCode(404);
            echo $this->renderView('errors/404');
        } else {
            $this->response->setStatusCode(500);
            echo $this->renderView('errors/500', [
                'errorMessage' => $e->getMessage(),
                'errorCode' => 500,
                'errorTrace' => $e->getTraceAsString()
            ]);
        }
    }
    
    /**
     * Render a view
     * 
     * @param string $view The view file to render
     * @param array $params Parameters to pass to the view
     * @return string The rendered view content
     */
    private function renderView($view, $params = [])
    {
        return View::render($view, $params);
    }
} 