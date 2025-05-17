<?php
/**
 * Email Helper
 * A wrapper class for PHPMailer and our custom mail.php configuration
 */

// Include the direct mail.php configuration
require_once APP_ROOT . '/config/mail.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailHelper {
    private $useCustomMailConfig;
    private $mail;
    
    /**
     * Initialize email sending configuration
     * 
     * @param bool $useMailHog Whether to use MailHog for local development
     * @param bool $useCustomMailConfig Whether to use the custom mail.php (true) or PHPMailer config (false)
     */
    public function __construct($useMailHog = false, $useCustomMailConfig = true) {
        $this->useCustomMailConfig = $useCustomMailConfig;
        
        if (!$useCustomMailConfig) {
            // Create a new PHPMailer instance
            $this->mail = new PHPMailer(true);
            
            // Check if using MailHog (for local development)
            if ($useMailHog) {
                $this->setupMailHog();
            } else {
                $this->setupRegularSmtp();
            }
            
            // Set default charset and encoding
            $this->mail->CharSet = 'UTF-8';
            $this->mail->Encoding = 'base64';
        }
    }
    
    /**
     * Configure PHPMailer for MailHog
     */
    private function setupMailHog() {
        $this->mail->isSMTP();
        $this->mail->Host = 'localhost';        // MailHog default host
        $this->mail->Port = 1025;               // MailHog default SMTP port
        $this->mail->SMTPAuth = false;          // MailHog doesn't need authentication
        $this->mail->SMTPSecure = false;        // MailHog doesn't need encryption
        
        // Set default sender
        $this->mail->setFrom(EMAIL_FROM, EMAIL_FROM_NAME);
    }
    
    /**
     * Configure PHPMailer for regular SMTP
     */
    private function setupRegularSmtp() {
        // Configure basic settings
        $this->mail->isSMTP();                                      // Send using SMTP
        $this->mail->Host       = SMTP_HOST;                        // SMTP server
        $this->mail->SMTPAuth   = true;                             // Enable SMTP authentication
        $this->mail->Username   = SMTP_USER;                        // SMTP username
        $this->mail->Password   = SMTP_PASS;                        // SMTP password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // Enable TLS encryption
        $this->mail->Port       = SMTP_PORT;                        // TCP port to connect to
        
        // Set default sender
        $this->mail->setFrom(EMAIL_FROM, EMAIL_FROM_NAME);
    }
    
    /**
     * Send a welcome email to a newly registered user
     * 
     * @param string $name User's name
     * @param string $email User's email address
     * @param string $verificationLink Optional verification link
     * @return bool True if sent successfully, false otherwise
     */
    public function sendWelcomeEmail($name, $email, $verificationLink = '') {
        try {
            if ($this->useCustomMailConfig) {
                // Use custom mail.php configuration
                $subject = 'Welcome to ' . SITE_NAME;
                
                // HTML message
                $message = '
                    <html>
                    <head>
                        <style>
                            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                            .header { background-color: #f5f5f5; padding: 15px; text-align: center; }
                            .content { padding: 20px; }
                            .button { display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; }
                            .footer { margin-top: 20px; text-align: center; font-size: 12px; color: #777; }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <div class="header">
                                <h1>Welcome to ' . SITE_NAME . '!</h1>
                            </div>
                            <div class="content">
                                <p>Hello ' . $name . ',</p>
                                <p>Thank you for registering with ' . SITE_NAME . '. We\'re excited to have you on board!</p>';
                
                // Add verification link if provided
                if ($verificationLink) {
                    $message .= '
                                <p>Please verify your email address by clicking the button below:</p>
                                <p><a href="' . $verificationLink . '" class="button">Verify Email</a></p>
                                <p>If the button doesn\'t work, you can copy and paste the following link into your browser:</p>
                                <p>' . $verificationLink . '</p>';
                }
                
                $message .= '
                                <p>If you have any questions, please don\'t hesitate to contact us.</p>
                                <p>Regards,<br>The ' . SITE_NAME . ' Team</p>
                            </div>
                            <div class="footer">
                                <p>This is an automated message, please do not reply directly to this email.</p>
                            </div>
                        </div>
                    </body>
                    </html>';
                
                // Use the sendEmail function from mail.php
                return sendEmail($email, $subject, $message);
            } else {
                // Set recipient
                $this->mail->clearAddresses();
                $this->mail->addAddress($email, $name);
                
                // Email content
                $this->mail->isHTML(true);
                $this->mail->Subject = 'Welcome to ' . SITE_NAME;
                
                // HTML message
                $message = '
                    <html>
                    <head>
                        <style>
                            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                            .header { background-color: #f5f5f5; padding: 15px; text-align: center; }
                            .content { padding: 20px; }
                            .button { display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; }
                            .footer { margin-top: 20px; text-align: center; font-size: 12px; color: #777; }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <div class="header">
                                <h1>Welcome to ' . SITE_NAME . '!</h1>
                            </div>
                            <div class="content">
                                <p>Hello ' . $name . ',</p>
                                <p>Thank you for registering with ' . SITE_NAME . '. We\'re excited to have you on board!</p>';
                
                // Add verification link if provided
                if ($verificationLink) {
                    $message .= '
                                <p>Please verify your email address by clicking the button below:</p>
                                <p><a href="' . $verificationLink . '" class="button">Verify Email</a></p>
                                <p>If the button doesn\'t work, you can copy and paste the following link into your browser:</p>
                                <p>' . $verificationLink . '</p>';
                }
                
                $message .= '
                                <p>If you have any questions, please don\'t hesitate to contact us.</p>
                                <p>Regards,<br>The ' . SITE_NAME . ' Team</p>
                            </div>
                            <div class="footer">
                                <p>This is an automated message, please do not reply directly to this email.</p>
                            </div>
                        </div>
                    </body>
                    </html>';
                
                $this->mail->Body = $message;
                $this->mail->AltBody = strip_tags(str_replace(['<br>', '</p>'], ["\n", "\n\n"], $message));
                
                // Send the email
                return $this->mail->send();
            }
        } catch (Exception $e) {
            error_log('Error sending welcome email: ' . ($this->useCustomMailConfig ? $e->getMessage() : $this->mail->ErrorInfo));
            return false;
        }
    }
    
    /**
     * Send login notification email to user
     * 
     * @param string $name User's name
     * @param string $email User's email address
     * @param string $loginTime Time of login (formatted)
     * @param string $ipAddress IP address of login (optional)
     * @return bool True if sent successfully, false otherwise
     */
    public function sendLoginNotification($name, $email, $loginTime, $ipAddress = '') {
        try {
            if ($this->useCustomMailConfig) {
                // Use custom mail.php configuration
                $subject = 'Login Notification - ' . SITE_NAME;
                
                // HTML message
                $message = '
                    <html>
                    <head>
                        <style>
                            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                            .header { background-color: #f5f5f5; padding: 15px; text-align: center; }
                            .content { padding: 20px; }
                            .alert { background-color: #f8f9fa; border-left: 4px solid #4CAF50; padding: 15px; margin-bottom: 20px; }
                            .footer { margin-top: 20px; text-align: center; font-size: 12px; color: #777; }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <div class="header">
                                <h1>Login Notification</h1>
                            </div>
                            <div class="content">
                                <p>Hello ' . $name . ',</p>
                                <p>We\'re sending this email to notify you of a recent login to your ' . SITE_NAME . ' account.</p>
                                
                                <div class="alert">
                                    <p><strong>Login Details:</strong></p>
                                    <p>Time: ' . $loginTime . '</p>';
                
                // Add IP address if provided
                if ($ipAddress) {
                    $message .= '<p>IP Address: ' . $ipAddress . '</p>';
                }
                
                $message .= '
                                </div>
                                
                                <p>If this was you, no further action is required.</p>
                                <p>If you did not log in at this time, please secure your account by changing your password immediately and contact our support team.</p>
                                
                                <p>Regards,<br>The ' . SITE_NAME . ' Team</p>
                            </div>
                            <div class="footer">
                                <p>This is an automated message, please do not reply directly to this email.</p>
                            </div>
                        </div>
                    </body>
                    </html>';
                
                // Use the sendEmail function from mail.php
                return sendEmail($email, $subject, $message);
            } else {
                // Set recipient
                $this->mail->clearAddresses();
                $this->mail->addAddress($email, $name);
                
                // Email content
                $this->mail->isHTML(true);
                $this->mail->Subject = 'Login Notification - ' . SITE_NAME;
                
                // HTML message
                $message = '
                    <html>
                    <head>
                        <style>
                            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                            .header { background-color: #f5f5f5; padding: 15px; text-align: center; }
                            .content { padding: 20px; }
                            .alert { background-color: #f8f9fa; border-left: 4px solid #4CAF50; padding: 15px; margin-bottom: 20px; }
                            .footer { margin-top: 20px; text-align: center; font-size: 12px; color: #777; }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <div class="header">
                                <h1>Login Notification</h1>
                            </div>
                            <div class="content">
                                <p>Hello ' . $name . ',</p>
                                <p>We\'re sending this email to notify you of a recent login to your ' . SITE_NAME . ' account.</p>
                                
                                <div class="alert">
                                    <p><strong>Login Details:</strong></p>
                                    <p>Time: ' . $loginTime . '</p>';
                
                // Add IP address if provided
                if ($ipAddress) {
                    $message .= '<p>IP Address: ' . $ipAddress . '</p>';
                }
                
                $message .= '
                                </div>
                                
                                <p>If this was you, no further action is required.</p>
                                <p>If you did not log in at this time, please secure your account by changing your password immediately and contact our support team.</p>
                                
                                <p>Regards,<br>The ' . SITE_NAME . ' Team</p>
                            </div>
                            <div class="footer">
                                <p>This is an automated message, please do not reply directly to this email.</p>
                            </div>
                        </div>
                    </body>
                    </html>';
                
                $this->mail->Body = $message;
                $this->mail->AltBody = strip_tags(str_replace(['<br>', '</p>'], ["\n", "\n\n"], $message));
                
                // Send the email
                return $this->mail->send();
            }
        } catch (Exception $e) {
            error_log('Error sending login notification: ' . ($this->useCustomMailConfig ? $e->getMessage() : $this->mail->ErrorInfo));
            return false;
        }
    }
    
    /**
     * Send a generic email
     * 
     * @param string $to Recipient email
     * @param string $toName Recipient name
     * @param string $subject Email subject
     * @param string $body Email body (HTML)
     * @param string $altBody Plain text alternative
     * @return bool True if sent successfully, false otherwise
     */
    public function sendEmail($to, $toName, $subject, $body, $altBody = '') {
        try {
            if ($this->useCustomMailConfig) {
                // Use the sendEmail function from mail.php
                return sendEmail($to, $subject, $body);
            } else {
                // Set recipient
                $this->mail->clearAddresses();
                $this->mail->addAddress($to, $toName);
                
                // Email content
                $this->mail->isHTML(true);
                $this->mail->Subject = $subject;
                $this->mail->Body = $body;
                $this->mail->AltBody = $altBody ? $altBody : strip_tags(str_replace(['<br>', '</p>'], ["\n", "\n\n"], $body));
                
                // Send the email
                return $this->mail->send();
            }
        } catch (Exception $e) {
            error_log('Error sending email: ' . ($this->useCustomMailConfig ? $e->getMessage() : $this->mail->ErrorInfo));
            return false;
        }
    }
} 