<?php
require_once 'config.php';
require_once 'database.php';
$current_page = 'musiques';

$message = '';
$message_type = '';

//s'assurer que le user est connecte
if(!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Définir le chemin du dossier uploads
$upload_dir = 'uploads/musiques/';

// Créer le dossier s'il n'existe pas
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Traitement de l'ajout d'une musique
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = Database::getInstance()->getConnection();
        
        $titre = ($_POST['title']);
        $lien = ($_POST['link']);
        
        // Gestion de l'upload d'image
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['image']['name'];
            $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($filetype, $allowed)) {
                $new_filename = uniqid() . '.' . $filetype;
                $upload_path = $upload_dir . $new_filename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    // Insertion dans la base de données
                    $stmt = $db->prepare("INSERT INTO musiques (titre, image, lien) VALUES (?, ?, ?)");
                    if ($stmt->execute([$titre, $new_filename, $lien])) {
                        $message = "Musique ajoutée avec succès";
                        $message_type = "success";
                    }
                } else {
                    $message = "Erreur lors de l'upload de l'image";
                    $message_type = "error";
                }
            } else {
                $message = "Type de fichier non autorisé";
                $message_type = "error";
            }
        }
    } catch(PDOException $e) {
        $message = "Erreur lors de l'ajout de la musique";
        $message_type = "error";
    }
}

