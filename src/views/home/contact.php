<?php
/**
 * Contact page template
 * 
 * Variables available:
 * $validationErrors - Array of validation errors
 * $formData - Array of previously submitted form data
 */

// Get settings
$settingService = new \App\Services\SettingService();
$address = $settingService->getSetting('address', '123 Main St, City, Country');
$contactEmail = $settingService->getSetting('contact_email', 'contact@example.com');
$contactPhone = $settingService->getSetting('contact_phone', '+1234567890');
$businessHours = $settingService->getSetting('business_hours', 'Monday to Friday, 9AM to 5PM');
?>

<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-4 fw-bold">Contact Us</h1>
            <p class="lead text-muted">We'd love to hear from you</p>
        </div>
    </div>
    
    <div class="row">
        <!-- Contact Form -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h4 mb-4">Send us a message</h2>
                    
                    <form action="/contact" method="post">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Your Name</label>
                                <input type="text" class="form-control <?= isset($validationErrors['name']) ? 'is-invalid' : '' ?>" id="name" name="name" placeholder="John Doe" value="<?= htmlspecialchars($formData['name'] ?? '') ?>" required>
                                <?php if (isset($validationErrors['name'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($validationErrors['name']) ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label">Your Email</label>
                                <input type="email" class="form-control <?= isset($validationErrors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" placeholder="john@example.com" value="<?= htmlspecialchars($formData['email'] ?? '') ?>" required>
                                <?php if (isset($validationErrors['email'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($validationErrors['email']) ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-12">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" class="form-control <?= isset($validationErrors['subject']) ? 'is-invalid' : '' ?>" id="subject" name="subject" placeholder="How can we help you?" value="<?= htmlspecialchars($formData['subject'] ?? '') ?>" required>
                                <?php if (isset($validationErrors['subject'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($validationErrors['subject']) ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-12">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control <?= isset($validationErrors['message']) ? 'is-invalid' : '' ?>" id="message" name="message" rows="5" placeholder="Your message here..." required><?= htmlspecialchars($formData['message'] ?? '') ?></textarea>
                                <?php if (isset($validationErrors['message'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($validationErrors['message']) ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input <?= isset($validationErrors['privacy']) ? 'is-invalid' : '' ?>" type="checkbox" id="privacy" name="privacy" required <?= isset($formData['privacy']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="privacy">
                                        I agree to the <a href="/privacy-policy">Privacy Policy</a>
                                    </label>
                                    <?php if (isset($validationErrors['privacy'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($validationErrors['privacy']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Contact Information -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h2 class="h4 mb-4">Contact Information</h2>
                    
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-primary fa-fw fa-lg me-3 mt-1"></i>
                        </div>
                        <div>
                            <h5 class="h6">Address</h5>
                            <p class="text-muted mb-0"><?= htmlspecialchars($address) ?></p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-envelope text-primary fa-fw fa-lg me-3 mt-1"></i>
                        </div>
                        <div>
                            <h5 class="h6">Email</h5>
                            <p class="mb-0">
                                <a href="mailto:<?= htmlspecialchars($contactEmail) ?>" class="text-muted"><?= htmlspecialchars($contactEmail) ?></a>
                            </p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-phone text-primary fa-fw fa-lg me-3 mt-1"></i>
                        </div>
                        <div>
                            <h5 class="h6">Phone</h5>
                            <p class="mb-0">
                                <a href="tel:<?= preg_replace('/[^0-9+]/', '', $contactPhone) ?>" class="text-muted"><?= htmlspecialchars($contactPhone) ?></a>
                            </p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-primary fa-fw fa-lg me-3 mt-1"></i>
                        </div>
                        <div>
                            <h5 class="h6">Business Hours</h5>
                            <p class="text-muted mb-0"><?= htmlspecialchars($businessHours) ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Social Media -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h4 mb-4">Connect With Us</h2>
                    
                    <div class="d-flex justify-content-between">
                        <a href="#" class="btn btn-outline-primary btn-social">
                            <i class="fab fa-facebook-f fa-fw"></i>
                        </a>
                        <a href="#" class="btn btn-outline-primary btn-social">
                            <i class="fab fa-twitter fa-fw"></i>
                        </a>
                        <a href="#" class="btn btn-outline-primary btn-social">
                            <i class="fab fa-instagram fa-fw"></i>
                        </a>
                        <a href="#" class="btn btn-outline-primary btn-social">
                            <i class="fab fa-linkedin-in fa-fw"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Map -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.215266952082!2d-73.98784692449356!3d40.75790963560158!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25855c6480299%3A0x55194ec5a1ae072e!2sTimes%20Square!5e0!3m2!1sen!2sus!4v1685710070887!5m2!1sen!2sus" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
    
    <!-- FAQ Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h2 class="h3 text-center mb-4">Frequently Asked Questions</h2>
            
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            What are your shipping options?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            We offer standard shipping (3-5 business days), express shipping (1-2 business days), and same-day delivery for select areas. Shipping costs vary based on the destination and the shipping method chosen. Free shipping is available for orders over $50.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            How can I track my order?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Once your order is shipped, you will receive a confirmation email with a tracking number. You can use this tracking number on our website under "Track Order" or directly on the carrier's website.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            What is your return policy?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            We offer a 30-day return policy. Items must be in original condition with tags attached and original packaging. Some products, such as personalized items or intimate products, are not eligible for return unless defective.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Do you ship internationally?
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes, we ship to many countries worldwide. International shipping costs and delivery times vary based on the destination. Please note that customers are responsible for any import duties or taxes required by their country.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-social {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style> 