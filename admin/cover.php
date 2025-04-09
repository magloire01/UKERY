<?php
require_once 'config.php';
require_once 'database.php';

$current_page = 'cover';
$message = '';
$message_type = '';

// Définir le chemin du dossier uploads
$upload_dir ='uploads/cover/';

// Créer le dossier s'il n'existe pas
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Traitement de l'ajout de couverture
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = Database::getInstance()->getConnection();
        $type = $_POST['type']; // 'image' ou 'video'
        
        if ($type === 'image') {
            // Gestion de l'upload d'image
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['cover_image']['name'];
                $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                if (in_array($filetype, $allowed)) {
                    $new_filename = 'cover_' . uniqid() . '.' . $filetype;
                    $upload_path = $upload_dir . $new_filename;
                    
                    if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $upload_path)) {
                        // Désactiver toutes les couvertures existantes
                        $db->query("UPDATE cover SET active = 0");
                        
                        // Insérer la nouvelle couverture
                        $stmt = $db->prepare("INSERT INTO cover (type, file_name, active) VALUES ('image', ?, 1)");
                        $stmt->execute([$new_filename]);
                        
                        $message = "Couverture image ajoutée avec succès";
                        $message_type = "success";
                    }
                }
            }
        } else {
            // Gestion du lien vidéo
            $video_url = trim($_POST['video_url']);
            if (!empty($video_url)) {
                // Désactiver toutes les couvertures existantes
                $db->query("UPDATE cover SET active = 0");
                
                // Insérer la nouvelle couverture
                $stmt = $db->prepare("INSERT INTO cover (type, file_name, active) VALUES ('video', ?, 1)");
                $stmt->execute([$video_url]);
                
                $message = "Couverture vidéo ajoutée avec succès";
                $message_type = "success";
            }
        }
    } catch(PDOException $e) {
        $message = "Erreur lors de l'ajout de la couverture";
        $message_type = "error";
    }
}

// Récupération de la couverture active
try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT * FROM cover WHERE active = 1 LIMIT 1");
    $current_cover = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $current_cover = null;
}

if (isset($_GET['toggle_active'])) {
    try {
        $db = Database::getInstance()->getConnection();
        
        // Récupérer l'état actuel
        $stmt = $db->prepare("SELECT active FROM cover WHERE id = ?");
        $stmt->execute([$_GET['toggle_active']]);
        $current_active = $stmt->fetchColumn();
        
        // Inverser l'état
        $new_active = $current_active ? 0 : 1;
        
        $stmt = $db->prepare("UPDATE cover SET active = ? WHERE id = ?");
        $stmt->execute([$new_active, $_GET['toggle_active']]);
        
        $message = $new_active ? "Couverture activée" : "Couverture désactivée";
        $message_type = "success";
        
        header('Location: cover.php');
        exit();
    } catch(PDOException $e) {
        $message = "Erreur lors de la modification de l'état";
        $message_type = "error";
    }
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

        .cover-container {
            margin-left: 250px;
            padding: 20px;
        }

        .form-container {
            background-color: var(--darker-bg);
            border: 1px solid var(--primary-color);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
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

        .type-selector {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .type-option {
            padding: 10px 20px;
            background-color: var(--dark-bg);
            border: 1px solid var(--primary-color);
            border-radius: 5px;
            color: var(--light-text);
            cursor: pointer;
        }

        .type-option.active {
            background-color: var(--primary-color);
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
        }

        .current-cover {
            background-color: var(--darker-bg);
            border: 1px solid var(--primary-color);
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }

        .current-cover img {
            max-width: 100%;
            border-radius: 5px;
        }

        .btn {
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: var(--light-text);
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .message.success {
            background-color: rgba(40, 167, 69, 0.2);
            border: 1px solid #28a745;
            color: #28a745;
        }

        .message.error {
            background-color: rgba(220, 53, 69, 0.2);
            border: 1px solid #dc3545;
            color: #dc3545;
        }
    </style>
</head>
<body>
    <?php require_once 'sidebar.php'; ?>

    <div class="cover-container">
        <div class="page-header">
            <h1>Gestion de la Couverture</h1>
        </div>

        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <div class="type-selector">
                <div class="type-option active" data-type="image">Image</div>
                <div class="type-option" data-type="video">Vidéo</div>
            </div>

            <!-- Formulaire Image -->
            <form method="POST" enctype="multipart/form-data" class="form-section active" id="image-form">
                <input type="hidden" name="type" value="image">
                <div class="form-group">
                    <label for="cover_image">Sélectionner une image</label>
                    <input type="file" id="cover_image" name="cover_image" accept="image/*" required>
                    <div class="image-preview"></div>
                </div>
                <button type="submit" class="btn">Définir comme couverture</button>
            </form>

            <!-- Formulaire Vidéo -->
            <form method="POST" class="form-section" id="video-form">
                <input type="hidden" name="type" value="video">
                <div class="form-group">
                    <label for="video_url">Lien de la vidéo (YouTube, Vimeo...)</label>
                    <input type="url" id="video_url" name="video_url" placeholder="https://" required>
                </div>
                <button type="submit" class="btn">Définir comme couverture</button>
            </form>
        </div>

        <!-- Affichage de la couverture actuelle -->
        <?php if ($current_cover): ?>
        <div class="current-cover">
            <h2>Couverture actuelle</h2>
            <?php if ($current_cover['type'] === 'image'): ?>
                <img src="uploads/cover/<?php echo htmlspecialchars($current_cover['file_name']); ?>" alt="Couverture">
            <?php else: ?>
                <div class="video-container">
                    <iframe src="<?php echo htmlspecialchars($current_cover['file_name']); ?>" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen></iframe>
                </div>
            <?php endif; ?>

            <div class="cover-actions">
        <a href="?toggle_active=<?php echo $current_cover['id']; ?>" 
           class="btn <?php echo $current_cover['active'] ? 'btn-unpublish' : 'btn-publish'; ?>">
            <?php echo $current_cover['active'] ? 'Désactiver' : 'Activer'; ?>
        </a>
    </div>
        </div>
        <?php endif; ?>
    </div>

    <script>
        // Gestion des onglets image/vidéo
        const typeOptions = document.querySelectorAll('.type-option');
        const forms = document.querySelectorAll('.form-section');

        typeOptions.forEach(option => {
            option.addEventListener('click', () => {
                // Mise à jour des onglets
                typeOptions.forEach(opt => opt.classList.remove('active'));
                option.classList.add('active');

                // Mise à jour des formulaires
                const type = option.dataset.type;
                forms.forEach(form => {
                    form.classList.remove('active');
                    if (form.id === `${type}-form`) {
                        form.classList.add('active');
                    }
                });
            });
        });

        // Preview de l'image
        document.getElementById('cover_image').addEventListener('change', function(e) {
            const preview = document.querySelector('.image-preview');
            const file = e.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '300px';
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