<!-- sidebar.php -->
<div class="sidebar">
    <h1 class="sidebar-title">Admin Panel</h1>
    <a href="<?php echo SITE_URL; ?>/index.php" class="menu-item <?php echo ($current_page === 'dashboard') ? 'active' : ''; ?>">
        <i class="fas fa-home"></i>
        Dashboard
    </a>
    <a href="<?php echo SITE_URL; ?>/news.php" class="menu-item <?php echo ($current_page === 'actualites') ? 'active' : ''; ?>">
        <i class="fas fa-newspaper"></i>
        Actualités
    </a>
    <a href="<?php echo SITE_URL; ?>/music.php" class="menu-item <?php echo ($current_page === 'musiques') ? 'active' : ''; ?>">
        <i class="fas fa-music"></i>
        Musiques
    </a>
    <a href="<?php echo SITE_URL; ?>/videos.php" class="menu-item <?php echo ($current_page === 'videos') ? 'active' : ''; ?>">
        <i class="fas fa-video"></i>
        Videos
    </a>
    <a href="<?php echo SITE_URL; ?>/phototheques.php" class="menu-item <?php echo ($current_page === 'phototheques') ? 'active' : ''; ?>">
        <i class="fas fa-images"></i>
        Phototheques
    </a>
    <a href="<?php echo SITE_URL; ?>/cover.php" class="menu-item <?php echo ($current_page === 'cover') ? 'active' : ''; ?>">
        <i class="fas fa-cog"></i>
        Cover
    </a>
</div>