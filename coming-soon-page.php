<?php
header('HTTP/1.1 503 Service Temporarily Unavailable');
header('Status: 503 Service Temporarily Unavailable');
header('Retry-After: 3600');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
   <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title><?php echo get_option('aio_cmp_settings')['title']; ?> - Coming Soon</title>
    <?php wp_head(); ?>
    <style>
    /* Include the star CSS from original code */
    <?php include AIO_CMP_PATH . 'styles.css'; ?>
    </style>
    
</head>
<body>
    <div class="overlay"></div>
    <div class="stars" aria-hidden="true"></div>
    <div class="starts2" aria-hidden="true"></div>
    <div class="stars3" aria-hidden="true"></div>
    <main class="main">
        <section class="contact">
            <h1 class="title"><?php echo get_option('aio_cmp_settings')['title']; ?></h1>
            <h2 class="sub-title"><?php echo get_option('aio_cmp_settings')['subtitle']; ?></h2>
        </section>
    </main>
    <footer style="position: fixed; bottom: 20px; width: 100%; text-align: center; color: #fff;">
        Powered by <a href="https://allinoneholdings.com" style="color: #fff;">AllInOneHoldings</a>
    </footer>
</body>
</html>