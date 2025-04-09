<?php
require_once 'config.php';
require_once 'database.php';

$current_page = 'dashboard';

//s'assurer que le user est connecte
if(!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}


try {
    $db = Database::getInstance()->getConnection();
    
    // Récupération des statistiques
    $stats = [
        'actualites' => $db->query("SELECT COUNT(*) FROM actualites")->fetchColumn(),
        'musiques' => $db->query("SELECT COUNT(*) FROM musiques")->fetchColumn(),
        'videos' => $db->query("SELECT COUNT(*) FROM videos")->fetchColumn(),
        'photos' => $db->query("SELECT COUNT(*) FROM phototeques")->fetchColumn()
    ];

} catch(PDOException $e) {
    // Gérer l'erreur
    var_dump($e->getMessage());

    $stats = [
        'actualites' => 0,
        'musiques' => 0,
        'videos' => 0,
        'photos' => 0
    ];
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Inclusion de la sidebar -->
    <?php require_once 'header.php'; ?>
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
        }

        /* Main Content Styles */
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            color: var(--primary-color);
            font-size: 32px;
            margin-bottom: 10px;
        }

        /* Cards Grid */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .card {
            background-color: var(--darker-bg);
            border: 1px solid var(--primary-color);
            border-radius: 10px;
            padding: 20px;
        }

        .card-title {
            color: var(--primary-color);
            font-size: 24px;
            margin-bottom: 15px;
        }

        .card-content {
            margin-bottom: 15px;
        }

        .btn-add {
            display: inline-block;
            padding: 8px 20px;
            background-color: var(--primary-color);
            color: var(--light-text);
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-add:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <!-- Inclusion de la sidebar -->
    <?php require_once 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1>Tableau de bord</h1>
            <p>Bienvenue dans votre espace d'administration</p>
        </div>
        
        <?php if (isset($e)): ?>
            <div style="background-color: #ff4444; color: white; padding: 10px; margin-bottom: 20px; border-radius: 5px;">
                <strong>Erreur:</strong> Une erreur est survenue lors de la récupération des données.
            </div>
        <?php endif; ?>

        <div class="cards-grid">
            <!-- Actualités Card -->
            <div class="card">
                <h2 class="card-title">Actualités</h2>
                <div class="card-content">
                    <p>Total: <?php echo $stats['actualites']?> articles</p>
                </div>
                <button class="btn-add">Ajouter</button>
            </div>

            <!-- Musiques Card -->
            <div class="card">
                <h2 class="card-title">Musiques</h2>
                <div class="card-content">
                    <p>Total: <?php echo $stats['musiques']?> titres</p>
                </div>
                <button class="btn-add">Ajouter</button>
            </div>

            <!-- Videos Card -->
            <div class="card">
                <h2 class="card-title">Videos</h2>
                <div class="card-content">
                    <p>Total: <?php echo $stats['videos']?> articles</p>
                </div>
                <button class="btn-add">Ajouter</button>
            </div>

            <!-- Phototheques Card -->
            <div class="card">
                <h2 class="card-title">Phototheques</h2>
                <div class="card-content">
                    <p>Total: <?php echo $stats['photos']?> titres</p>
                </div>
                <button class="btn-add">Ajouter</button>
            </div>
        </div>
    </div>
</body>
</html>