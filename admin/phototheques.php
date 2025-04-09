<?php
require_once 'config.php';
$current_page = 'phototheques';

//s'assurer que le user est connecte
if(!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}
require_once 'database.php';

$message = '';
$message_type = '';

// Définir le chemin du dossier uploads
$upload_dir = 'uploads/phototheques/';

// Créer le dossier s'il n'existe pas
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Traitement de l'ajout des photos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = Database::getInstance()->getConnection();
        
        // Gestion des uploads multiples
        if (isset($_FILES['images'])) {
            $files = $_FILES['images'];
            $file_count = count($files['name']);
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            
            for ($i = 0; $i < $file_count; $i++) {
                if ($files['error'][$i] === 0) {
                    $filename = $files['name'][$i];
                    $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    
                    if (in_array($filetype, $allowed)) {
                        $new_filename = uniqid() . '.' . $filetype;
                        $upload_path = $upload_dir . $new_filename;
                        
                        if (move_uploaded_file($files['tmp_name'][$i], $upload_path)) {
                            // Insertion dans la base de données
                            $stmt = $db->prepare("INSERT INTO phototeques (image) VALUES (?)");
                            $stmt->execute([$new_filename]);
                        }
                    }
                }
            }
            $message = "Images ajoutées avec succès";
            $message_type = "success";
        }
    } catch(PDOException $e) {
       
        $message = "Erreur lors de l'ajout des images";
        $message_type = "error";
    }
}

if (isset($_GET['delete'])) {
    try {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("SELECT image FROM phototeques WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $image = $stmt->fetchColumn();
        
        if ($image) {
            $image_path = $upload_dir . $image;
            //var_dump($image_path);
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        
        $stmt = $db->prepare("DELETE FROM phototeques WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        
        $message = "Image supprimée avec succès";
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
        $stmt = $db->prepare("UPDATE phototeques SET statut = IF(statut = 'publie', 'brouillon', 'publie') WHERE id = ?");
        $stmt->execute([$_GET['toggle_status']]);
    } catch(PDOException $e) {
        $message = "Erreur lors de la modification du statut";
        $message_type = "error";
    }
}

// Récupération des photos
try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT * FROM phototeques ORDER BY date_ajout DESC");
    $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $photos = [];
    $message = "Erreur lors de la récupération des photos";
    $message_type = "error";
}
?>
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

        .btn-publish {
    background-color: #28a745;
}

.btn-unpublish {
    background-color: #ffc107;
}.btn-delete {
    background-color: #dc3545;
    color: var(--light-text);
    text-decoration: none;
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

        /* Zone d'upload */
        .upload-container {
            background-color: var(--darker-bg);
            border: 2px dashed var(--primary-color);
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-container:hover {
            background-color: rgba(255, 140, 0, 0.1);
        }

        .upload-container i {
            font-size: 48px;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .upload-text {
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        #file-input {
            display: none;
        }

        /* Grille de photos */
        .photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            background-color: var(--darker-bg);
            border: 1px solid var(--primary-color);
            border-radius: 10px;
            padding: 20px;
        }

        .photo-item {
            position: relative;
            aspect-ratio: 1;
            overflow: hidden;
            border-radius: 5px;
        }

        .photo-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .photo-item:hover img {
            transform: scale(1.05);
        }

        .photo-actions {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .photo-item:hover .photo-actions {
            opacity: 1;
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

        .btn-danger {
            background-color: #dc3545;
        }

        .btn:hover {
            opacity: 0.9;
        }

        /* Preview des images à uploader */
        .preview-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .preview-item {
            position: relative;
            aspect-ratio: 1;
        }

        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 5px;
        }

        .preview-remove {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(220, 53, 69, 0.8);
            color: white;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php require_once 'sidebar.php'; ?>

    <div class="main-content">
        <div class="page-header">
            <h1>Photothèque</h1>
            <p>Gestion des photos du site</p>
        </div>

<!-- Zone d'upload -->
<form action="" method="POST" enctype="multipart/form-data">
    <div class="upload-container" id="upload-zone">
        <i class="fas fa-cloud-upload-alt"></i>
        <h3 class="upload-text">Glissez vos images ici</h3>
        <p>ou</p>
        <button type="button" class="btn btn-primary" style="background-color: var(--primary-color);">
            Sélectionner des fichiers
        </button>
        <input type="file" name="images[]" id="file-input" multiple accept="image/*">
    </div>

    <!-- Preview des images à uploader -->
    <div class="preview-container" id="preview-container"></div>

    <!-- Bouton d'envoi -->
    <div style="text-align: center; margin: 20px 0;">
        <button type="submit" class="btn btn-primary" style="background-color: var(--primary-color);">
            <i class="fas fa-upload"></i> Envoyer les photos
        </button>
    </div>
</form>

        <!-- Grille des photos -->
        <div class="photo-grid">
        <?php foreach($photos as $photo): ?>
                <div class="photo-item">
                    <img src="uploads/phototheques/<?php echo htmlspecialchars($photo['image']); ?>" 
                         alt="Photo">
                    <div class="photo-actions">
                        <a href="?toggle_status=<?php echo $photo['id']; ?>" 
                           class="btn <?php echo $photo['statut'] === 'publie' ? 'btn-unpublish' : 'btn-publish'; ?>">
                            <?php echo $photo['statut'] === 'publie' ? 'Enlever' : 'Publier'; ?>
                        </a>
                        <a href="?delete=<?php echo $photo['id']; ?>" 
                           class="btn btn-delete" 
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette photo ?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
            <!-- Répéter pour chaque photo -->
        </div>
    </div>

    <script>
        const uploadZone = document.getElementById('upload-zone');
        const fileInput = document.getElementById('file-input');
        const previewContainer = document.getElementById('preview-container');

        // Ouvrir le sélecteur de fichiers au clic
        uploadZone.addEventListener('click', () => fileInput.click());

        // Gestion du drag & drop
        uploadZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadZone.style.backgroundColor = 'rgba(255, 140, 0, 0.1)';
        });

        uploadZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            uploadZone.style.backgroundColor = '';
        });

        uploadZone.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadZone.style.backgroundColor = '';
            handleFiles(e.dataTransfer.files);
        });

        // Gestion de la sélection de fichiers
        fileInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });

        function handleFiles(files) {
            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const previewItem = document.createElement('div');
                        previewItem.className = 'preview-item';
                        previewItem.innerHTML = `
                            <img src="${e.target.result}" alt="Preview">
                            <div class="preview-remove">×</div>
                        `;
                        previewContainer.appendChild(previewItem);

                        // Suppression de la preview
                        previewItem.querySelector('.preview-remove').addEventListener('click', () => {
                            previewItem.remove();
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    </script>
</body>
</html>