<?php
require_once 'config.php';
require_once 'db.php';
require_once 'email_config.php';

// Initialize variables
$success = false;
$error = '';
$name = '';
$email = '';
$subject = '';
$message = '';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    
    // Get and sanitize input
    $name = sanitizeInput($_POST['name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $subject = sanitizeInput($_POST['subject'] ?? '');
    $message = sanitizeInput($_POST['message'] ?? '');
    
    // Validate input
    if (empty($name)) {
        $error = 'Please enter your name.';
    } elseif (empty($email)) {
        $error = 'Please enter your email address.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (empty($subject)) {
        $error = 'Please enter a subject.';
    } elseif (empty($message)) {
        $error = 'Please enter your message.';
    } elseif (strlen($message) < 10) {
        $error = 'Message must be at least 10 characters long.';
    } else {
        // All validation passed, save to database
        $conn = getDBConnection();
        
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        
        if ($stmt->execute()) {
            // Send email notification
            $email_sent = sendContactNotification($name, $email, $subject, $message);
            
            $success = true;
            // Clear form fields on success
            $name = '';
            $email = '';
            $subject = '';
            $message = '';
        } else {
            $error = 'Sorry, there was an error submitting your message. Please try again.';
        }
        
        $stmt->close();
        $conn->close();
    }
}

// Function to send email notification
function sendContactNotification($name, $email, $subject, $message) {
    $email_subject = "New Contact: " . $subject;
    
    // Create HTML email
    $html_message = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; background: #f5f5f5; }
            .header { background: linear-gradient(135deg, #1E88E5, #43A047); color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
            .body { background: white; padding: 30px; border-radius: 0 0 8px 8px; }
            .info-row { margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee; }
            .label { font-weight: bold; color: #1E88E5; }
            .message-box { background: #f9f9f9; padding: 15px; border-left: 4px solid #1E88E5; margin-top: 20px; }
            .footer { text-align: center; margin-top: 20px; padding-top: 20px; border-top: 2px solid #eee; color: #757575; font-size: 14px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>📧 New Contact Message</h1>
                <p>Inclusive Rwanda</p>
            </div>
            <div class='body'>
                <p>You have received a new message:</p>
                
                <div class='info-row'>
                    <div class='label'>From:</div>
                    <div>" . htmlspecialchars($name) . "</div>
                </div>
                
                <div class='info-row'>
                    <div class='label'>Email:</div>
                    <div><a href='mailto:" . htmlspecialchars($email) . "'>" . htmlspecialchars($email) . "</a></div>
                </div>
                
                <div class='info-row'>
                    <div class='label'>Subject:</div>
                    <div>" . htmlspecialchars($subject) . "</div>
                </div>
                
                <div class='info-row'>
                    <div class='label'>Date:</div>
                    <div>" . date('F j, Y \a\t g:i A') . "</div>
                </div>
                
                <div class='message-box'>
                    <div class='label'>Message:</div>
                    <div>" . nl2br(htmlspecialchars($message)) . "</div>
                </div>
                
                <div class='footer'>
                    <p>Sent from Inclusive Rwanda contact form</p>
                    <p><a href='" . BASE_URL . "admin/dashboard.php' style='color: #1E88E5; text-decoration: none;'>→ View in Admin Panel</a></p>
                </div>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Plain text version
    $plain_message = "New Contact Form Submission\n\n";
    $plain_message .= "From: " . $name . "\n";
    $plain_message .= "Email: " . $email . "\n";
    $plain_message .= "Subject: " . $subject . "\n";
    $plain_message .= "Message:\n" . $message;
    
    // Send email using our email function
    return sendEmail(ADMIN_EMAIL, $email_subject, $html_message, $plain_message, $email);
}
?>