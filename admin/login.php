<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #ff8c00;
            --dark-bg: #121212;
            --darker-bg: #0a0a0a;
            --light-text: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--dark-bg);
            color: var(--light-text);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .login-container {
            background-color: var(--darker-bg);
            padding: 40px;
            border-radius: 10px;
            border: 1px solid var(--primary-color);
            width: 90%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header i {
            font-size: 48px;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .login-header h1 {
            color: var(--primary-color);
            font-size: 24px;
            margin: 15px 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .input-group {
            position: relative;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            padding-right: 40px;
            background-color: transparent;
            border: 1px solid var(--primary-color);
            border-radius: 5px;
            color: var(--light-text);
            font-size: 16px;
        }

        .input-group i {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-color);
            color: var(--light-text);
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: opacity 0.3s ease;
            margin-top: 20px;
        }

        .btn-login:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-user-circle"></i>
            <h1>Admin Panel</h1>
        </div>
        <?php
        // Affichage des messages d'erreur
        if (isset($_GET['error'])) {
            $error_message = '';
            switch($_GET['error']) {
                case 'invalid_credentials':
                    $error_message = 'Identifiants incorrects';
                    break;
                case 'db_error':
                    $error_message = 'Erreur de connexion à la base de données';
                    break;
                default:
                    $error_message = 'Une erreur est survenue';
            }
            echo '<div class="error-message">' . htmlspecialchars($error_message) . '</div>';
        }
        ?>

        <form method="POST" action="auth.php">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <div class="input-group">
                    <input type="text" id="username" name="username" required>
                    <i class="fas fa-user"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" required>
                    <i class="fas fa-eye" id="togglePassword"></i>
                </div>
            </div>

            <button type="submit" class="btn-login">Se connecter</button>
        </form>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>