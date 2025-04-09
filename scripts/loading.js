
        // Lorsque l'utilisateur clique sur le bouton, on montre la page de chargement
        document.getElementById("goToNextPage").onclick = function() {
            // Crée un élément de la page de chargement
            let loadingPage = window.open("", "_blank");

            loadingPage.document.write(`
                <!DOCTYPE html>
                <html lang="fr">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Chargement...</title>
                    <style>
                        body {
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            height: 100vh;
                            margin: 0;
                            background-color: black;
                            overflow: hidden;
                            position: relative;
                        }
                        .loader {
                            position: relative;
                            border: 8px solid #f3f3f3;
                            border-top: 8px solid #3498db;
                            border-radius: 50%;
                            width: 80px;
                            height: 80px;
                            animation: spin 2s linear infinite;
                        }
                        .loader img {
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%);
                            width: 40px; /* Taille du logo */
                            height: 40px; /* Taille du logo */
                        }
                        @keyframes spin {
                            0% { transform: rotate(0deg); }
                            100% { transform: rotate(360deg); }
                        }
                        .bubbles {
                            position: absolute;
                            bottom: 0;
                            width: 100%;
                            height: 100%;
                            display: flex;
                            justify-content: space-around;
                            animation: bubbles 3s infinite;
                        }
                        .bubble {
                            width: 15px;
                            height: 15px;
                            border-radius: 50%;
                            background-color: orange;
                            animation: rise 3s infinite;
                        }
                        .bubble:nth-child(1) {
                            animation-delay: 0.5s;
                        }
                        .bubble:nth-child(2) {
                            animation-delay: 1s;
                        }
                        .bubble:nth-child(3) {
                            animation-delay: 1.5s;
                        }
                        .bubble:nth-child(4) {
                            animation-delay: 2s;
                        }
                        @keyframes rise {
                            0% {
                                transform: translateY(0);
                                opacity: 1;
                            }
                            100% {
                                transform: translateY(-100vh);
                                opacity: 0;
                            }
                        }
                        @keyframes bubbles {
                            0% { transform: translateY(0); }
                            100% { transform: translateY(-100%); }
                        }
                    </style>
                </head>
                <body>
                    <!-- Ronds qui montent -->
                    <div class="bubbles">
                        <div class="bubble"></div>
                        <div class="bubble"></div>
                        <div class="bubble"></div>
                        <div class="bubble"></div>
                    </div>

                    <!-- Cercle de chargement -->
                    <div class="loader">
                        <img src="./logo.jpg" alt="Logo">
                    </div>
                </body>
                </html>
            `);

            // Après 3 secondes, rediriger vers la page suivante
            setTimeout(function() {
                loadingPage.location.href = "./music.php"; // Remplace par la page cible
            }, 3000); // 3000 millisecondes = 3 secondes
        };