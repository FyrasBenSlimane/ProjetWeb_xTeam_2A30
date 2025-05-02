<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - <?php echo $data['title']; ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/css/style.css">
    
    <?php 
    // Include support.css only for support-related pages
    $current_url = $_SERVER['REQUEST_URI'];
    if (strpos($current_url, '/support') !== false): 
    ?>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/css/support.css">
    <?php endif; ?>

    <!-- Root CSS Variables and Core Styles -->
    <style>
        /* Reset box sizing */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        /* Color variables - Modern refined palette */
        :root {
            /* Base colors */
            --white: #ffffff;
            --black: #0c0c0d;
            --transparent: transparent;

            /* Primary colors - Changed to darker blue scheme (almost black with blue tint) */
            --primary-color: #0a1128;
            /* Very dark blue/almost black */
            --primary-light: #1c2541;
            /* Dark blue */
            --primary-lighter: #e6e9f0;
            /* Very light blue-gray background */
            --primary-dark: #050914;
            /* Nearly black with blue undertone */

            /* Secondary colors - Changed to complement dark blue-black */
            --secondary-color: #121a29;
            /* Dark navy/near black */
            --secondary-light: #1d2b3f;
            /* Dark blue-gray */
            --secondary-lighter: #eaecf1;
            /* Very light gray with blue tint */
            --secondary-dark: #0a0f17;
            /* Nearly black with navy tint */

            /* Gray shades - more refined, softer grays with blue tint */
            --gray-50: #f6f8fa;
            --gray-100: #eef1f5;
            --gray-200: #dde3eb;
            --gray-300: #c4cad6;
            --gray-400: #9aa2b3;
            --gray-500: #717d96;
            --gray-600: #4a5568;
            --gray-700: #323b4e;
            --gray-800: #1e2433;
            --gray-900: #0f1524;

            /* Use semantic naming for grays */
            --gray-lightest: var(--gray-50);
            --gray-lighter: var(--gray-100);
            --gray-light: var(--gray-200);
            --gray-medium: var(--gray-400);
            --gray-dark: var(--gray-600);
            --gray-darker: var(--gray-700);
            --gray-darkest: var(--gray-900);

            /* Background and text colors */
            --background-color: var(--white);
            --text-color: var(--gray-800);

            /* Accent colors - Modified to complement darker blue */
            --accent-purple: #273469;
            /* Dark indigo */
            --accent-purple-light: #bbc2d8;
            --accent-purple-lighter: #ebedf5;

            --accent-pink: #1f2b50;
            /* Deep blue */
            --accent-pink-light: #c0c6db;
            --accent-pink-lighter: #e7eaf2;

            --accent-orange: #0d315b;
            /* Darker blue */
            --accent-orange-light: #b5c5d9;
            --accent-orange-lighter: #e8eef5;

            --accent-red: #F04438;
            /* Keeping red for alerts/errors */
            --accent-red-light: #FDA29B;
            --accent-red-lighter: #FEF3F2;

            --accent-green: #0a558c;
            /* Blue-tinted success */
            --accent-green-light: #b3d6e7;
            --accent-green-lighter: #e3f1f8;

            /* Semantic colors */
            --success: var(--accent-green);
            --warning: var(--accent-orange);
            --danger: var(--accent-red);
            --info: var(--primary-color);

            /* Fonts - maintaining the same fonts */
            --font-primary: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            --font-display: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            --font-secondary: var(--font-primary);

            /* Background gradients and color overlays */
            --gradient-primary: linear-gradient(135deg, #0a1128, #131e3a);
            --gradient-secondary: linear-gradient(135deg, #121a29, #0a0f17);
            --gradient-accent: linear-gradient(135deg, #273469, #0d315b);

            /* Transparent colors for UI elements */
            --primary-light-transparent: rgba(28, 37, 65, 0.1);
            --secondary-light-transparent: rgba(18, 26, 41, 0.1);
            --warning-light-transparent: rgba(13, 49, 91, 0.1);
            --danger-light-transparent: rgba(240, 68, 56, 0.1);
            --purple-light-transparent: rgba(39, 52, 105, 0.1);
            --teal-light-transparent: rgba(10, 85, 140, 0.1);

            /* Standardized spacing variables */
            --spacing-xs: 0.25rem;
            /* 4px */
            --spacing-sm: 0.5rem;
            /* 8px */
            --spacing-md: 1rem;
            /* 16px */
            --spacing-lg: 1.5rem;
            /* 24px */
            --spacing-xl: 2rem;
            /* 32px */
            --spacing-2xl: 2.5rem;
            /* 40px */
            --spacing-3xl: 3rem;
            /* 48px */
            --spacing-4xl: 4rem;
            /* 64px */
            --spacing-5xl: 5rem;
            /* 80px */

            /* Border radius - more refined */
            --border-radius-sm: 4px;
            --border-radius-md: 8px;
            --border-radius-lg: 12px;
            --border-radius-xl: 16px;
            --border-radius-2xl: 24px;
            --border-radius-pill: 50px;

            /* Typography - Font Sizes - more refined scale */
            --font-size-xs: 0.75rem;
            /* 12px */
            --font-size-sm: 0.875rem;
            /* 14px */
            --font-size-base: 1rem;
            /* 16px */
            --font-size-lg: 1.125rem;
            /* 18px */
            --font-size-xl: 1.25rem;
            /* 20px */
            --font-size-2xl: 1.5rem;
            /* 24px */
            --font-size-3xl: 1.875rem;
            /* 30px */
            --font-size-4xl: 2.25rem;
            /* 36px */
            --font-size-5xl: 3rem;
            /* 48px */
            --font-size-6xl: 3.75rem;
            /* 60px */
            --font-size-7xl: 4.5rem;
            /* 72px */

            /* Line Heights */
            --line-height-tight: 1.25;
            --line-height-snug: 1.375;
            --line-height-normal: 1.5;
            --line-height-relaxed: 1.625;
            --line-height-loose: 2;

            /* Font Weights */
            --font-weight-light: 300;
            --font-weight-normal: 400;
            --font-weight-medium: 500;
            --font-weight-semibold: 600;
            --font-weight-bold: 700;

            /* Applied Font Weights */
            --font-weight-base: var(--font-weight-normal);
            --font-weight-body: var(--font-weight-normal);
            --font-weight-strong: var(--font-weight-medium);
            --font-weight-heading: var(--font-weight-semibold);

            /* Letter Spacing */
            --letter-spacing-tighter: -0.05em;
            --letter-spacing-tight: -0.025em;
            --letter-spacing-normal: 0em;
            --letter-spacing-wide: 0.025em;
            --letter-spacing-wider: 0.05em;
            --letter-spacing-widest: 0.1em;

            /* Shadow tokens - refined shadow system */
            --shadow-xs: 0px 1px 2px rgba(16, 24, 40, 0.05);
            --shadow-sm: 0px 1px 3px rgba(16, 24, 40, 0.1), 0px 1px 2px rgba(16, 24, 40, 0.06);
            --shadow-md: 0px 4px 8px -2px rgba(16, 24, 40, 0.1), 0px 2px 4px -2px rgba(16, 24, 40, 0.06);
            --shadow-lg: 0px 12px 16px -4px rgba(16, 24, 40, 0.08), 0px 4px 6px -2px rgba(16, 24, 40, 0.03);
            --shadow-xl: 0px 20px 24px -4px rgba(16, 24, 40, 0.08), 0px 8px 8px -4px rgba(16, 24, 40, 0.03);
            --shadow-2xl: 0px 24px 48px -12px rgba(16, 24, 40, 0.18);
            --shadow-3xl: 0px 32px 64px -12px rgba(16, 24, 40, 0.14);

            /* Container */
            --container-width: 1280px;
            --container-padding-x: 1rem;
            /* 16px default padding */
            --container-padding-x-sm: 1.5rem;
            /* 24px for small screens */
            --container-padding-x-lg: 2rem;
            /* 32px for large screens */

            /* Z-index scale */
            --z-negative: -1;
            --z-elevate: 1;
            --z-dropdown: 1000;
            --z-sticky: 1020;
            --z-fixed: 1030;
            --z-modal-backdrop: 1040;
            --z-modal: 1050;
            --z-popover: 1060;
            --z-tooltip: 1070;

            /* Animation durations */
            --duration-instant: 0s;
            --duration-fast: 0.15s;
            --duration-normal: 0.3s;
            --duration-slow: 0.5s;
            --duration-slower: 0.8s;

            /* Animation easings */
            --ease-in: cubic-bezier(0.4, 0, 1, 1);
            --ease-out: cubic-bezier(0, 0, 0.2, 1);
            --ease-in-out: cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Base Typography */
        html {
            font-size: 16px;
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--font-primary);
            font-size: var(--font-size-base);
            line-height: var(--line-height-normal);
            background-color: var(--background-color);
            color: var(--text-color);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            padding-top: 70px;
            /* Height of fixed navbar */
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Typography styles */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin-top: 0;
            font-family: var(--font-display);
            font-weight: var(--font-weight-heading);
            color: var(--gray-900);
            letter-spacing: var(--letter-spacing-tight);
            line-height: var(--line-height-tight);
        }

        h1 {
            font-size: var(--font-size-4xl);
            margin-bottom: var(--spacing-lg);
        }

        h2 {
            font-size: var(--font-size-3xl);
            margin-bottom: var(--spacing-md);
        }

        h3 {
            font-size: var(--font-size-2xl);
            margin-bottom: var(--spacing-md);
        }

        h4 {
            font-size: var(--font-size-xl);
            margin-bottom: var(--spacing-sm);
        }

        h5 {
            font-size: var(--font-size-lg);
            margin-bottom: var(--spacing-sm);
        }

        h6 {
            font-size: var(--font-size-base);
            margin-bottom: var(--spacing-sm);
        }

        p {
            margin-top: 0;
            margin-bottom: var(--spacing-md);
            line-height: var(--line-height-relaxed);
        }

        @media (min-width: 768px) {
            h1 {
                font-size: var(--font-size-5xl);
            }

            h2 {
                font-size: var(--font-size-4xl);
            }

            h3 {
                font-size: var(--font-size-3xl);
            }

            h4 {
                font-size: var(--font-size-2xl);
            }
        }

        @media (min-width: 1024px) {
            h1 {
                font-size: var(--font-size-6xl);
            }
        }

        /* General Element Styles - Refined for better accessibility and aesthetics */
        a {
            color: var(--primary-color);
            text-decoration: none;
            transition: all var(--duration-normal) var(--ease-in-out);
            position: relative;
        }

        a:hover {
            color: var(--primary-dark);
        }

        /* Underline animation for text links */
        a.text-link {
            position: relative;
            display: inline-block;
        }

        a.text-link::after {
            content: '';
            position: absolute;
            width: 100%;
            transform: scaleX(0);
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: currentColor;
            transform-origin: bottom right;
            transition: transform 0.3s var(--ease-out);
        }

        a.text-link:hover::after {
            transform: scaleX(1);
            transform-origin: bottom left;
        }

        /* Images with soft scaling */
        img {
            max-width: 100%;
            height: auto;
            transition: transform var(--duration-normal) var(--ease-out);
        }

        .img-hover-zoom {
            overflow: hidden;
            border-radius: var(--border-radius-md);
        }

        .img-hover-zoom img {
            transition: transform var(--duration-normal) var(--ease-out);
        }

        .img-hover-zoom:hover img {
            transform: scale(1.05);
        }

        /* Lists */
        ul,
        ol {
            padding-left: var(--spacing-xl);
            margin-bottom: var(--spacing-md);
        }

        li {
            margin-bottom: var(--spacing-xs);
        }

        /* Horizontal rule */
        hr {
            border: 0;
            height: 1px;
            background-color: var(--gray-200);
            margin: var(--spacing-xl) 0;
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gray-400);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--gray-500);
        }

        /* Button styles - More sophisticated with hover effects */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: var(--font-weight-medium);
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            user-select: none;
            border: 2px solid transparent;
            padding: 0.6rem 1.4rem;
            font-size: var(--font-size-base);
            line-height: 1.5;
            border-radius: var(--border-radius-md);
            transition: all var(--duration-normal) var(--ease-in-out);
            position: relative;
            overflow: hidden;
            cursor: pointer;
            font-family: var(--font-primary);
            letter-spacing: 0.01em;
        }

        .btn::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: -100%;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.2) 50%, rgba(255, 255, 255, 0) 100%);
            transition: left 0.7s var(--ease-in-out);
            z-index: 1;
            pointer-events: none;
        }

        .btn:hover::after {
            left: 100%;
        }

        .btn-content {
            position: relative;
            z-index: 2;
        }

        .btn:hover,
        .btn:focus {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .btn:active {
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .btn-lg {
            padding: 0.8rem 1.8rem;
            font-size: var(--font-size-lg);
            border-radius: var(--border-radius-lg);
        }

        .btn-sm {
            padding: 0.4rem 1rem;
            font-size: var(--font-size-sm);
            border-radius: var(--border-radius-sm);
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: var(--white);
            border-color: var(--primary-color);
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            color: var(--white);
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            color: var(--white);
            border-color: var(--secondary-color);
        }

        .btn-secondary:hover,
        .btn-secondary:focus {
            background-color: var(--secondary-dark);
            border-color: var(--secondary-dark);
            color: var(--white);
        }

        .btn-outline-primary {
            background-color: transparent;
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover,
        .btn-outline-primary:focus {
            background-color: var(--primary-color);
            color: var (--white);
        }

        .btn-outline-secondary {
            background-color: transparent;
            color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-outline-secondary:hover,
        .btn-outline-secondary:focus {
            background-color: var(--secondary-color);
            color: var(--white);
        }

        .btn-light {
            background-color: var(--white);
            color: var (--primary-color);
            border-color: var(--gray-200);
        }

        .btn-light:hover,
        .btn-light:focus {
            background-color: var(--gray-50);
            border-color: var(--gray-300);
            color: var(--primary-dark);
        }

        .btn-outline-light {
            background-color: transparent;
            color: var(--white);
            border-color: var(--white);
        }

        .btn-outline-light:hover,
        .btn-outline-light:focus {
            background-color: var(--white);
            color: var(--primary-color);
        }

        .btn-dark {
            background-color: var(--gray-800);
            color: var(--white);
            border-color: var(--gray-800);
        }

        .btn-dark:hover,
        .btn-dark:focus {
            background-color: var(--gray-900);
            border-color: var(--gray-900);
        }

        .btn-outline-dark {
            background-color: transparent;
            color: var(--gray-800);
            border-color: var(--gray-800);
        }

        .btn-outline-dark:hover,
        .btn-outline-dark:focus {
            background-color: var(--gray-800);
            color: var(--white);
        }

        .btn-link {
            background-color: transparent;
            color: var(--primary-color);
            box-shadow: none;
            padding: 0.5rem;
            text-decoration: none;
        }

        .btn-link:hover,
        .btn-link:focus {
            color: var(--primary-dark);
            box-shadow: none;
            transform: none;
            text-decoration: underline;
        }

        .btn-block {
            display: block;
            width: 100%;
        }

        .btn-rounded,
        .rounded-pill {
            border-radius: var(--border-radius-pill);
        }

        /* Icon button styles */
        .btn-icon {
            width: 40px;
            height: 40px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .btn-icon.btn-sm {
            width: 32px;
            height: 32px;
        }

        .btn-icon.btn-lg {
            width: 48px;
            height: 48px;
        }

        /* Container layouts - Modern responsive containers */
        .container {
            width: 100%;
            padding-right: var(--container-padding-x);
            padding-left: var(--container-padding-x);
            margin-right: auto;
            margin-left: auto;
        }

        @media (min-width: 576px) {
            .container {
                max-width: 540px;
                padding-right: var(--container-padding-x-sm);
                padding-left: var(--container-padding-x-sm);
            }
        }

        @media (min-width: 768px) {
            .container {
                max-width: 720px;
            }
        }

        @media (min-width: 992px) {
            .container {
                max-width: 960px;
                padding-right: var(--container-padding-x-lg);
                padding-left: var(--container-padding-x-lg);
            }
        }

        @media (min-width: 1200px) {
            .container {
                max-width: 1140px;
            }
        }

        @media (min-width: 1400px) {
            .container {
                max-width: var(--container-width);
            }
        }

        .container-fluid {
            width: 100%;
            margin-right: auto;
            margin-left: auto;
            padding-right: var(--container-padding-x);
            padding-left: var(--container-padding-x);
        }

        @media (min-width: 768px) {
            .container-fluid {
                padding-right: var(--container-padding-x-sm);
                padding-left: var(--container-padding-x-sm);
            }
        }

        @media (min-width: 992px) {
            .container-fluid {
                padding-right: var(--container-padding-x-lg);
                padding-left: var(--container-padding-x-lg);
            }
        }

        /* Section layouts */
        .section {
            padding: var(--spacing-5xl) 0;
        }

        .section-sm {
            padding: var(--spacing-3xl) 0;
        }

        .section-lg {
            padding: var(--spacing-5xl) 0;
        }

        @media (max-width: 768px) {
            .section {
                padding: var(--spacing-3xl) 0;
            }

            .section-sm {
                padding: var(--spacing-2xl) 0;
            }

            .section-lg {
                padding: var(--spacing-4xl) 0;
            }
        }

        /* Card styles */
        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            transition: all var(--duration-normal) var(--ease-in-out);
            box-shadow: var(--shadow-sm);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .card-body {
            flex: 1 1 auto;
            padding: var(--spacing-lg);
        }

        .card-title {
            margin-bottom: var(--spacing-md);
            font-weight: var(--font-weight-semibold);
        }

        .card-subtitle {
            margin-top: calc(-1 * var(--spacing-xs));
            margin-bottom: var(--spacing-md);
            color: var(--gray-600);
        }

        .card-text:last-child {
            margin-bottom: 0;
        }

        .card-link+.card-link {
            margin-left: var(--spacing-md);
        }

        .card-header {
            padding: var(--spacing-md) var(--spacing-lg);
            margin-bottom: 0;
            background-color: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
        }

        .card-footer {
            padding: var(--spacing-md) var(--spacing-lg);
            background-color: var(--gray-50);
            border-top: 1px solid var(--gray-200);
        }

        /* Form Controls - More beautiful, accessible form elements */
        .form-control {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: var(--font-size-base);
            line-height: 1.5;
            font-family: var(--font-primary);
            color: var(--gray-700);
            background-color: var(--white);
            background-clip: padding-box;
            border: 1px solid var(--gray-300);
            border-radius: var(--border-radius-md);
            transition: all var(--duration-normal) var(--ease-in-out);
        }

        .form-control:focus {
            color: var(--gray-900);
            background-color: var(--white);
            border-color: var(--primary-light);
            outline: 0;
            box-shadow: 0 0 0 4px var(--primary-light-transparent);
        }

        .form-control::placeholder {
            color: var(--gray-500);
            opacity: 0.7;
        }

        .form-control:disabled,
        .form-control[readonly] {
            background-color: var(--gray-100);
            opacity: 0.7;
            cursor: not-allowed;
        }

        .form-label {
            display: inline-block;
            margin-bottom: 0.5rem;
            font-weight: var(--font-weight-medium);
            color: var(--gray-700);
        }

        .form-text {
            display: block;
            margin-top: 0.25rem;
            font-size: var(--font-size-sm);
            color: var(--gray-600);
        }

        .form-group {
            margin-bottom: var(--spacing-md);
        }

        /* Input group */
        .input-group {
            position: relative;
            display: flex;
            flex-wrap: wrap;
            align-items: stretch;
            width: 100%;
        }

        .input-group .form-control {
            position: relative;
            flex: 1 1 auto;
            width: 1%;
            min-width: 0;
            margin-bottom: 0;
        }

        .input-group-text {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            font-size: var(--font-size-base);
            font-weight: var(--font-weight-normal);
            line-height: 1.5;
            color: var(--gray-700);
            text-align: center;
            white-space: nowrap;
            background-color: var(--gray-100);
            border: 1px solid var(--gray-300);
            border-radius: var(--border-radius-md);
        }

        .input-group>.form-control:not(:last-child),
        .input-group>.input-group-text:not(:last-child) {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .input-group>.form-control:not(:first-child),
        .input-group>.input-group-text:not(:first-child) {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        /* Advanced form input with floating labels */
        .form-floating {
            position: relative;
        }

        .form-floating>.form-control {
            height: calc(3.5rem + 2px);
            padding: 1.625rem 1rem 0.625rem;
        }

        .form-floating>label {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            padding: 1rem;
            pointer-events: none;
            border: 1px solid transparent;
            transform-origin: 0 0;
            transition: opacity 0.1s ease-in-out, transform 0.1s ease-in-out;
            color: var(--gray-500);
        }

        .form-floating>.form-control:focus~label,
        .form-floating>.form-control:not(:placeholder-shown)~label {
            opacity: 0.65;
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
        }

        /* Badge styles */
        .badge {
            display: inline-block;
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: var(--font-weight-medium);
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: var(--border-radius-pill);
            transition: all var(--duration-normal) var(--ease-in-out);
        }

        .badge-primary {
            color: var (--white);
            background-color: var(--primary-color);
        }

        .badge-secondary {
            color: var(--white);
            background-color: var(--secondary-color);
        }

        .badge-success {
            color: var(--white);
            background-color: var(--success);
        }

        .badge-danger {
            color: var(--white);
            background-color: var(--danger);
        }

        .badge-warning {
            color: var(--gray-900);
            background-color: var(--warning);
        }

        .badge-info {
            color: var(--white);
            background-color: var(--info);
        }

        .badge-light {
            color: var(--gray-900);
            background-color: var(--gray-100);
        }

        .badge-dark {
            color: var(--white);
            background-color: var(--gray-800);
        }

        /* Soft badges */
        .badge-soft-primary {
            color: var(--primary-color);
            background-color: var(--primary-lighter);
        }

        .badge-soft-secondary {
            color: var(--secondary-color);
            background-color: var(--secondary-lighter);
        }

        .badge-soft-success {
            color: var(--success);
            background-color: var(--accent-green-lighter);
        }

        .badge-soft-danger {
            color: var(--danger);
            background-color: var(--accent-red-lighter);
        }

        .badge-soft-warning {
            color: var(--warning);
            background-color: var(--accent-orange-lighter);
        }

        .badge-soft-info {
            color: var(--info);
            background-color: var(--primary-lighter);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeInDown {
            from {
                transform: translateY(-30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeInLeft {
            from {
                transform: translateX(-30px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeInRight {
            from {
                transform: translateX(30px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes scaleIn {
            from {
                transform: scale(0.95);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0.3);
                opacity: 0;
            }

            40% {
                transform: scale(1.05);
            }

            60% {
                transform: scale(0.95);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes shine {
            from {
                background-position: -200% center;
            }

            to {
                background-position: 200% center;
            }
        }

        /* Animation classes */
        .animate-fade-in {
            opacity: 0;
            animation: fadeIn var(--duration-normal) var(--ease-out) forwards;
        }

        .animate-fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp var(--duration-normal) var(--ease-out) forwards;
        }

        .animate-fade-in-down {
            opacity: 0;
            transform: translateY(-30px);
            animation: fadeInDown var(--duration-normal) var(--ease-out) forwards;
        }

        .animate-fade-in-left {
            opacity: 0;
            transform: translateX(-30px);
            animation: fadeInLeft var(--duration-normal) var(--ease-out) forwards;
        }

        .animate-fade-in-right {
            opacity: 0;
            transform: translateX(30px);
            animation: fadeInRight var(--duration-normal) var (--ease-out) forwards;
        }

        .animate-scale-in {
            opacity: 0;
            transform: scale(0.95);
            animation: scaleIn var(--duration-normal) var(--ease-out) forwards;
        }

        .animate-bounce-in {
            opacity: 0;
            animation: bounceIn var(--duration-slower) cubic-bezier(0.215, 0.610, 0.355, 1.000) forwards;
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        .animate-pulse {
            animation: pulse 2s ease-in-out infinite;
        }

        .animate-shine {
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            background-size: 200% auto;
            animation: shine 2s linear infinite;
        }

        /* Animation delays */
        .delay-100 {
            animation-delay: 0.1s;
        }

        .delay-200 {
            animation-delay: 0.2s;
        }

        .delay-300 {
            animation-delay: 0.3s;
        }

        .delay-400 {
            animation-delay: 0.4s;
        }

        .delay-500 {
            animation-delay: 0.5s;
        }

        .delay-600 {
            animation-delay: 0.6s;
        }

        .delay-700 {
            animation-delay: 0.7s;
        }

        .delay-800 {
            animation-delay: 0.8s;
        }

        .delay-900 {
            animation-delay: 0.9s;
        }

        .delay-1000 {
            animation-delay: 1s;
        }

        /* Utility classes */
        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        .bg-secondary {
            background-color: var(--secondary-color) !important;
        }

        .bg-success {
            background-color: var(--success) !important;
        }

        .bg-danger {
            background-color: var(--danger) !important;
        }

        .bg-warning {
            background-color: var(--warning) !important;
        }

        .bg-info {
            background-color: var(--info) !important;
        }

        .bg-light {
            background-color: var(--gray-100) !important;
        }

        .bg-dark {
            background-color: var(--gray-800) !important;
        }

        .bg-white {
            background-color: var(--white) !important;
        }

        .bg-transparent {
            background-color: transparent !important;
        }

        .bg-primary-light {
            background-color: var(--primary-lighter) !important;
        }

        .bg-secondary-light {
            background-color: var(--secondary-lighter) !important;
        }

        .bg-success-light {
            background-color: var(--accent-green-lighter) !important;
        }

        .bg-danger-light {
            background-color: var(--accent-red-lighter) !important;
        }

        .bg-warning-light {
            background-color: var(--accent-orange-lighter) !important;
        }

        .bg-info-light {
            background-color: var(--primary-lighter) !important;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .text-secondary {
            color: var(--secondary-color) !important;
        }

        .text-success {
            color: var(--success) !important;
        }

        .text-danger {
            color: var(--danger) !important;
        }

        .text-warning {
            color: var(--warning) !important;
        }

        .text-info {
            color: var(--info) !important;
        }

        .text-light {
            color: var(--gray-100) !important;
        }

        .text-dark {
            color: var(--gray-800) !important;
        }

        .text-white {
            color: var(--white) !important;
        }

        .text-muted {
            color: var(--gray-600) !important;
        }

        /* Gradient backgrounds */
        .bg-gradient-primary {
            background: var(--gradient-primary) !important;
        }

        .bg-gradient-secondary {
            background: var(--gradient-secondary) !important;
        }

        .bg-gradient-accent {
            background: var(--gradient-accent) !important;
        }

        /* Subtle backgrounds with patterns */
        .bg-subtle-dots {
            background-color: var(--gray-50);
            background-image: radial-gradient(var(--gray-200) 1px, transparent 1px);
            background-size: 20px 20px;
        }

        .bg-subtle-grid {
            background-color: var(--white);
            background-image: linear-gradient(var(--gray-100) 1px, transparent 1px),
                linear-gradient(90deg, var(--gray-100) 1px, transparent 1px);
            background-size: 20px 20px;
        }

        /* Intersecting observer animation helpers */
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
</head>

<body>
    <?php require APPROOT . '/views/layouts/navbar.php'; ?>

    <main class="container-fluid p-0">