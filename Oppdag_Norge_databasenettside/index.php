<?php
session_start();

?>



<!--Her så starter jeg en php økt og jeg gjør dette siden jeg skal passe på at
hvis det er noen som er logget inn så endrer nettsiden seg basert på at du er logget inn -->
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hovedside</title>
    <link rel="icon" type="image/x-icon" href="Databasenettside/bilder/OppdagNorgemindre.png">
    <link rel="stylesheet" href="Databasenettside/css/stylesheet.css">
</head>

<body>
    <header>
        <div class="container">
            <a href="index.php" class="logo-link"><img src="Databasenettside/bilder/OppdagNorgemindre.png" alt="Oppdag Norge" class="logo"></a>
            
            <label id="menuToggle" class="menu-toggle" for="menuToggleCheckbox">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </label>
            <input type="checkbox" id="menuToggleCheckbox" style="display: none;" />
            
            <nav>
                <ul>
                    <li><a href="fjorder.html">Fjorder</a></li>
                    <li><a href="fjell.html">Fjell</a></li>
                    <li><a href="byer.html">Byer</a></li>
                    <li><a href="om-oss.html">Om Oss</a></li>
                    <?php if (isset($_SESSION['user_email'])): ?>
                        <li><a href="Databasenettside/htmlogphp/profil.php">Profil</a></li>
                        <li><a href="databasenettside/htmlogphp/logut.php">Logg ut</a></li>
                    <?php else: ?>
                        <li><a href="Databasenettside/htmlogphp/login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section id="home" class="hero-section">
            <div class="hero-text">
                <h1>Velkommen<?php 
                    // Hvis brukeren er logget inn og har et navn lagret i session, vis navnet
                    if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])) {
                        echo ', ' . htmlspecialchars($_SESSION['user_name']);
                    }
                    else {
                        echo ''; // Hvis ingen er logget inn, vis bare "Velkommen!"
                    }
                ?>!</h1>
            </div>

            <!-- Her så sjekker php om du er logget inn og hvis du er logget inn så skriver den navnet ditt og velkommen. Den beskytter også mot xss med koden htmlspecialchars -->
        </section>

        <section id="fjorder" class="content-section">
            <div class="text right">
                <h2>Utforsk Fjorder</h2>
                <p>De norske fjordene er blant de mest spektakulære naturlige underverkene i verden. Med sine dype vann, bratte fjell og små landsbyer byr de på en magisk opplevelse.</p>
            </div>
            <img src="Databasenettside/bilder/norgefjord.jpeg" alt="Fjorder i Norge" class="content-image left">
        </section>

        <section id="fjell" class="content-section">
            <img src="Databasenettside/bilder/preikestolen.jpg" alt="Fjellene i Norge" class="content-image right">
            <div class="text left">
                <h2>Besøk Fjellene</h2>
                <p>Fjellene i Norge tilbyr utrolige turmuligheter, med alt fra Preikestolen til Galdhøpiggen. Enten du søker spenning eller stillhet, finner du det her.</p>
            </div>
        </section>

        <section id="byer" class="content-section">
            <div class="text right">
                <h2>Oppdag Byer</h2>
                <p>Utforsk livlige norske byer som Oslo, Bergen og Trondheim. Nyt kultur, historie og det moderne livet i noen av verdens vakreste byer.</p>
            </div>
            <img src="Databasenettside/bilder/drammensentrum.jpg" alt="Byer i Norge" class="content-image left">
        </section>
    </main>

    <footer>
        <div class="footer-container">
            <div class="footer-column">
                <h3>Om Oss</h3>
                <p>Oppdag Norge er dedikert til å vise frem Norges vakre landskap og rike kultur. Vi tilbyr reiseguider, tips og inspirasjon for å hjelpe deg med å oppdage alt Norge har å tilby.</p>
            </div>
            <div class="footer-column">
                <h3>Nyttige Lenker</h3>
                <ul>
                    <li><a href="fjorder.html">Fjorder</a></li>
                    <li><a href="fjell.html">Fjell</a></li>
                    <li><a href="#byer.html">Byer</a></li>
                    <li><a href="om-oss.html">Om Oss</a></li>
                    <?php if (isset($_SESSION['user_email'])): ?>
                    <li><a href="profil.php">Profil</a></li>
                    <li><a href="Databasenettside/htmlogphp/logut.php">Logg ut</a></li>
                    <?php else: ?>
                    <li><a href="Databasenettside/htmlogphp/login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Kontakt Oss</h3>
                <p>Email: kontakt@oppdagnorge.no</p>
                <p>Telefon: +47 123 45 678</p>
                <p>Adresse: Oppdag Norge AS, Fjellveien 12, 5000 Bergen</p>
            </div>
            <div class="footer-column">
                <h3>Følg Oss</h3>
                <ul class="social-icons">
                    <li><a href="https://facebook.com" target="_blank"><img src="Databasenettside/bilder/Facebooklogo.webp" alt="Facebook"></a></li>
                    <li><a href="https://instagram.com" target="_blank"><img src="Databasenettside/bilder/Instagramlogo.webp" alt="Instagram"></a></li>
                    <li><a href="https://twitter.com" target="_blank"><img src="Databasenettside/bilder/Twitterlogo.png" alt="Twitter"></a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 Oppdag Norge. Alle rettigheter reservert.</p>
        </div>
    </footer>

    <button id="backToTop" title="Tilbake til toppen">↑</button>
    
<script>
    const menuToggleCheckbox = document.getElementById('menuToggleCheckbox');
    const nav = document.querySelector('nav');

    menuToggleCheckbox.addEventListener('change', function() {
        if (this.checked) {
            nav.classList.add('active');
        } else {
            nav.classList.remove('active');
        }
    });
</script>

<script>
    const backToTopButton = document.getElementById('backToTop');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 200) {
            backToTopButton.style.display = 'block';
        } else {
            backToTopButton.style.display = 'none';
        }
    });

    backToTopButton.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
</script>

</body>
</html>
