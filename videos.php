<?php
require_once 'admin/config.php';
require_once 'admin/database.php';

// Récupération des photos publiées
try {
    $db = Database::getInstance()->getConnection();
   
     // Récupération des musiques
     $stmt = $db->query("SELECT * FROM musiques WHERE statut = 'publie' ORDER BY date_ajout DESC LIMIT 4");
     $musiques = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
     // Récupération des vidéos
     $stmt = $db->query("SELECT * FROM videos WHERE statut = 'publie' ORDER BY date_ajout DESC LIMIT 4");
     $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);    
} catch(PDOException $e) {
    $photos = [];
    $musiques = [];
    $videos = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Videos Page</title>
  <link rel="stylesheet" href="styles/stylesMusic.css">
  <link rel="stylesheet" href="boxicons/css/boxicons.min.css">
</head>
<body>
    <nav id="navbar">
        <div class="logo">
          <img class="imageLogo" src="assets/images/logo.jpg" alt="Logo">
        </div>
        <div class="menu-toggle">
            <i class="bx bx-menu"></i>
        </div>
        <div class="navRight">
            <ul>
                <li><a href="index">Accueil</a></li>
                <li><a href="index.php#bibliography">Bibliographie</a></li>
                <li><a href="music">Musics</a></li>
                <li><a href="videos">Videos</a></li>
                <li><a href="index.php#phototheque-container">Photothèque</a></li>
                <li><a href="contact">Contact</a></li>
            </ul>
            <div class="social-icons">
                <a href="https://www.facebook.com/ukery?mibextid=ZbWKwL"><i class="bx bxl-facebook"></i></a>
                <a href="https://twitter.com/UkeryOfficiel?t=ImmFAX8dgfEucu8fc2F25w&s=09"><i class="bx bxl-twitter"></i></a>
                <a href="https://instagram.com/ukery_officiel?igshid=MmVlMjlkMTBhMg"><i class="bx bxl-instagram"></i></a>
                <a href="https://www.youtube.com/@ukeryofficiel9068"><i class="bx bxl-youtube"></i></a>
                <a href="#"><i class="bx bxl-tiktok"></i></a>
                <a href="https://open.spotify.com/album/50ZkPRu1h9j6I38qywqIGL"><i class="bx bxl-spotify"></i></a>
                <a href="mailto:ukeryofficiel@gmail.com"><i class="bx bx-envelope"></i></a>
            </div>
        </div>
    </nav>
  <div class="music-container">
    <h1>VIDEOS</h1>
    <hr>

    <?php foreach ($videos as $video): ?>
      <div class="music-item">
        <div class="details">
          <h3><?php echo htmlspecialchars($video['titre']); ?></h3>
          <button onclick="window.open('<?php echo htmlspecialchars($video['lien']); ?>')" 
              class="watch-button">WATCH</button> <!-- Changé "LISTEN" en "WATCH" -->
        </div>
        <img src="admin/uploads/videos/<?php echo htmlspecialchars($video['image']); ?>" 
           alt="<?php echo htmlspecialchars($video['titre']); ?>">
      </div>
    <?php endforeach; ?>

    <hr>
  </div>

  <?php include 'footer.php'; ?>

  <!-- JavaScript intégré directement -->
  <script>
    let lastScrollTop = 0;
    window.addEventListener("scroll", function() {
        const navbar = document.getElementById('navbar');
        let currentScroll = window.pageYOffset || document.documentElement.scrollTop;
        
        if (currentScroll > lastScrollTop) {
            navbar.classList.add('hidden');
        } else {
            navbar.classList.remove('hidden');
        }
        lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
    });

    document.addEventListener('DOMContentLoaded', function() {
        const menuToggle = document.querySelector('.menu-toggle');
        const navRight = document.querySelector('.navRight');
        menuToggle.addEventListener('click', () => {
            navRight.classList.toggle('active');
        });
    });
  </script>
</body>
</html>