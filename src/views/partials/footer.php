<?php
$settingService = new \App\Services\SettingService();
$siteName = $settingService->getSetting('site_name', 'Solo E-commerce');
$address = $settingService->getSetting('address', '123 Main St, City, Country');
$contactEmail = $settingService->getSetting('contact_email', 'contact@example.com');
$contactPhone = $settingService->getSetting('contact_phone', '+1234567890');
$facebookUrl = $settingService->getSetting('facebook_url', 'https://facebook.com');
$instagramUrl = $settingService->getSetting('instagram_url', 'https://instagram.com');
$twitterUrl = $settingService->getSetting('twitter_url', 'https://twitter.com');
?>

<footer class="bg-dark text-white pt-5 pb-4">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5>About Us</h5>
                <p><?= htmlspecialchars($siteName) ?> is an e-commerce platform offering a wide range of products at competitive prices.</p>
                <div class="social-links mt-3">
                    <a href="<?= htmlspecialchars($facebookUrl) ?>" class="text-white me-2" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="<?= htmlspecialchars($twitterUrl) ?>" class="text-white me-2" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a href="<?= htmlspecialchars($instagramUrl) ?>" class="text-white me-2" target="_blank"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <h5>Customer Service</h5>
                <ul class="list-unstyled">
                    <li><a href="/contact" class="text-white">Contact Us</a></li>
                    <li><a href="/shipping" class="text-white">Shipping Information</a></li>
                    <li><a href="/returns" class="text-white">Returns & Refunds</a></li>
                    <li><a href="/faq" class="text-white">FAQ</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h5>Contact Information</h5>
                <address>
                    <p><i class="fas fa-map-marker-alt me-2"></i> <?= htmlspecialchars($address) ?></p>
                    <p><i class="fas fa-envelope me-2"></i> <a href="mailto:<?= htmlspecialchars($contactEmail) ?>" class="text-white"><?= htmlspecialchars($contactEmail) ?></a></p>
                    <p><i class="fas fa-phone me-2"></i> <?= htmlspecialchars($contactPhone) ?></p>
                </address>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($siteName) ?>. All Rights Reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p>
                    <a href="/privacy-policy" class="text-white me-3">Privacy Policy</a>
                    <a href="/terms-of-service" class="text-white">Terms of Service</a>
                </p>
            </div>
        </div>
    </div>
</footer> 