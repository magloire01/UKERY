<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="boxicons/css/boxicons.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, rgba(10, 10, 10, 0.9), rgba(10, 10, 10, 0.8)), url('Acceuil2.png') no-repeat center top/cover;
            color: #fff;
        }

        /* Navbar */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.9);
            color: white;
            display: flex;
            justify-content: space-between;
            padding: 0.3rem 2rem;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        nav.hidden {
            transform: translateY(-100%);
        }

        .logo {
            height: 50px;
            display: flex;
            align-items: center;
        }

        .imageLogo {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .menu-toggle {
            display: none;
            font-size: 2rem;
            color: white;
            cursor: pointer;
        }

        .navRight {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .navRight ul {
            list-style: none;
            display: flex;
            gap: 2rem;
        }

        .navRight ul li a {
            text-decoration: none;
            color: white;
            text-transform: uppercase;
            transition: color 0.3s ease-in-out, font-size 0.3s;
            font-size: 0.9rem;
        }

        .navRight ul li a:hover {
            color: #e16900;
            font-size: 0.95rem;
        }

        .navRight .social-icons {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        .navRight .social-icons a {
            font-size: 1.5rem;
            color: #fff;
            transition: transform 0.3s, color 0.3s;
        }

        .navRight .social-icons a:hover {
            transform: scale(1.2);
            color: rgba(255, 255, 255, 0.7);
        }

        /* Header */
        .header {
            background: linear-gradient(to right, rgba(212, 153, 4, 0.8), rgba(44, 34, 8, 0.8), rgba(0, 0, 0, 0.8));
            color: white;
            text-align: center;
            padding: 50px 20px;
            margin-top: 70px; /* Ajusté pour éviter chevauchement avec navbar */
        }

        .header h1 {
            margin: 0;
            font-size: clamp(2rem, 6vw, 36px);
        }

        /* Main Content */
        .main-content {
            padding: 20px;
            text-align: center;
        }

        .main-content h2 {
            font-size: clamp(1.5rem, 4vw, 24px);
            margin-bottom: 20px;
            color: #eeeeee;
        }

        .main-content p {
            font-size: clamp(1rem, 3vw, 16px);
            color: #d3d3d3;
        }

        /* Footer */
        footer {
            margin-top: 25px;
            background: #101010;
            color: white;
            padding: 2rem 1rem;
            text-align: center;
        }

        /* Media Queries pour la responsivité */
        @media (max-width: 768px) {
            .menu-toggle {
                display: block; /* Affiche le menu burger */
            }

            .navRight {
                display: none; /* Masqué par défaut sur mobile */
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: rgba(0, 0, 0, 0.9);
                padding: 1rem;
                flex-direction: column;
            }

            .navRight.active {
                display: flex; /* Affiche le menu quand actif */
            }

            .navRight ul {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .navRight .social-icons {
                justify-content: center;
            }

            .header {
                padding: 40px 15px;
                margin-top: 60px;
            }

            .main-content {
                padding: 15px;
            }
        }

        @media (max-width: 480px) {
            nav {
                padding: 0.3rem 1rem;
            }

            .logo {
                height: 40px;
            }

            .header {
                padding: 30px 10px;
                margin-top: 50px;
            }

            .main-content h2 {
                margin-bottom: 15px;
            }
        }
    </style>
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

    <div class="header">
        <h1>Contact Us</h1>
    </div>

    <div class="main-content">
        <h2>Booking</h2>
        <p>+237 698 759 625</p>
    </div>
    <div class="main-content">
        <h2>Management</h2>
        <p>+237 698 759 625</p>
    </div>

    <?php include 'footer.php'; ?>

    <!-- JavaScript intégré -->
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