<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($data['title']) ? $data['title'] . ' | ' . SITE_NAME : SITE_NAME; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="<?php echo URL_ROOT; ?>/public/images/favicon.ico" type="image/x-icon">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    
    <!-- Root CSS Variables -->
    <style>
        :root {
            /* Primary color scheme */
            --primary: #2c3e50;
            --primary-light: #34495e;
            --primary-dark: #1a252f;
            --primary-accent: #ecf0f1;
            --secondary: #222325;
            --secondary-light: #404145;
            --secondary-dark: #0e0e10;
            --secondary-accent: #f1f1f2;
            
            /* Common colors */
            --white: #ffffff;
            --black: #000000;
            --success: #28a745;
            --info: #17a2b8;
            --warning: #ffc107;
            --danger: #dc3545;
            
            /* Typography */
            --font-primary: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            --font-size-xs: 0.75rem;  /* 12px */
            --font-size-sm: 0.875rem; /* 14px */
            --font-size-md: 1rem;     /* 16px */
            --font-size-lg: 1.125rem; /* 18px */
            --font-size-xl: 1.25rem;  /* 20px */
            
            /* Spacing */
            --spacing-xs: 0.25rem;  /* 4px */
            --spacing-sm: 0.5rem;   /* 8px */
            --spacing-md: 1rem;     /* 16px */
            --spacing-lg: 1.5rem;   /* 24px */
            --spacing-xl: 2rem;     /* 32px */
            --spacing-2xl: 3rem;    /* 48px */
            
            /* Border radius */
            --border-radius-sm: 4px;
            --border-radius-md: 8px; 
            --border-radius-lg: 12px;
            
            /* Shadows */
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.1), 0 1px 3px rgba(0,0,0,0.08);
            --shadow-lg: 0 10px 20px rgba(0,0,0,0.15), 0 3px 6px rgba(0,0,0,0.1);
        }

        body {
            font-family: var(--font-primary);
            color: var(--secondary);
            line-height: 1.5;
        }
        
        /* Animation utilities */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }
        
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
        
        .reveal-left {
            opacity: 0;
            transform: translateX(-30px);
            transition: all 0.8s ease;
        }
        
        .reveal-left.active {
            opacity: 1;
            transform: translateX(0);
        }
        
        .reveal-right {
            opacity: 0;
            transform: translateX(30px);
            transition: all 0.8s ease;
        }
        
        .reveal-right.active {
            opacity: 1;
            transform: translateX(0);
        }
        
        .reveal-scale {
            opacity: 0;
            transform: scale(0.9);
            transition: all 0.8s ease;
        }
        
        .reveal-scale.active {
            opacity: 1;
            transform: scale(1);
        }
    </style>
    
    <!-- Page-specific CSS -->
    <?php if(isset($data['css'])) : ?>
        <?php foreach($data['css'] as $css) : ?>
            <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/css/<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Global Modal Styles -->
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/css/components/modal.css">
    
    <!-- Social Authentication Scripts -->
    <script src="<?php echo URL_ROOT; ?>/public/js/github-auth.js"></script>
    <script src="<?php echo URL_ROOT; ?>/public/js/google-auth.js"></script>
</head>
<body<?php echo isset($bodyClass) ? ' class="'.$bodyClass.'"' : ''; ?>>
    <?php require APPROOT . '/views/layouts/navbar.php'; ?>
    
    <main class="container-fluid p-0">
</body>
</html>