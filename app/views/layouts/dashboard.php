<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($data['title']) ? $data['title'] . ' - ' . SITE_NAME : SITE_NAME . ' Dashboard'; ?></title>

    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/css/style.css">

    <!-- Dashboard specific CSS -->
    <style>
        /* Root Variables */
        :root {
            --primary: #0d6efd;
            --light: #F9F9F9;
            --grey: #eee;
            --dark-grey: #AAAAAA;
            --dark: #342E37;
            --blue: #3C91E6;
            --light-blue: #CFE8FF;
            --yellow: #FFCE26;
            --light-yellow: #FFF2C6;
            --red: #DB504A;
        }

        /* Dashboard Layout Styles */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
            background: var(--grey);
        }

        .dashboard-content {
            flex: 1;
            padding: 20px;
            overflow: auto;
        }

        /* Breadcrumb Styles */
        .head-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .head-title .left h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--dark);
        }

        .head-title .breadcrumb {
            display: flex;
            align-items: center;
            grid-gap: 10px;
            padding: 0;
            margin: 0;
            background: transparent;
        }

        .head-title .breadcrumb li {
            font-size: 14px;
            color: var(--dark-grey);
        }

        .head-title .breadcrumb li a {
            color: var(--dark-grey);
            text-decoration: none;
        }

        .head-title .breadcrumb li a:hover,
        .head-title .breadcrumb li a.active {
            color: var(--primary);
        }

        .head-title .btn-download {
            display: flex;
            align-items: center;
            padding: 8px 16px;
            background: var(--primary);
            color: white;
            border-radius: 5px;
            border: none;
            outline: none;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .head-title .btn-download:hover {
            background: #0b5ed7;
        }

        .head-title .btn-download i {
            margin-right: 8px;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Include sidebar -->
        <?php include APP_PATH . '/views/dashboard/sidebar.php'; ?>

        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <!-- Load the content passed from the controller -->
            <?php echo $content; ?>
        </div>
    </div>

    <!-- Scripts -->
    <script src="<?php echo URL_ROOT; ?>/public/js/dashboard.js"></script>
</body>

</html>