<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    private CategoryService $categoryService;

    public function __construct()
    {
        $this->categoryService = new CategoryService();
    }

    public function index(): void
    {
        $categories = $this->categoryService->getCategories();
        
        $this->render('categories/index', [
            'categories' => $categories,
            'pageTitle' => 'Product Categories'
        ]);
    }
} 