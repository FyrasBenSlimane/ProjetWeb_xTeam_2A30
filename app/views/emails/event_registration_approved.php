<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration Approved</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { background-color: #2c3e50; color: #ffffff; padding: 20px; text-align: center; border-top-left-radius: 8px; border-top-right-radius: 8px; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 20px; }
        .content p { margin-bottom: 15px; }
        .event-details { background-color: #f9f9f9; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .event-details strong { color: #2c3e50; }
        .footer { text-align: center; padding: 15px; font-size: 0.9em; color: #777; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; background-color: #f4f4f4; }
        .button { display: inline-block; background-color: #3498db; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .button:hover { background-color: #2980b9; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Registration Approved!</h1>
        </div>
        <div class="content">
            <p>Dear <?php echo htmlspecialchars($userName); ?>,</p>
            <p>We are pleased to inform you that your registration for the following event has been <strong>approved</strong>:</p>
            <div class="event-details">
                <p><strong>Event:</strong> <?php echo htmlspecialchars($eventTitle); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($eventDate); ?></p>
            </div>
            <p>We look forward to seeing you there!</p>
            <?php if (!empty($eventVirtualLink) && $isVirtual): ?>
                <p>This is a virtual event. You can join using the link: <a href="<?php echo htmlspecialchars($eventVirtualLink); ?>" class="button">Join Event</a></p>
            <?php elseif (!empty($eventLocation) && !$isVirtual): ?>
                 <p><strong>Location:</strong> <?php echo htmlspecialchars($eventLocation); ?></p>
            <?php endif; ?>
            <p>If you have any questions, please don't hesitate to contact us.</p>
            <p>Best regards,<br>The <?php echo SITE_NAME; ?> Team</p>
        </div>
        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 