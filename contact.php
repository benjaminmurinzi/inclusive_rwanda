<?php
require_once 'includes/config.php';
require_once 'includes/contact_handler.php';
$page_title = 'Contact Us';
require_once 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>Contact Us</h1>
        <p class="lead">We'd love to hear from you</p>
    </div>
</section>

<!-- Contact Information -->
<section class="contact-info">
    <div class="container">
        <div class="contact-intro">
            <h2>Get in Touch</h2>
            <p class="large-text">
                Have questions about our services? Want to partner with us? Or simply want 
                to learn more about disability inclusion? We're here to help. Reach out to 
                us using the form below or through our contact details.
            </p>
        </div>
        
        <div class="contact-details-grid">
            <div class="contact-detail-card">
                <div class="contact-icon" aria-hidden="true">📍</div>
                <h3>Office Location</h3>
                <p>
                    Kigali, Rwanda<br>
                    KG 123 Street<br>
                    Gasabo District
                </p>
            </div>
            
            <div class="contact-detail-card">
                <div class="contact-icon" aria-hidden="true">📧</div>
                <h3>Email</h3>
                <p>
                    <a href="mailto:info@inclusiverw.org">info@inclusiverw.org</a><br>
                    <a href="mailto:support@inclusiverw.org">support@inclusiverw.org</a>
                </p>
            </div>
            
            <div class="contact-detail-card">
                <div class="contact-icon" aria-hidden="true">📞</div>
                <h3>Phone</h3>
                <p>
                    Office: +250 788 123 456<br>
                    Mobile: +250 788 654 321
                </p>
            </div>
            
            <div class="contact-detail-card">
                <div class="contact-icon" aria-hidden="true">🕒</div>
                <h3>Working Hours</h3>
                <p>
                    Monday - Friday<br>
                    8:00 AM - 5:00 PM<br>
                    (East Africa Time)
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form -->
<section class="contact-form-section">
    <div class="container">
        <div class="form-container">
            <h2>Send Us a Message</h2>
            
            <?php if ($success): ?>
                <div class="alert alert-success" role="alert">
                    <strong>Thank you!</strong> Your message has been sent successfully. 
                    We'll get back to you as soon as possible.
                </div>
            <?php endif; ?>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-error" role="alert">
                    <strong>Error:</strong> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="contact-form" novalidate>
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">
                            Full Name <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="<?php echo htmlspecialchars($name); ?>"
                            required
                            aria-required="true"
                            placeholder="Enter your full name"
                        >
                    </div>
                    
                    <div class="form-group">
                        <label for="email">
                            Email Address <span class="required">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="<?php echo htmlspecialchars($email); ?>"
                            required
                            aria-required="true"
                            placeholder="your.email@example.com"
                        >
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="subject">
                        Subject <span class="required">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="subject" 
                        name="subject" 
                        value="<?php echo htmlspecialchars($subject); ?>"
                        required
                        aria-required="true"
                        placeholder="What is your message about?"
                    >
                </div>
                
                <div class="form-group">
                    <label for="message">
                        Message <span class="required">*</span>
                    </label>
                    <textarea 
                        id="message" 
                        name="message" 
                        rows="6"
                        required
                        aria-required="true"
                        placeholder="Tell us more about your inquiry..."
                    ><?php echo htmlspecialchars($message); ?></textarea>
                    <small>Minimum 10 characters</small>
                </div>
                
                <button type="submit" name="submit_contact" class="btn btn-primary btn-large">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section">
    <div class="container">
        <h2>Frequently Asked Questions</h2>
        <div class="faq-grid">
            <div class="faq-item">
                <h3>How quickly will I receive a response?</h3>
                <p>We aim to respond to all inquiries within 1-2 business days.</p>
            </div>
            
            <div class="faq-item">
                <h3>Can I visit your office?</h3>
                <p>Yes! We welcome visitors. Please contact us in advance to schedule a visit.</p>
            </div>
            
            <div class="faq-item">
                <h3>Do you offer services outside Kigali?</h3>
                <p>Yes, we provide services throughout Rwanda. Contact us to discuss your needs.</p>
            </div>
            
            <div class="faq-item">
                <h3>How can I become a volunteer?</h3>
                <p>We appreciate your interest! Send us a message with "Volunteer" in the subject line.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>