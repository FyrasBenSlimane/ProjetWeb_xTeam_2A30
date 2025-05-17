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

echo "<h1>Testing Email Functionality</h1>";

// XAMPP Mail Configuration Instructions
echo "<h2>XAMPP Mail Server Configuration</h2>";
echo "<p>Before testing, make sure you've configured XAMPP's mail server:</p>";
echo "<ol>";
echo "<li>Open XAMPP Control Panel</li>";
echo "<li>Click on 'Config' button for Apache</li>";
echo "<li>Select 'php.ini' from the dropdown menu</li>";
echo "<li>Find the [mail function] section and ensure it's configured like this:
<pre>
[mail function]
SMTP=localhost
smtp_port=25
sendmail_from=your_email@example.com
</pre>
</li>";
echo "<li>Save the file and restart Apache</li>";
echo "</ol>";

// Test Form
echo "<h2>Send Test Email</h2>";
echo "<form method='post'>";
echo "<p><label>To Email:</label><br><input type='email' name='to_email' required></p>";
echo "<p><label>Subject:</label><br><input type='text' name='subject' value='Test Email from XAMPP' required></p>";
echo "<p><label>Message:</label><br><textarea name='message' rows='5' cols='50' required>This is a test email sent from PHPMailer with XAMPP.</textarea></p>";
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
        
        // Configure basic settings
        $mail->isSMTP();                                      // Send using SMTP
        $mail->Host       = SMTP_HOST;                        // SMTP server
        $mail->SMTPAuth   = true;                             // Enable SMTP authentication
        $mail->Username   = SMTP_USER;                        // SMTP username
        $mail->Password   = SMTP_PASS;                        // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // Enable TLS encryption
        $mail->Port       = SMTP_PORT;                        // TCP port to connect to
        
        // XAMPP Debug mode - more verbose output
        $mail->SMTPDebug = 2;                                 // Enable verbose debug output
        
        // Output debug info to the page
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
        echo "<h3>Attempting to send email...</h3>";
        if ($mail->send()) {
            echo "<p style='color: green; font-weight: bold;'>Email sent successfully!</p>";
        } else {
            echo "<p style='color: red; font-weight: bold;'>Email could not be sent.</p>";
            echo "<p>Error: " . $mail->ErrorInfo . "</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red; font-weight: bold;'>Email could not be sent.</p>";
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
    
    echo "<hr>";
    echo "<h3>Troubleshooting Tips:</h3>";
    echo "<ul>";
    echo "<li>If you're using XAMPP without a configured mail server, emails won't actually be sent but the script should process without errors.</li>";
    echo "<li>For actual email sending, consider using a real SMTP service like Gmail, or software like Mailhog for testing.</li>";
    echo "<li>For Gmail, update your config.php settings to use gmail's SMTP server (smtp.gmail.com), port 587, and a valid Gmail account with an 'App Password'.</li>";
    echo "<li>Another option is to install a mail capture tool like <a href='https://github.com/mailhog/MailHog' target='_blank'>MailHog</a> for local testing.</li>";
    echo "</ul>";
}
?> 