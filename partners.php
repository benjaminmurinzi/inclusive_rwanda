<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
$page_title = 'Our Partners';
require_once 'includes/header.php';

// Get database connection
$conn = getDBConnection();

// Fetch all partners from database
$partners = [];
$sql = "SELECT * FROM partners ORDER BY name ASC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $partners[] = $row;
    }
}

$conn->close();
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>Our Partners</h1>
        <p class="lead">Working together for an inclusive Rwanda</p>
    </div>
</section>

<!-- Partners Introduction -->
<section class="partners-intro">
    <div class="container">
        <div class="intro-content">
            <h2>Collaboration for Impact</h2>
            <p class="large-text">
                We believe that creating lasting change requires collaboration. That's why we 
                work closely with government agencies, non-profit organizations, private sector 
                companies, and disability advocacy groups. Together, we're building a more 
                accessible and inclusive Rwanda.
            </p>
        </div>
    </div>
</section>

<!-- Partners List -->
<section class="partners-list">
    <div class="container">
        <h2>Our Valued Partners</h2>
        
        <?php if (count($partners) > 0): ?>
            <div class="partners-grid">
                <?php foreach ($partners as $partner): ?>
                    <article class="partner-card" aria-labelledby="partner-<?php echo $partner['id']; ?>">
                        <?php if (!empty($partner['logo'])): ?>
                            <div class="partner-logo">
                                <img 
                                    src="<?php echo BASE_URL . 'admin/uploads/' . htmlspecialchars($partner['logo']); ?>" 
                                    alt="<?php echo htmlspecialchars($partner['name']) . ' logo'; ?>"
                                    loading="lazy"
                                >
                            </div>
                        <?php else: ?>
                            <div class="partner-logo-placeholder" aria-hidden="true">
                                🤝
                            </div>
                        <?php endif; ?>
                        
                        <div class="partner-content">
                            <h3 id="partner-<?php echo $partner['id']; ?>">
                                <?php echo htmlspecialchars($partner['name']); ?>
                            </h3>
                            <p><?php echo htmlspecialchars($partner['description']); ?></p>
                            
                            <?php if (!empty($partner['website'])): ?>
                                <a 
                                    href="<?php echo htmlspecialchars($partner['website']); ?>" 
                                    class="partner-link" 
                                    target="_blank" 
                                    rel="noopener noreferrer"
                                    aria-label="Visit <?php echo htmlspecialchars($partner['name']); ?> website (opens in new tab)"
                                >
                                    Visit Website →
                                </a>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-content">
                <p>We are currently building our partnership network. Check back soon!</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Partnership Benefits -->
<section class="partnership-benefits">
    <div class="container">
        <h2>Benefits of Partnership</h2>
        <div class="benefits-grid">
            <div class="benefit-item">
                <div class="benefit-icon" aria-hidden="true">🌍</div>
                <h3>Wider Impact</h3>
                <p>Reach more communities and create greater change together.</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon" aria-hidden="true">💡</div>
                <h3>Shared Knowledge</h3>
                <p>Exchange expertise and learn from each other's experiences.</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon" aria-hidden="true">🤝</div>
                <h3>Stronger Advocacy</h3>
                <p>Amplify voices and advocate more effectively for policy changes.</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon" aria-hidden="true">📈</div>
                <h3>Resource Sharing</h3>
                <p>Pool resources to maximize efficiency and effectiveness.</p>
            </div>
        </div>
    </div>
</section>

<!-- Become a Partner -->
<section class="become-partner">
    <div class="container">
        <div class="become-partner-content">
            <h2>Become a Partner</h2>
            <p>
                Are you an organization committed to disability inclusion? We'd love to 
                explore partnership opportunities with you. Together, we can create a more 
                inclusive Rwanda for everyone.
            </p>
            <a href="<?php echo BASE_URL; ?>contact.php" class="btn btn-primary">Get in Touch</a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>