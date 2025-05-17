<?php
// Load configuration
require_once '../app/config/config.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Set up error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Testing Email with MailHog</h1>";

// MailHog Configuration Instructions
echo "<h2>MailHog Configuration</h2>";
echo "<p>MailHog is a great tool for testing emails locally. It captures all outgoing emails and displays them in a web interface.</p>";
echo "<ol>";
echo "<li>Download and install <a href='https://github.com/mailhog/MailHog' target='_blank'>MailHog</a> for your system</li>";
echo "<li>Start MailHog (it runs on port 1025 by default)</li>";
echo "<li>Access the MailHog web interface at <a href='http://localhost:8025' target='_blank'>http://localhost:8025</a></li>";
echo "</ol>";

// Test Form
echo "<h2>Send Test Email to MailHog</h2>";
echo "<form method='post'>";
echo "<p><label>To Email:</label><br><input type='email' name='to_email' required></p>";
echo "<p><label>Subject:</label><br><input type='text' name='subject' value='Test Email via MailHog' required></p>";
echo "<p><label>Message:</label><br><textarea name='message' rows='5' cols='50' required>This is a test email sent from PHPMailer to MailHog.</textarea></p>";
echo "<p><input type='submit' name='send' value='Send Test Email'></p>";
echo "</form>";

// Process form submission
if (isset($_POST['send'])) {
    $to = $_POST['to_email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    try {
        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);
        
        // Configure MailHog settings
        $mail->isSMTP();
        $mail->Host = 'localhost';  // MailHog default host
        $mail->Port = 1025;         // MailHog default SMTP port
        $mail->SMTPAuth = false;    // MailHog doesn't need authentication
        $mail->SMTPSecure = false;  // MailHog doesn't need encryption
        
        // Debug mode
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = function($str, $level) {
            echo "<pre style='color: #0000AA;'>$str</pre>";
        };
        
        // Set sender and recipient
        $mail->setFrom(EMAIL_FROM, EMAIL_FROM_NAME);
        $mail->addAddress($to);
        
        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->AltBody = strip_tags($message);
        
        // Send the email
        echo "<h3>Attempting to send email to MailHog...</h3>";
        if ($mail->send()) {
            echo "<p style='color: green; font-weight: bold;'>Email sent successfully to MailHog!</p>";
            echo "<p>Check <a href='http://localhost:8025' target='_blank'>MailHog Web Interface</a> to view the email.</p>";
        } else {
            echo "<p style='color: red; font-weight: bold;'>Email could not be sent.</p>";
            echo "<p>Error: " . $mail->ErrorInfo . "</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red; font-weight: bold;'>Email could not be sent.</p>";
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
    
    echo "<hr>";
    echo "<h3>MailHog Setup Help:</h3>";
    echo "<ul>";
    echo "<li>MailHog is available at <a href='https://github.com/mailhog/MailHog/releases' target='_blank'>https://github.com/mailhog/MailHog/releases</a></li>";
    echo "<li>For Windows, just download the .exe file, run it, and it will start capturing emails</li>";
    echo "<li>For Mac, you can install it via Homebrew: <code>brew install mailhog</code> and then run <code>mailhog</code></li>";
    echo "<li>For Linux, download the appropriate binary or use Docker</li>";
    echo "<li>MailHog web interface runs on port 8025 by default: <a href='http://localhost:8025' target='_blank'>http://localhost:8025</a></li>";
    echo "</ul>";
}
?> 