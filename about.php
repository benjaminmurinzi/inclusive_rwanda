<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
$page_title = 'About Us';
require_once 'includes/header.php';

// Get database connection
$conn = getDBConnection();

// Get count of services (we'll display this)
$service_count = 0;
$result = $conn->query("SELECT COUNT(*) as total FROM services");
if ($result && $row = $result->fetch_assoc()) {
    $service_count = $row['total'];
}

$conn->close();
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>About Inclusive Rwanda</h1>
        <p class="lead">Building a more inclusive and accessible Rwanda for everyone</p>
    </div>
</section>

<!-- About Content -->
<section class="about-content">
    <div class="container">
        <div class="about-intro">
            <h2>Who We Are</h2>
            <p class="large-text">
                Inclusive Rwanda is a leading organization dedicated to promoting disability 
                inclusion and accessibility across Rwanda. We believe that every person, 
                regardless of ability, deserves equal opportunities to participate fully in society.
            </p>
        </div>

        <div class="about-grid">
            <div class="about-card">
                <h3>Our Vision</h3>
                <p>
                    A Rwanda where persons with disabilities are fully included in all aspects 
                    of social, economic, and political life, with equal access to opportunities 
                    and resources.
                </p>
            </div>

            <div class="about-card">
                <h3>Our Mission</h3>
                <p>
                    To promote and advance the rights of persons with disabilities through 
                    advocacy, education, capacity building, and the promotion of accessible 
                    environments and inclusive practices.
                </p>
            </div>

            <div class="about-card">
                <h3>Our Values</h3>
                <ul>
                    <li><strong>Inclusion:</strong> Everyone belongs and has value</li>
                    <li><strong>Dignity:</strong> Respect for all individuals</li>
                    <li><strong>Equity:</strong> Fair treatment and equal opportunities</li>
                    <li><strong>Innovation:</strong> Creative solutions to barriers</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- What We Do -->
<section class="what-we-do">
    <div class="container">
        <h2>What We Do</h2>
        <div class="services-preview">
            <p class="text-center large-text">
                We currently offer <strong><?php echo $service_count; ?> specialized services</strong> 
                designed to promote accessibility and inclusion.
            </p>
            <div class="work-areas">
                <div class="work-card">
                    <span class="work-icon" aria-hidden="true">🏢</span>
                    <h3>Accessibility</h3>
                    <p>
                        We work with organizations to make their physical spaces and digital 
                        platforms accessible to all, following international accessibility standards.
                    </p>
                </div>

                <div class="work-card">
                    <span class="work-icon" aria-hidden="true">👥</span>
                    <h3>Capacity Building</h3>
                    <p>
                        We provide training and workshops to organizations, helping them build 
                        inclusive workplaces and understand disability rights.
                    </p>
                </div>

                <div class="work-card">
                    <span class="work-icon" aria-hidden="true">📢</span>
                    <h3>Advocacy</h3>
                    <p>
                        We advocate for policy changes and enforce implementation of laws that 
                        protect the rights of persons with disabilities.
                    </p>
                </div>

                <div class="work-card">
                    <span class="work-icon" aria-hidden="true">🤝</span>
                    <h3>Partnerships</h3>
                    <p>
                        We collaborate with government, NGOs, and private sector to create 
                        a coordinated approach to disability inclusion.
                    </p>
                </div>
            </div>
            <div class="text-center" style="margin-top: 2rem;">
                <a href="<?php echo BASE_URL; ?>services.php" class="btn btn-primary">View All Services</a>
            </div>
        </div>
    </div>
</section>

<!-- Our Impact -->
<section class="our-impact">
    <div class="container">
        <h2>Our Impact</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">500+</div>
                <div class="stat-label">People Trained</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">50+</div>
                <div class="stat-label">Organizations Supported</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">100+</div>
                <div class="stat-label">Accessibility Audits</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">20+</div>
                <div class="stat-label">Policy Changes Influenced</div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta">
    <div class="container">
        <h2>Join Us in Making a Difference</h2>
        <p>Partner with us to create a more inclusive Rwanda</p>
        <a href="<?php echo BASE_URL; ?>contact.php" class="btn btn-secondary">Get in Touch</a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>