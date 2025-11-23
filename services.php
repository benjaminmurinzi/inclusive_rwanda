<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
$page_title = 'Our Services';
require_once 'includes/header.php';

// Get database connection
$conn = getDBConnection();

// Fetch all services from database
$services = [];
$sql = "SELECT * FROM services ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

$conn->close();
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>Our Services</h1>
        <p class="lead">Comprehensive solutions for accessibility and inclusion</p>
    </div>
</section>

<!-- Services Introduction -->
<section class="services-intro">
    <div class="container">
        <div class="intro-content">
            <h2>What We Offer</h2>
            <p class="large-text">
                At Inclusive Rwanda, we provide a range of specialized services designed to 
                promote accessibility, inclusion, and equal opportunities for persons with 
                disabilities. Our expert team works closely with organizations, government 
                agencies, and communities to create lasting change.
            </p>
        </div>
    </div>
</section>

<!-- Services List -->
<section class="services-list">
    <div class="container">
        <?php if (count($services) > 0): ?>
            <div class="services-grid">
                <?php foreach ($services as $index => $service): ?>
                    <article class="service-card" aria-labelledby="service-<?php echo $service['id']; ?>">
                        <div class="service-icon" aria-hidden="true">
                            <?php echo htmlspecialchars($service['icon']); ?>
                        </div>
                        <div class="service-content">
                            <h3 id="service-<?php echo $service['id']; ?>">
                                <?php echo htmlspecialchars($service['title']); ?>
                            </h3>
                            <p><?php echo htmlspecialchars($service['description']); ?></p>
                            
                            <?php if (!empty($service['image'])): ?>
                                <div class="service-image">
                                    <img 
                                        src="<?php echo BASE_URL . 'admin/uploads/' . htmlspecialchars($service['image']); ?>" 
                                        alt="<?php echo htmlspecialchars($service['title']); ?>"
                                        loading="lazy"
                                    >
                                </div>
                            <?php endif; ?>
                            
                            <div class="service-meta">
                                <small>
                                    Added: <?php echo date('F j, Y', strtotime($service['created_at'])); ?>
                                </small>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-content">
                <p>No services available at the moment. Please check back later.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Why Choose Us -->
<section class="why-choose-us">
    <div class="container">
        <h2>Why Choose Our Services?</h2>
        <div class="benefits-grid">
            <div class="benefit-card">
                <span class="benefit-icon" aria-hidden="true">✓</span>
                <h3>Expert Team</h3>
                <p>Our team includes specialists in accessibility, disability rights, and inclusive design.</p>
            </div>
            <div class="benefit-card">
                <span class="benefit-icon" aria-hidden="true">✓</span>
                <h3>Proven Results</h3>
                <p>We have successfully helped hundreds of organizations become more inclusive.</p>
            </div>
            <div class="benefit-card">
                <span class="benefit-icon" aria-hidden="true">✓</span>
                <h3>Customized Solutions</h3>
                <p>Every organization is unique. We tailor our services to meet your specific needs.</p>
            </div>
            <div class="benefit-card">
                <span class="benefit-icon" aria-hidden="true">✓</span>
                <h3>Ongoing Support</h3>
                <p>We don't just consult and leave. We provide continuous support and guidance.</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta">
    <div class="container">
        <h2>Ready to Get Started?</h2>
        <p>Contact us today to learn how we can help your organization</p>
        <a href="<?php echo BASE_URL; ?>contact.php" class="btn btn-secondary">Contact Us</a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>