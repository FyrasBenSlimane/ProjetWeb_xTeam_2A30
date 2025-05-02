<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $database = new Database();
        $db = $database->getConnection();
        
        // Vérification si l'utilisateur existe
        $stmt = $db->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin && $password === $admin['password']) {
            $_SESSION['is_admin'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header('Location: dashboard.php');
            exit;
        }
        
        $error = "Identifiants incorrects";
        
    } catch(PDOException $e) {
        $error = "Erreur de connexion à la base de données: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - LenSi</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Inter:wght@300;400;500&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #3E5C76;
            --secondary: #748CAB;
            --accent: #1D2D44;
            --light: #F9F7F0;
        }

        body {
            background: var(--light);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }

        .login-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            background: var(--accent);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .login-body {
            padding: 2rem;
        }

        .login-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
        }

        .btn-login {
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border: none;
            color: white;
            padding: 0.75rem;
            border-radius: 0.5rem;
            font-weight: 600;
            width: 100%;
            margin-top: 1rem;
        }

        .btn-login:hover {
            background: linear-gradient(90deg, var(--secondary), var(--primary));
            color: white;
        }

        [data-bs-theme="dark"] {
            --light: #121212;
        }

        [data-bs-theme="dark"] .login-card {
            background: rgba(255,255,255,0.05);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <i class="bi bi-shield-lock display-4 mb-3"></i>
            <h4 class="login-title">Admin Login</h4>
            <p class="mb-0">Enter your credentials to continue</p>
        </div>
        <div class="login-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-login">
                    Login
                </button>
            </form>
            
            <div class="text-center mt-4">
                <a href="../index.php" class="text-muted text-decoration-none">
                    <i class="bi bi-arrow-left"></i>
                    Back to Website
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>