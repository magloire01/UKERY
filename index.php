<?php
require_once 'admin/config.php';
require_once 'admin/database.php';

// Récupération des photos publiées
try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT * FROM phototeques WHERE statut = 'publie' ORDER BY date_ajout DESC LIMIT 8");
    $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $db->query("SELECT *, DATE_FORMAT(date_ajout, '%d/%m/%Y à %H:%i') as date_formatee 
                        FROM videos 
                        WHERE statut = 'publie' 
                        ORDER BY date_ajout DESC 
                        LIMIT 1");
    $latest_video = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $db->query("SELECT *, DATE_FORMAT(date_ajout, '%d/%m/%Y à %H:%i') as date_formatee 
    FROM musiques 
    WHERE statut = 'publie' 
    ORDER BY date_ajout DESC 
    LIMIT 1");
    $latest_musique = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $db->query("SELECT * FROM actualites WHERE statut = 'publie' ORDER BY date_creation DESC LIMIT 8");
    $actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupération de la couverture active
    $stmt = $db->query("SELECT * FROM cover WHERE active = 1 LIMIT 1");
    $current_cover = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $photos = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UKERY - Landing Page</title>
    <link rel="stylesheet" href="boxicons/css/boxicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/stylesindex.css">
</head>
<body>
    <nav id="navbar">
        <div class="logo">
            <img class="imageLogo" src="assets/images/logo.jpg">
        </div>
        <div class="menu-toggle">
            <i class="bx bx-menu"></i>
        </div>
        <div class="navRight">
            <ul>
                <li><a href="#home">Accueil</a></li>
                <li><a href="#bibliography">Bibliographie</a></li>
                <li><a href="music" id="goToNextPage">Musics</a></li>
                <li><a href="videos">Videos</a></li>
                <li><a href="#phototheque-container">Photothèque</a></li>
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

    <?php if ($current_cover): ?>
        <?php if ($current_cover['type'] === 'image' && $current_cover['active']): ?>
            <header id="home">
                <div style="width: 100%;height: 100%;position: absolute">
                    <img src="admin/uploads/cover/<?php echo htmlspecialchars($current_cover['file_name']); ?>" style="width:100%;height:150%;">
                </div>
        <?php else: ?>
            <header id="home" style="background: url('Acc.jpg') no-repeat center/cover;">
        <?php endif; ?>
    <?php else: ?>
        <header id="home" style="background: url('Acc.jpg') no-repeat center/cover;">
    <?php endif; ?>
        <h1>Je suis l'artiste <span class="highlight">UKERY</span></h1>
        <p>La musique donne une âme à nos cœurs et des ailes à la pensée</p>
        <div class="wave"></div>
    </header>

    <section id="actualite">
        <div class="containerA">
            <h2>Actualité</h2>
            <div class="actualite-slider">
                <?php
                try {
                    if (!empty($actualites)) {
                        foreach($actualites as $actualite) {
                            ?>
                            <div class="slide">
                                <div class="actualite-content">
                                    <div class="actualite-img">
                                        <img src="admin/uploads/actualites/<?php echo htmlspecialchars($actualite['image']); ?>" 
                                             alt="<?php echo htmlspecialchars($actualite['titre']); ?>">
                                    </div>
                                    <div class="actualite-text">
                                        <h3><?php echo htmlspecialchars($actualite['titre']); ?></h3>
                                        <p><?php echo htmlspecialchars($actualite['contenu']); ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<p style="color: #fff; text-align: center;">Aucune actualité disponible</p>';
                    }
                } catch(PDOException $e) {
                    var_dump($e->getMessage());
                    echo '<p style="color: #fff; text-align: center;"></p>';
                }
                ?>
                <button class="prev">❮</button>
                <button class="next">❯</button>
                <div class="dots"></div>
            </div>
        </div>
    </section>

    <section id="bibliography">
        <div class="text-content">
            <h2>Bibliographie</h2>
            <p>Vainqueure du concours Goethe découverte catégorie musique voix 2022
                et du Cameroun Talent Show la même année, Ukëry n'a pas eu à attendre bien
                longtemps pour que la profession lui reconnaisse son talent. Pourtant obligée
                de mettre fin à sa carrière et abandonner son rêve de faire carrière dans
                l'industrie musicale, c'est en feu que celle qui a continué de nourrir
                secrètement son rêve Ukëry revient sur la scène musicale Camerounaise.
                MEFOUMANE LEPIDI Alexandra Rijkarde connue sous le pseudo Ukëry est une
                Auteure-Compositrice-Interprète camerounaise née le 01 Février 2001 et
                native de la région du Sud Cameroun est une chanteuse rigoureuse, déterminée
                et engagée.
                D'une mère enseignante et d’un père greffier, Ukëry est l'aînée d'une
                fratrie de deux enfants. Très vite, elle développe une passion qui ne la quittera
                plus jamais : La Musique. Après avoir testé plusieurs instruments, elle adopte
                finalement le piano comme son instrument de prédilection. Elle vit ses
                premières expériences de musique à l'école, ensuite avec les différents
                orchestres de son église et c'est à ce moment qu'elle démarre une carrière qui
                lui ouvre les portes sur l'international mais qui ne fera pas long feu à cause de
                ses problèmes de santé qui durent plusieurs années. Sa petite carrière ne laisse
                malheureusement aucune trace dans la mémoire collective jusqu'à sa
                réapparition en 2020 avec un Mashup et des covers qui la remettent sur le
                devant de la scène. Aujourd'hui Diplômée en Diplomatie et droit consulaire une
                profession qu'elle marie bien à la musique, Ukëry est une artiste prête à
                conquérir le monde et à se faire entendre. Vainqueure du Goethe découverte
                catégorie musique voix et du Cameroun Talents Show, elle a sur le marché un
                Ep baptisé Enyiñ et est en préparation de son premier album studio
            </p>
        </div>
        <div class="image-content">
            <img src="assets/images/Acc.jpg" alt="Bibliography Image">
        </div>
    </section>

    <section id="section">
        <div class="container"> 
            <div class="title">MUSIC</div>
            <?php if ($latest_musique): ?>
            <div class="album-cover"> <img src="admin/uploads/musiques/<?php echo htmlspecialchars($latest_musique['image']); ?>" alt="Album Cover"> </div>
            <div class="album-info">
                <div><?php echo htmlspecialchars($latest_musique['titre']); ?></div>
                <a href="<?php echo $latest_musique['lien']; ?>" class="more-releases-button" >ECOUTER</a>
                <a href="music.php" class="more-releases-button">PLUS DE MUSICS</a>
            </div>
            <?php else: ?>
            <div class="album-cover"> <img src="music.png" alt="Album Cover"> </div>
            <div class="album-info">
                <div>FUNDS (FEAT. ODUMODUBLVCK & CHIKE)</div> 
            </div>
            <a href="#" class="more-releases-button">ECOUTER</a>
            <a href="music.php" class="more-releases-button">VPLUS DE MUSICS</a>
            <?php endif; ?>
        </div>
    </section>

    <section id="section">
        <div class="container"> 
            <div class="title">VIDEOS</div>
            <?php if ($latest_video): ?>
            <div class="album-cover"> <img src="admin/uploads/videos/<?php echo htmlspecialchars($latest_video['image']); ?>" alt="Album Cover"> </div>
            <div class="album-info">
                <div><?php echo htmlspecialchars($latest_video['titre']); ?></div>
            </div>
            <a href="<?php echo $latest_video['lien']; ?>" class="more-releases-button">VOIR</a>
            <a href="videos.php" class="more-releases-button">PLUS DE VIDEOS</a> 
            <?php else: ?>
            <div class="album-cover"> <img src="videos.png" alt="Album Cover"> </div>
            <div class="album-info">
                <div>FUNDS (FEAT. ODUMODUBLVCK & CHIKE)</div> 
            </div>
            <a href="#" class="more-releases-button">VOIR</a>
            <a href="videos.php" class="more-releases-button">PLUS DE VIDEOS</a>
            <?php endif; ?>
        </div>
    </section>

    <div id="phototheque-container">
    <h2>Phototheque</h2>
    <div class="photo-grid">
        <?php if (!empty($photos)): ?>
            <?php foreach($photos as $photo): ?>
                <img src="admin/uploads/phototheques/<?php echo htmlspecialchars($photo['image']); ?>" 
                     alt="Photo" 
                     class="photo">
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: #fff; grid-column: 1/-1; text-align: center;">Aucune photo disponible</p>
        <?php endif; ?>
    </div>
    </div>

    <?php include 'footer.php'; ?>

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

            const bibliography = document.getElementById('bibliography');
            const rect = bibliography.getBoundingClientRect();
            if (rect.top <= window.innerHeight && rect.bottom >= 0) {
                bibliography.classList.add('visible');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.slide');
            const dotsContainer = document.querySelector('.dots');
            let currentSlide = 0;

            slides.forEach((_, i) => {
                const dot = document.createElement('span');
                dot.className = 'dot';
                dot.onclick = () => showSlide(i);
                dotsContainer.appendChild(dot);
            });

            const dots = document.querySelectorAll('.dot');

            function showSlide(n) {
                slides.forEach(slide => slide.classList.remove('active'));
                dots.forEach(dot => dot.classList.remove('active'));
                
                currentSlide = (n + slides.length) % slides.length;
                slides[currentSlide].classList.add('active');
                dots[currentSlide].classList.add('active');
            }

            document.querySelector('.prev').onclick = () => showSlide(currentSlide - 1);
            document.querySelector('.next').onclick = () => showSlide(currentSlide + 1);

            showSlide(0);
            setInterval(() => showSlide(currentSlide + 1), 30000);

            const menuToggle = document.querySelector('.menu-toggle');
            const navRight = document.querySelector('.navRight');
            menuToggle.addEventListener('click', () => {
                navRight.classList.toggle('active');
            });
        });
    </script>
</body>
</html>