<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\ReviewService;
use App\Services\ProductService;

class ReviewController extends Controller
{
    private ReviewService $reviewService;
    private ProductService $productService;

    public function __construct()
    {
        $this->reviewService = new ReviewService();
        $this->productService = new ProductService();
    }

    public function create(): void
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }

        // Only process POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
            return;
        }

        $productId = (int)$this->postParam('product_id', 0);
        $rating = (int)$this->postParam('rating', 0);
        $title = $this->filterInput($this->postParam('title', ''));
        $comment = $this->filterInput($this->postParam('comment', ''));

        // Validate input
        if ($productId <= 0 || $rating < 1 || $rating > 5) {
            $_SESSION['review_error'] = 'Invalid review data';
            $this->redirect('/product/' . $productId);
            return;
        }

        // Check if the product exists
        $product = $this->productService->getProductById($productId);
        if (!$product) {
            $this->redirect('/');
            return;
        }

        // Create the review
        $userId = (int)$_SESSION['user_id'];
        $created = $this->reviewService->createReview($productId, $userId, $rating, $title, $comment);

        if ($created) {
            $_SESSION['review_success'] = 'Your review has been submitted and will be visible after approval';
        } else {
            $_SESSION['review_error'] = 'Failed to submit review. Please try again.';
        }

        // Redirect back to the product page
        $this->redirect('/product/' . $productId);
    }

    public function adminList(): void
    {
        // Check if user is admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
            return;
        }

        $status = $this->getParam('status', 'pending');
        $page = max(1, (int)$this->getParam('page', 1));
        $perPage = 10;

        $reviews = $this->reviewService->getReviewsByStatus($status, $page, $perPage);
        $totalReviews = $this->reviewService->countReviewsByStatus($status);
        $totalPages = ceil($totalReviews / $perPage);

        $this->render('admin/reviews/index', [
            'pageTitle' => 'Manage Reviews',
            'reviews' => $reviews,
            'currentStatus' => $status,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function adminApprove(): void
    {
        // Check if user is admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
            return;
        }

        // Only process POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/reviews');
            return;
        }

        $reviewId = (int)$this->postParam('review_id', 0);
        
        if ($reviewId <= 0) {
            $this->redirect('/admin/reviews');
            return;
        }

        $updated = $this->reviewService->updateReviewStatus($reviewId, 'approved');

        if ($updated) {
            $_SESSION['admin_message'] = 'Review approved successfully';
        } else {
            $_SESSION['admin_error'] = 'Failed to approve review';
        }

        $this->redirect('/admin/reviews');
    }

    public function adminReject(): void
    {
        // Check if user is admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
            return;
        }

        // Only process POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/reviews');
            return;
        }

        $reviewId = (int)$this->postParam('review_id', 0);
        
        if ($reviewId <= 0) {
            $this->redirect('/admin/reviews');
            return;
        }

        $updated = $this->reviewService->updateReviewStatus($reviewId, 'rejected');

        if ($updated) {
            $_SESSION['admin_message'] = 'Review rejected successfully';
        } else {
            $_SESSION['admin_error'] = 'Failed to reject review';
        }

        $this->redirect('/admin/reviews');
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
            $this->redirect('/admin/reviews');
            return;
        }

        $reviewId = (int)$this->postParam('review_id', 0);
        
        if ($reviewId <= 0) {
            $this->redirect('/admin/reviews');
            return;
        }

        $deleted = $this->reviewService->deleteReview($reviewId);

        if ($deleted) {
            $_SESSION['admin_message'] = 'Review deleted successfully';
        } else {
            $_SESSION['admin_error'] = 'Failed to delete review';
        }

        $this->redirect('/admin/reviews');
    }
} 