<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

$page_title = 'Dashboard';

// Get statistics from database
$conn = getDBConnection();

// Count services
$services_count = 0;
$result = $conn->query("SELECT COUNT(*) as total FROM services");
if ($result && $row = $result->fetch_assoc()) {
    $services_count = $row['total'];
}

// Count partners
$partners_count = 0;
$result = $conn->query("SELECT COUNT(*) as total FROM partners");
if ($result && $row = $result->fetch_assoc()) {
    $partners_count = $row['total'];
}

// Count news articles
$news_count = 0;
$result = $conn->query("SELECT COUNT(*) as total FROM news");
if ($result && $row = $result->fetch_assoc()) {
    $news_count = $row['total'];
}

// Count contact messages
$messages_count = 0;
$result = $conn->query("SELECT COUNT(*) as total FROM contact_messages");
if ($result && $row = $result->fetch_assoc()) {
    $messages_count = $row['total'];
}

// Count unread messages
$unread_count = 0;
$result = $conn->query("SELECT COUNT(*) as total FROM contact_messages WHERE read_status = 0");
if ($result && $row = $result->fetch_assoc()) {
    $unread_count = $row['total'];
}

// Get recent contact messages
$recent_messages = [];
$result = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $recent_messages[] = $row;
    }
}

$conn->close();

require_once 'includes/admin_header.php';
?>

<div class="admin-card">
    <h2>Welcome back, <?php echo htmlspecialchars($current_admin['username']); ?>! 👋</h2>
    <p>Here's an overview of your website content.</p>
</div>

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-box">
        <div class="stat-number"><?php echo $services_count; ?></div>
        <div class="stat-label">Services</div>
    </div>
    
    <div class="stat-box">
        <div class="stat-number"><?php echo $partners_count; ?></div>
        <div class="stat-label">Partners</div>
    </div>
    
    <div class="stat-box">
        <div class="stat-number"><?php echo $news_count; ?></div>
        <div class="stat-label">News Articles</div>
    </div>
    
    <div class="stat-box">
        <div class="stat-number"><?php echo $messages_count; ?></div>
        <div class="stat-label">Contact Messages</div>
    </div>
</div>

<!-- Quick Actions -->
<div class="admin-card">
    <h3>Quick Actions</h3>
    <div class="btn-group">
        <a href="manage_services.php" class="btn btn-primary">➕ Add Service</a>
        <a href="manage_partners.php" class="btn btn-primary">➕ Add Partner</a>
        <a href="manage_news.php" class="btn btn-primary">➕ Add News</a>
        <a href="<?php echo BASE_URL; ?>index.php" class="btn btn-secondary" target="_blank">🌐 View Website</a>
    </div>
</div>

<!-- Recent Contact Messages -->
<div class="admin-card">
    <h3>Recent Contact Messages (<?php echo $unread_count; ?> Unread)</h3>
    
    <?php if (count($recent_messages) > 0): ?>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: var(--light-color); text-align: left;">
                    <th style="padding: 1rem;">Name</th>
                    <th style="padding: 1rem;">Email</th>
                    <th style="padding: 1rem;">Subject</th>
                    <th style="padding: 1rem;">Date</th>
                    <th style="padding: 1rem;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_messages as $message): ?>
                    <tr style="border-bottom: 1px solid #e0e0e0;">
                        <td style="padding: 1rem;"><?php echo htmlspecialchars($message['name']); ?></td>
                        <td style="padding: 1rem;"><?php echo htmlspecialchars($message['email']); ?></td>
                        <td style="padding: 1rem;"><?php echo htmlspecialchars($message['subject']); ?></td>
                        <td style="padding: 1rem;"><?php echo date('M j, Y', strtotime($message['created_at'])); ?></td>
                        <td style="padding: 1rem;">
                            <?php if ($message['read_status'] == 0): ?>
                                <span style="color: #E53935; font-weight: bold;">● Unread</span>
                            <?php else: ?>
                                <span style="color: #43A047;">✓ Read</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No contact messages yet.</p>
    <?php endif; ?>
</div>

<!-- System Information -->
<div class="admin-card">
    <h3>System Information</h3>
    <table style="width: 100%;">
        <tr>
            <td style="padding: 0.5rem 0;"><strong>PHP Version:</strong></td>
            <td><?php echo phpversion(); ?></td>
        </tr>
        <tr>
            <td style="padding: 0.5rem 0;"><strong>Server:</strong></td>
            <td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
        </tr>
        <tr>
            <td style="padding: 0.5rem 0;"><strong>Database:</strong></td>
            <td>MySQL (<?php echo DB_NAME; ?>)</td>
        </tr>
        <tr>
            <td style="padding: 0.5rem 0;"><strong>Logged in as:</strong></td>
            <td><?php echo htmlspecialchars($current_admin['email']); ?></td>
        </tr>
    </table>
</div>

<?php require_once 'includes/admin_footer.php'; ?>