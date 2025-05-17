<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration Update</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { background-color: #e74c3c; color: #ffffff; padding: 20px; text-align: center; border-top-left-radius: 8px; border-top-right-radius: 8px; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 20px; }
        .content p { margin-bottom: 15px; }
        .event-details { background-color: #f9f9f9; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .event-details strong { color: #2c3e50; }
        .footer { text-align: center; padding: 15px; font-size: 0.9em; color: #777; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; background-color: #f4f4f4; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Registration Update</h1>
        </div>
        <div class="content">
            <p>Dear <?php echo htmlspecialchars($userName); ?>,</p>
            <p>We regret to inform you that your registration for the following event has been <strong>declined</strong>:</p>
            <div class="event-details">
                <p><strong>Event:</strong> <?php echo htmlspecialchars($eventTitle); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($eventDate); ?></p>
            </div>
            <p>This may be due to capacity limits or other criteria. If you believe this is an error or have any questions, please contact us.</p>
            <p>We appreciate your interest and hope to see you at future events.</p>
            <p>Best regards,<br>The <?php echo SITE_NAME; ?> Team</p>
        </div>
        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 