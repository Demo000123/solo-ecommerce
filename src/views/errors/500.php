<?php
// Get error details if available (this would be passed by the error handler)
$errorMessage = $errorMessage ?? 'An unexpected error occurred';
$errorCode = $errorCode ?? '500';
$errorTrace = $errorTrace ?? null;
$isDebug = defined('DEBUG_MODE') && DEBUG_MODE === true;
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="error-content">
                <div class="error-image mb-4">
                    <img src="/assets/images/500.svg" alt="Server Error" class="img-fluid" style="max-height: 300px;">
                </div>
                <h1 class="display-1 fw-bold text-danger mb-3">500</h1>
                <h2 class="mb-4">Server Error</h2>
                <p class="lead text-muted mb-4">
                    We're sorry, but something went wrong on our server.
                    Our team has been notified and is working to fix the issue.
                </p>
                
                <?php if ($isDebug): ?>
                    <div class="alert alert-danger mb-4 text-start">
                        <h5 class="alert-heading">Error Details:</h5>
                        <p><?= htmlspecialchars($errorMessage) ?></p>
                        
                        <?php if ($errorTrace): ?>
                            <hr>
                            <div class="small">
                                <pre class="mb-0"><?= htmlspecialchars($errorTrace) ?></pre>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <div class="error-actions mb-5">
                    <a href="/" class="btn btn-primary me-3">
                        <i class="fas fa-home me-2"></i> Go to Homepage
                    </a>
                    <button type="button" class="btn btn-outline-secondary" onclick="window.location.reload()">
                        <i class="fas fa-redo me-2"></i> Try Again
                    </button>
                </div>
                
                <div class="contact-support">
                    <h5 class="mb-3">Need Help?</h5>
                    <p class="mb-4">
                        If the problem persists, please contact our support team.
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="/contact" class="btn btn-outline-primary">
                            <i class="fas fa-envelope me-2"></i> Contact Support
                        </a>
                        <a href="tel:+1234567890" class="btn btn-outline-primary">
                            <i class="fas fa-phone me-2"></i> Call Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-content {
    padding: 3rem 0;
}

.error-image img {
    opacity: 0.85;
}

.contact-support {
    background-color: #f8f9fa;
    padding: 2rem;
    border-radius: 0.5rem;
}

@media (max-width: 768px) {
    .d-flex.justify-content-center.gap-3 {
        flex-direction: column;
    }
    
    .d-flex.justify-content-center.gap-3 .btn {
        margin-bottom: 0.5rem;
    }
}
</style> 