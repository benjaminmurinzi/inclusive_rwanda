<?php
// Email configuration using PHPMailer with Gmail SMTP

// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Require PHPMailer files
require_once __DIR__ . '/phpmailer/Exception.php';
require_once __DIR__ . '/phpmailer/PHPMailer.php';
require_once __DIR__ . '/phpmailer/SMTP.php';

// Email configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587); // or 465 for SSL
define('SMTP_USERNAME', 'inclusiverwanda@gmail.com'); // Your Gmail
define('SMTP_PASSWORD', 'vixr isrr mfte giwk'); //  your 16-character app password
define('SMTP_ENCRYPTION', 'tls'); // or 'ssl'

define('ADMIN_EMAIL', 'inclusiverwanda@gmail.com'); // Where to receive notifications
define('SITE_EMAIL', 'inclusiverwanda@gmail.com'); // From address
define('SITE_NAME', 'Inclusive Rwanda');

// Email log directory (backup)
define('EMAIL_LOG_DIR', dirname(__DIR__) . '/admin/email_logs/');

// Create directory if it doesn't exist
if (!file_exists(EMAIL_LOG_DIR)) {
    @mkdir(EMAIL_LOG_DIR, 0755, true);
}

/**
 * Send email using PHPMailer with Gmail SMTP
 */
function sendEmail($to, $subject, $html_message, $plain_message = '', $reply_to = null) {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_ENCRYPTION;
        $mail->Port       = SMTP_PORT;
        
        // Disable SSL verification for localhost (remove in production)
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
        // Recipients
        $mail->setFrom(SITE_EMAIL, SITE_NAME);
        $mail->addAddress($to);
        
        if ($reply_to) {
            $mail->addReplyTo($reply_to);
        }
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $html_message;
        $mail->AltBody = $plain_message ?: strip_tags($html_message);
        
        // Send email
        $result = $mail->send();
        
        // Log email for backup
        logEmail($to, $subject, $html_message, $reply_to, $result);
        
        return $result;
        
    } catch (Exception $e) {
        // Log error
        error_log("Email Error: {$mail->ErrorInfo}");
        
        // Log to file
        logEmail($to, $subject, $html_message, $reply_to, false, $mail->ErrorInfo);
        
        return false;
    }
}

/**
 * Log emails to file
 */
function logEmail($to, $subject, $message, $reply_to = null, $sent = true, $error = null) {
    if (!is_dir(EMAIL_LOG_DIR)) {
        return;
    }
    
    $log_file = EMAIL_LOG_DIR . 'email_' . date('Y-m-d') . '.html';
    
    $status_badge = $sent ? 
        "<span style='background: #43A047; color: white; padding: 5px 10px; border-radius: 4px;'>✓ SENT SUCCESSFULLY</span>" : 
        "<span style='background: #E53935; color: white; padding: 5px 10px; border-radius: 4px;'>✗ FAILED</span>";
    
    $error_section = '';
    if ($error) {
        $error_section = "
        <div style='background: #FFEBEE; padding: 15px; border-radius: 4px; margin-top: 10px; border-left: 4px solid #E53935;'>
            <strong style='color: #C62828;'>Error:</strong> " . htmlspecialchars($error) . "
        </div>";
    }
    
    $log_content = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; margin: 0; background: #f5f5f5; }
            .email-log { background: white; margin: 20px; padding: 0; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden; }
            .email-header { background: linear-gradient(135deg, #1E88E5, #43A047); color: white; padding: 20px; }
            .info-section { padding: 20px; background: #f9f9f9; }
            .info-row { margin: 8px 0; padding: 8px; background: white; border-radius: 4px; }
            .label { font-weight: bold; color: #1E88E5; display: inline-block; width: 100px; }
            .email-content { padding: 20px; }
        </style>
    </head>
    <body>
        <div class='email-log'>
            <div class='email-header'>
                <h2 style='margin: 0 0 10px 0;'>📧 Email Log</h2>
                <p style='margin: 0 0 10px 0; opacity: 0.9;'>" . date('F j, Y \a\t g:i:s A') . "</p>
                " . $status_badge . "
            </div>
            
            <div class='info-section'>
                <div class='info-row'>
                    <span class='label'>To:</span> " . htmlspecialchars($to) . "
                </div>
                <div class='info-row'>
                    <span class='label'>Subject:</span> " . htmlspecialchars($subject) . "
                </div>";
    
    if ($reply_to) {
        $log_content .= "
                <div class='info-row'>
                    <span class='label'>Reply To:</span> " . htmlspecialchars($reply_to) . "
                </div>";
    }
    
    $log_content .= "
                <div class='info-row'>
                    <span class='label'>Time:</span> " . date('F j, Y \a\t g:i:s A') . "
                </div>
                " . $error_section . "
            </div>
            
            <div class='email-content'>
                <h3 style='color: #1E88E5;'>Email Content:</h3>
                <hr style='border: 0; height: 1px; background: #e0e0e0;'>
                " . $message . "
            </div>
        </div>
        <div style='height: 30px;'></div>
    </body>
    </html>
    ";
    
    @file_put_contents($log_file, $log_content, FILE_APPEND);
}
?>