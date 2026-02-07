<?php 
session_start();
$char_images = [
    'jonathan.jpg' => 'Jonathan Joestar',
    'joseph.jpg' => 'Joseph Joestar',
    'jotaro.jpg' => 'Jotaro Kujo',
    'josuke.jpg' => 'Josuke Higashikata',
];

$info_cards = [
    ['title' => 'Quote', 'content' => '"Yare Yare Daze..."'],
    ['title' => 'Studio', 'content' => 'David Production'],
    ['title' => 'Genre', 'content' => 'Action, Supernatural'],
    ['title' => 'Status', 'content' => 'Ongoing'],
    ['title' => 'Creator', 'content' => 'Hirohiko Araki'],
    ['title' => 'Rating', 'content' => '8.5/10 (MAL)'],
    ['title' => 'Year', 'content' => '2012 - Present'],
    ['title' => 'Episodes', 'content' => '190+ Episodes'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <?php include 'header.php'; ?> 
    <div class="container">
    <div class="hero-banner">
        <div class="slider-container">
            <img src="banner1.jpg" alt="Jojo Banner 1" class="slider-item">
            <img src="banner2.jpg" alt="Jojo Banner 2" class="slider-item active">
            <img src="banner3.jpg" alt="Jojo Banner 3" class="slider-item">
            <img src="banner4.jpg" alt="Jojo Banner 4" class="slider-item">
            <img src="banner5.jpg" alt="Jojo Banner 5" class="slider-item">
        </div>
    </div>  
                    
        <br><br><h2 class="section-title1">Sinopsis</h2>
        <p class="sinopsis-text">
            JoJo's Bizarre Adventure adalah serial manga dan anime epik karya Hirohiko Araki yang mengikuti keluarga Joestar lintas generasi dalam petualangan supernatural mereka melawan kekuatan jahat. Setiap bagian berfokus pada anggota keluarga yang berbeda (semua dijuluki "JoJo") di era waktu yang berbeda , dimulai dari Jonathan Joestar di Inggris Victoria melawan vampir Dio Brando, berlanjut ke cucu-cucunya yang menggunakan kekuatan "Stand" (manifestasi energi spiritual), hingga keturunan modern yang menghadapi berbagai ancaman dari organisasi kriminal, pembunuh berantai, hingga musuh yang dapat memanipulasi waktu. Serial ini terkenal dengan pose-pose ikoniknya, kemampuan Stand yang kreatif dan unik, referensi musik rock/pop Barat, dan gaya seni yang sangat khas dengan pertarungan yang lebih mengandalkan strategi dan kecerdikan daripada kekuatan mentah.
        </p>

        <h2 class="section-title2">Character</h2>
        <div class="character-grid">
            <?php foreach ($char_images as $file => $name): ?>
            <div class="char-card">
                <img src="<?= $file ?>" alt="<?= $name ?>" class="char-img"> 
                <span class="char-name"><?= $name ?></span>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="info-cards-grid">
            <?php foreach ($info_cards as $card): ?>
            <div class="info-card">
                <h4><?= htmlspecialchars($card['title']) ?></h4>
                <p><?= htmlspecialchars($card['content']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slides = document.querySelectorAll('.slider-item');
            let currentSlide = 0;

            function showSlide(index) {
                slides.forEach(slide => {
                    slide.classList.remove('active');
                });
                slides[index].classList.add('active');
            }

            function nextSlide() {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            }
            setInterval(nextSlide, 5000); 
            showSlide(currentSlide);
        });
    </script>
</body>
</html>