// Gestion de la suppression
if (isset($_GET['delete'])) {
    try {
        $db = Database::getInstance()->getConnection();
        
        // Récupération du nom de l'image
        $stmt = $db->prepare("SELECT image FROM musiques WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $image = $stmt->fetchColumn();
        
        // Suppression de l'image
        if ($image) {
            $image_path = $upload_dir . $image;
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        
        // Suppression de l'enregistrement
        $stmt = $db->prepare("DELETE FROM musiques WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        
        $message = "Musique supprimée avec succès";
        $message_type = "success";
    } catch(PDOException $e) {
        $message = "Erreur lors de la suppression";
        $message_type = "error";
    }
}

// Gestion du statut (publier/enlever)
if (isset($_GET['toggle_status'])) {
    try {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE musiques SET statut = IF(statut = 'publie', 'brouillon', 'publie') WHERE id = ?");
        $stmt->execute([$_GET['toggle_status']]);
    } catch(PDOException $e) {
        $message = "Erreur lors de la modification du statut";
        $message_type = "error";
    }
}

// Récupération des musiques
try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT * FROM musiques ORDER BY date_ajout DESC");
    $musiques = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $musiques = [];
    $message = "Erreur lors de la récupération des musiques";
    $message_type = "error";
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
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

        /* Formulaire d'ajout */
        .form-container {
            background-color: var(--darker-bg);
            border: 1px solid var(--primary-color);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: var(--primary-color);
            margin-bottom: 8px;
        }

        .form-group input[type="text"],
        .form-group input[type="url"] {
            width: 100%;
            padding: 10px;
            background-color: var(--dark-bg);
            border: 1px solid var(--primary-color);
            border-radius: 5px;
            color: var(--light-text);
        }

        /* Zone de prévisualisation d'image */
        .image-preview {
            max-width: 200px;
            margin-top: 10px;
        }

        .image-preview img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }

        /* Liste des musiques */
        .music-list {
            background-color: var(--darker-bg);
            border: 1px solid var(--primary-color);
            border-radius: 10px;
            padding: 20px;
        }

        .music-item {
            display: flex;
            align-items: start;
            padding: 15px;
            border-bottom: 1px solid rgba(255, 140, 0, 0.2);
            margin-bottom: 15px;
        }

        .music-image {
            width: 150px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 20px;
        }

        .music-content {
            flex: 1;
        }

        .music-title {
            color: var(--primary-color);
            font-size: 18px;
            margin-bottom: 10px;
        }

        .music-link {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
            word-break: break-all;
        }

        .music-actions {
            display: flex;
            gap: 10px;
        }

        /* Boutons */
        .btn {
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            color: var(--light-text);
        }

        .btn-primary {
            background-color: var(--primary-color);
        }
        // Remplacez les classes CSS actuelles par :

.btn-publish {
    background-color: #28a745;
    color: var(--light-text);
    text-decoration: none;
}

.btn-unpublish {
    background-color: #ffc107;
    color: var(--dark-bg);
    text-decoration: none;
}

.btn-delete {
    background-color: #dc3545;
    color: var(--light-text);
    text-decoration: none;
}

/* Correction des classes pour la liste des musiques */
.musique-item {
    display: flex;
    align-items: start;
    padding: 15px;
    border-bottom: 1px solid rgba(255, 140, 0, 0.2);
    margin-bottom: 15px;
}

.musique-image {
    width: 150px;
    height: 100px;
    object-fit: cover;
    border-radius: 5px;
    margin-right: 20px;
}

.musique-content {
    flex: 1;
}

.musique-actions {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.news-date {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }


        .btn-danger {
            background-color: #dc3545;
        }
        .btn-toggle {
    padding: 8px 20px;
    border-radius: 5px;
    color: var(--light-text);
    text-decoration: none;
    margin-right: 10px;
    cursor: pointer;
}
.btn-publish {
    background-color: #28a745;
}

.btn-unpublish {
    background-color: #ffc107;
}

        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <?php require_once 'sidebar.php'; ?>

    <div class="main-content">
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $message_type; ?>" style="padding: 15px; margin-bottom: 20px; border-radius: 5px; background-color: <?php echo $message_type === 'success' ? '#28a745' : '#dc3545'; ?>;">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
        <div class="page-header">
            <h1>Musiques</h1>
            <p>Gestion des musiques du site</p>
        </div>

        <!-- Formulaire d'ajout -->
        <div class="form-container">
            <h2 style="color: var(--primary-color); margin-bottom: 20px;">Ajouter une musique</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Titre</label>
                    <input type="text" id="title" name="title" required>
                </div>

                <div class="form-group">
                    <label for="image">Image de couverture</label>
                    <input type="file" id="image" name="image" accept="image/*" required>
                    <div class="image-preview"></div>
                </div>

                <div class="form-group">
                    <label for="link">Lien de la musique</label>
                    <input type="url" id="link" name="link" placeholder="https://..." required>
                </div>

                <button type="submit" class="btn btn-primary">Ajouter</button>
            </form>
        </div>

        <!-- Liste des musiques -->
        <div class="music-list">
            <h2 style="color: var(--primary-color); margin-bottom: 20px;">Musiques publiées</h2>
            
            <?php foreach($musiques as $musique): ?>
                <div class="musique-item">
                    <img src="uploads/musiques/<?php echo htmlspecialchars($musique['image']); ?>" 
                         alt="<?php echo htmlspecialchars($musique['titre']); ?>" 
                         class="musique-image">
                    
                    <div class="musique-content">
                        <h3><?php echo htmlspecialchars($musique['titre']); ?></h3>
                        <div class="musique-link">
                            <a href="<?php echo htmlspecialchars($musique['lien']); ?>" 
                            target="_blank"
                            style="color: var(--primary-color);">
                            <?php echo htmlspecialchars($musique['lien']); ?>
                        </a>
                    </div>
                    <div class="news-date">
                    Publié le <?php echo date('d/m/Y', strtotime($musique['date_ajout'])); ?>
                </div>
                        <div class="musique-actions">
                            <a href="?toggle_status=<?php echo $musique['id']; ?>" 
                               class="btn <?php echo $musique['statut'] === 'publie' ? 'btn-unpublish' : 'btn-publish'; ?>">
                                <?php echo $musique['statut'] === 'publie' ? 'Enlever' : 'Publier'; ?>
                            </a>
                            <a href="?delete=<?php echo $musique['id']; ?>" 
                               class="btn btn-delete" 
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette musique ?')">
                                Supprimer
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

         
        </div>
    </div>

    <script>
        // Preview de l'image
        document.getElementById('image').addEventListener('change', function(e) {
            const preview = document.querySelector('.image-preview');
            const file = e.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                preview.innerHTML = '';
                preview.appendChild(img);
            }

            if(file) {
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>