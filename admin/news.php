<?php
require_once 'config.php';
$current_page = 'actualites';
?>

Collapse
<?php
require_once 'config.php';
require_once 'database.php';

$current_page = 'actualites';

// Gestion des messages
$message = '';
$message_type = '';

//s'assurer que le user est connecte
if(!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}



// Traitement de l'ajout d'une actualité
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = Database::getInstance()->getConnection();
        
        $titre = ($_POST['title']);
        $contenu = ($_POST['content']);
        
        // Gestion de l'upload d'image
        if (isset($_FILES['image'])) {
            if ($_FILES['image']['error'] === 0) {
                $upload_dir = 'uploads/actualites/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                if (!is_writable($upload_dir)) {
                    throw new Exception("Le répertoire de stockage n'est pas accessible en écriture");
                }
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['image']['name'];
                $filetype = pathinfo($filename, PATHINFO_EXTENSION);

                if (in_array(strtolower($filetype), $allowed)) {
                    $new_filename = uniqid() . '.' . $filetype;
                    $upload_path = $upload_dir . $new_filename;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                        // Insertion dans la base de données
                        $stmt = $db->prepare("INSERT INTO actualites (titre, contenu, image) VALUES (?, ?, ?)");
                        if($stmt->execute([$titre, $contenu, $new_filename])) {
                            $message = "Actualité ajoutée avec succès";
                            $message_type = "success";
                        } else {
                            $message = "Erreur lors de l'ajout dans la base de données";
                            $message_type = "error";
                        }
                    } else {
                        $message = "Erreur lors de l'upload du fichier";
                        $message_type = "error";
                    }
                } else {
                    $message = "Type de fichier non autorisé. Seuls les formats JPG, JPEG, PNG et GIF sont acceptés.";
                    $message_type = "error";
                }
            } else {
                $message = "Erreur lors du téléchargement du fichier. Code d'erreur : " . $_FILES['image']['error'];
                $message_type = "error";
            }
        }
    } catch(PDOException $e) {
        $message = "Erreur lors de l'ajout de l'actualité";
        $message_type = "error";
    }
}

// Suppression d'une actualité
if (isset($_GET['delete'])) {
    try {
        $db = Database::getInstance()->getConnection();
        
        // Récupération du nom de l'image
        $stmt = $db->prepare("SELECT image FROM actualites WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $image = $stmt->fetchColumn();
        
        // Suppression de l'image
        if ($image) {
            $image_path = '../uploads/actualites/' . $image;
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        
        // Suppression de l'enregistrement
        $stmt = $db->prepare("DELETE FROM actualites WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        
        $message = "Actualité supprimée avec succès";
        $message_type = "success";
    } catch(PDOException $e) {
        $message = "Erreur lors de la suppression";
        $message_type = "error";
    }
}elseif (isset($_GET['toggle_status'])) {
    try {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE actualites SET statut = IF(statut = 'publie', 'brouillon', 'publie') WHERE id = ?");
        $stmt->execute([$_GET['toggle_status']]);
    } catch(PDOException $e) {
        $message = "Erreur lors de la modification du statut";
        $message_type = "error";
    }
}

// Récupération des actualités
try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT * FROM actualites ORDER BY date_creation DESC");
    $actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $actualites = [];
    $message = "Erreur lors de la récupération des actualités";
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
        .form-group textarea {
            width: 100%;
            padding: 10px;
            background-color: var(--dark-bg);
            border: 1px solid var(--primary-color);
            border-radius: 5px;
            color: var(--light-text);
        }

        .form-group textarea {
            min-height: 150px;
            resize: vertical;
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

        /* Liste des actualités */
        .news-list {
            background-color: var(--darker-bg);
            border: 1px solid var(--primary-color);
            border-radius: 10px;
            padding: 20px;
        }

        .news-item {
            display: flex;
            align-items: start;
            padding: 15px;
            border-bottom: 1px solid rgba(255, 140, 0, 0.2);
            margin-bottom: 15px;
        }

        .news-image {
            width: 150px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 20px;
        }

        .news-content {
            flex: 1;
        }

        .news-title {
            color: var(--primary-color);
            font-size: 18px;
            margin-bottom: 10px;
        }

        .news-date {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .news-actions {
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
            color: var (--light-text);
        }

        .btn-primary {
            background-color: var(--primary-color);
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn:hover {
            opacity: 0.9;
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

.btn-delete {
    background-color: #dc3545;
    padding: 8px 20px;
    border-radius: 5px;
    color: var(--light-text);
    text-decoration: none;
    cursor: pointer;
}


.actualite-actions {
    display: flex;
    margin-top: 10px;
}
    </style>
</head>
<body>
    <?php require_once 'sidebar.php'; ?>

    <div class="main-content">
        <div class="page-header">
            <h1>Actualités</h1>
            <p>Gestion des actualités du site</p>
        </div>
        <div class="main-content">
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $message_type; ?>" style="padding: 15px; margin-bottom: 20px; border-radius: 5px; background-color: <?php echo $message_type === 'success' ? '#28a745' : '#dc3545'; ?>;">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
        <!-- Formulaire d'ajout -->
        <div class="form-container">
            <h2 style="color: var(--primary-color); margin-bottom: 20px;">Ajouter une actualité</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Titre</label>
                    <input type="text" id="title" name="title" required>
                </div>

                <div class="form-group">
                    <label for="content">Contenu</label>
                    <textarea id="content" name="content" required></textarea>
                </div>

                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <div class="image-preview"></div>
                </div>

                <button type="submit" class="btn btn-primary">Publier</button>
            </form>
        </div>

        <!-- Liste des actualités -->
        <div class="news-list">
            <h2 style="color: var(--primary-color); margin-bottom: 20px;">Actualités publiées</h2>

            <?php foreach($actualites as $actualite): ?>
                <div class="news-item">
                    <?php
                $image_path = 'uploads/actualites/' . htmlspecialchars($actualite['image']);
    if (!empty($actualite['image']) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/UKERY/admin/uploads/actualites/' . $actualite['image'])) {
        ?>
        <img src="<?php echo $image_path; ?>" 
             alt="<?php echo htmlspecialchars($actualite['titre']); ?>" 
             class="news-image">
    <?php } else { ?>
        <img src="../assets/images/default-news.jpg" 
             alt="Image par défaut" 
             class="news-image">
    <?php } ?>
                    
                    <div class="news-content">
                        <h3><?php echo htmlspecialchars($actualite['titre']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($actualite['contenu'], 0, 200)) . '...'; ?></p>
                        <div class="news-date">
                            Publié le <?php echo date('d/m/Y', strtotime($actualite['date_creation'])); ?>
                        </div>
                        <div class="news-actions">
                        <a href="?toggle_status=<?php echo $actualite['id']; ?>" 
                   class="btn-toggle <?php echo $actualite['statut'] === 'publie' ? 'btn-unpublish' : 'btn-publish'; ?>">
                    <?php echo $actualite['statut'] === 'publie' ? 'Enlever' : 'Publier'; ?>
                </a>
                            <a href="?delete=<?php echo $actualite['id']; ?>" 
                               class="btn btn-delete" 
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette actualité ?')">
                                Supprimer
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Répéter pour chaque actualité -->
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