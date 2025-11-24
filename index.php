<?php
require_once 'includes/config.php';
$page_title = 'Home';
require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero" aria-labelledby="hero-heading">
    <div class="container">
        <div class="hero-content">
            <h2 id="hero-heading">
                <span class="typing-text" id="typingText"></span>
            </h2>
            <p class="hero-subtitle">Building an inclusive society where everyone thrives</p>
            <p class="hero-description">
                We are dedicated to promoting disability inclusion, accessibility, 
                and equal opportunities for all Rwandans.
            </p>
            <a href="<?php echo BASE_URL; ?>about.php" class="btn btn-primary">Learn More</a>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="mission" aria-labelledby="mission-heading">
    <div class="container">
        <h2 id="mission-heading">Our Mission</h2>
        <div class="mission-grid">
            <div class="mission-card">
                <div class="icon" aria-hidden="true">🤝</div>
                <h3>Inclusion</h3>
                <p>Promoting equal participation for persons with disabilities in all aspects of society.</p>
            </div>
            <div class="mission-card">
                <div class="icon" aria-hidden="true">♿</div>
                <h3>Accessibility</h3>
                <p>Ensuring physical and digital environments are accessible to everyone.</p>
            </div>
            <div class="mission-card">
                <div class="icon" aria-hidden="true">📚</div>
                <h3>Education</h3>
                <p>Raising awareness about disability rights and inclusive practices.</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta" aria-labelledby="cta-heading">
    <div class="container">
        <h2 id="cta-heading">Get Involved</h2>
        <p>Join us in creating a more inclusive Rwanda</p>
        <a href="<?php echo BASE_URL; ?>contact.php" class="btn btn-secondary">Contact Us</a>
    </div>
</section>

<script>
// Typing animation for hero title only
document.addEventListener('DOMContentLoaded', function() {
    const typingElement = document.getElementById('typingText');
    if (!typingElement) return;
    
    const text = 'Welcome to Inclusive Rwanda';
    let charIndex = 0;
    
    function typeChar() {
        if (charIndex < text.length) {
            typingElement.textContent += text.charAt(charIndex);
            charIndex++;
            setTimeout(typeChar, 100); // 100ms per character (adjust for speed)
        } else {
            // Remove blinking cursor after typing is complete
            setTimeout(() => {
                typingElement.classList.add('typing-complete');
            }, 500);
        }
    }
    
    // Start typing after a brief delay
    setTimeout(typeChar, 500);
});
</script>

<?php require_once 'includes/footer.php'; ?>