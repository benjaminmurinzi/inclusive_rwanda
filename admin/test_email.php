<?php
require_once '../includes/config.php';
require_once '../includes/email_config.php';
require_once '../includes/auth.php';

$page_title = 'Test Email';
$result = '';

if (isset($_POST['send_test'])) {
    $test_email = $_POST['test_email'] ?? ADMIN_EMAIL;
    
    $html = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; }
            .box { background: #E3F2FD; padding: 20px; border-radius: 8px; border-left: 4px solid #1E88E5; }
        </style>
    </head>
    <body>
        <h1>🎉 Test Email from Inclusive Rwanda</h1>
        <div class='box'>
            <p><strong>Congratulations!</strong></p>
            <p>Your email system is working correctly.</p>
            <p>Sent at: " . date('F j, Y \a\t g:i A') . "</p>
        </div>
    </body>
    </html>
    ";
    
    $plain = "Test Email\n\nYour email system is working!\nSent at: " . date('F j, Y \a\t g:i A');
    
    if (sendEmail($test_email, "Test Email - Inclusive Rwanda", $html, $plain)) {
        $result = "<div class='alert alert-success'>✅ Test email sent successfully to: " . htmlspecialchars($test_email) . "</div>";
    } else {
        $result = "<div class='alert alert-error'>❌ Failed to send email. Check your email configuration.</div>";
    }
}

require_once 'includes/admin_header.php';
?>

<div class="admin-card">
    <h2>📧 Email System Test</h2>
    <p>Use this tool to test if your email system is working correctly.</p>
    
    <?php echo $result; ?>
    
    <form method="POST" class="contact-form">
        <div class="form-group">
            <label for="test_email">Send test email to:</label>
            <input 
                type="email" 
                id="test_email" 
                name="test_email" 
                value="<?php echo htmlspecialchars(ADMIN_EMAIL); ?>"
                required
            >
            <small>Enter your email address to receive a test email</small>
        </div>
        
        <button type="submit" name="send_test" class="btn btn-primary">Send Test Email</button>
    </form>
</div>

<div class="admin-card">
    <h3>⚙️ Email Configuration Status</h3>
    <table style="width: 100%;">
        <tr>
            <td style="padding: 0.5rem 0;"><strong>Admin Email:</strong></td>
            <td><?php echo htmlspecialchars(ADMIN_EMAIL); ?></td>
        </tr>
        <tr>
            <td style="padding: 0.5rem 0;"><strong>Site Email:</strong></td>
            <td><?php echo htmlspecialchars(SITE_EMAIL); ?></td>
        </tr>
        <tr>
            <td style="padding: 0.5rem 0;"><strong>PHP mail() function:</strong></td>
            <td><?php echo function_exists('mail') ? '✅ Available' : '❌ Not Available'; ?></td>
        </tr>
        <tr>
            <td style="padding: 0.5rem 0;"><strong>SMTP Server:</strong></td>
            <td><?php echo ini_get('SMTP') ?: 'Not configured'; ?></td>
        </tr>
    </table>
</div>

<div class="admin-card">
    <h3>📝 Setup Instructions</h3>
    
    <h4>Step 1: Update Admin Email</h4>
    <p>Edit <code>includes/email_config.php</code> and change:</p>
    <pre style="background: #f5f5f5; padding: 1rem; border-radius: 4px; overflow-x: auto;">define('ADMIN_EMAIL', 'your-email@gmail.com'); // Change this to your email</pre>
    
    <h4>Step 2: Configure PHP Mail</h4>
    <p><strong>For Windows (XAMPP):</strong></p>
    <ol>
        <li>Open <code>C:\xampp\php\php.ini</code></li>
        <li>Find <code>[mail function]</code> section</li>
        <li>Set: <code>SMTP = localhost</code></li>
        <li>Set: <code>smtp_port = 25</code></li>
        <li>Restart Apache in XAMPP</li>
    </ol>
    
    <p><strong>For Linux (LAMP):</strong></p>
    <ol>
        <li>Install sendmail: <code>sudo apt-get install sendmail</code></li>
        <li>Start sendmail: <code>sudo service sendmail start</code></li>
        <li>Restart Apache: <code>sudo service apache2 restart</code></li>
    </ol>
    
    <h4>Step 3: For Production (Real Server)</h4>
    <p>Consider using email services like:</p>
    <ul>
        <li><strong>SendGrid</strong> - Free tier available</li>
        <li><strong>Mailgun</strong> - Free tier available</li>
        <li><strong>Amazon SES</strong> - Very cheap</li>
        <li><strong>PHPMailer with Gmail SMTP</strong> - Use your Gmail account</li>
    </ul>
    
    <div style="background: #FFF3E0; padding: 1rem; border-radius: 4px; margin-top: 1rem;">
        <strong>⚠️ Note:</strong> Email on localhost (XAMPP/LAMP) may not work perfectly. 
        The system will still save messages to the database even if email fails.
    </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>