<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($data['title']) ? $data['title'] . ' | ' . SITE_NAME : SITE_NAME; ?></title>

    <!-- Favicon -->
    <link rel="icon" href="<?php echo URL_ROOT; ?>/public/images/favicon.ico" type="image/x-icon">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/css/style.css">

    <!-- Page-specific CSS -->
    <?php if (isset($data['css'])) : ?>
        <?php foreach ($data['css'] as $css) : ?>
            <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/css/<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Additional meta tags -->
    <meta name="description" content="<?php echo isset($data['description']) ? $data['description'] : SITE_DESCRIPTION; ?>">

    <style>
        /* Auth navbar styles */
        .auth-navbar {
            height: 70px;
            border-bottom: 1px solid #e4e5e7;
            background-color: #fff;
            display: flex;
            align-items: center;
            padding: 0 24px;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .auth-navbar-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .marketplace-name {
            font-size: 24px;
            font-weight: 700;
            color: #0a1128;
            text-decoration: none;
        }

        .marketplace-name:hover {
            color: #050914;
            text-decoration: none;
        }

        .auth-links {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .auth-links a {
            color: #001e00;
            font-weight: 500;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }

        .auth-links a:hover {
            color: #0a1128;
        }

        .auth-button {
            background-color: transparent;
            color: #0a1128;
            border: 2px solid #0a1128;
            border-radius: 50px;
            padding: 8px 16px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .auth-button.active {
            background-color: #0a1128;
            color: #ffffff;
        }

        .auth-text-link {
            color: #001e00;
            font-weight: 500;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }

        .auth-text-link:hover {
            color: #0a1128;
        }

        /* Role toggle styles */
        #role-toggle-container {
            display: flex;
            gap: 12px;
        }

        .role-button.hidden {
            display: none;
        }

        /* Add top padding to main content to account for fixed navbar */
        #main-content {
            padding-top: 20px;
        }

        /* Mobile adjustments */
        @media (max-width: 576px) {
            .auth-navbar {
                padding: 0 16px;
                height: 60px;
            }

            .auth-links {
                gap: 16px;
            }

            #main-content {
                padding-top: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Auth navbar -->
    <nav class="auth-navbar">
        <div class="auth-navbar-container">
            <a href="<?php echo URL_ROOT; ?>" class="marketplace-name">LenSi</a>
        </div>
    </nav>

    <!-- Auth navbar JavaScript for toggle functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if we're on the register page
            const toggleRoleButton = document.getElementById('toggle-role-button');

            if (toggleRoleButton) {
                // Check URL parameters to initialize the correct state
                const urlParams = new URLSearchParams(window.location.search);
                const userType = urlParams.get('type');

                // Add click event listeners for role buttons
                toggleRoleButton.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Determine new role
                    const newRole = toggleRoleButton.textContent.includes('Freelancer') ? 'freelancer' : 'client';

                    // Update button text and href
                    if (newRole === 'freelancer') {
                        toggleRoleButton.textContent = 'Apply for Client';
                        toggleRoleButton.href = '<?php echo URL_ROOT; ?>/users/register?type=client';
                    } else {
                        toggleRoleButton.textContent = 'Apply for Freelancer';
                        toggleRoleButton.href = '<?php echo URL_ROOT; ?>/users/register?type=freelancer';
                    }

                    // Update URL without page reload
                    const newUrl = `${window.location.pathname}?type=${newRole}`;
                    history.pushState(null, '', newUrl);

                    // Update page content for the selected role
                    updatePageContent(newRole);
                });

                // Function to update the page content based on selected role
                function updatePageContent(role) {
                    const headingElement = document.querySelector('.auth-form-container h2');
                    const descriptionElement = document.querySelector('.auth-form-container h2 + p');
                    const userTypeInput = document.querySelector('input[name="user_type"]');

                    if (headingElement && descriptionElement && userTypeInput) {
                        userTypeInput.value = role;

                        if (role === 'freelancer') {
                            headingElement.textContent = 'Sign up to find work you love';
                            descriptionElement.textContent = 'Join thousands of freelancers who find success on our platform';
                        } else if (role === 'client') {
                            headingElement.textContent = 'Sign up to hire top talent';
                            descriptionElement.textContent = 'Access our global pool of professional freelancers';
                        }
                    }
                }
            }
        });
    </script>

    <main id="main-content">