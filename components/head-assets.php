<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700&family=Source+Sans+3:wght@400;500;600&display=swap" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700&family=Source+Sans+3:wght@400;500;600&display=swap"></noscript>
<link rel="preload" as="style" href="/assets/css/main.css?v=20260920d">
<link rel="stylesheet" href="/assets/css/main.css?v=20260920d">
<?php
$baoOgImage = 'https://www.baopredictions.com/assets/img/og-default.jpg';
if (function_exists('bao_env')) {
    $customOg = bao_env('BAO_OG_IMAGE', '');
    if (is_string($customOg) && $customOg !== '') {
        $baoOgImage = $customOg;
    }
}
?>
<meta property="og:image" content="<?php echo htmlspecialchars($baoOgImage, ENT_QUOTES, 'UTF-8'); ?>">
<meta property="og:image:secure_url" content="<?php echo htmlspecialchars($baoOgImage, ENT_QUOTES, 'UTF-8'); ?>">
<meta property="og:image:type" content="image/jpeg">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="Bao Predictions football predictions and free betting tips">
<meta name="twitter:image" content="<?php echo htmlspecialchars($baoOgImage, ENT_QUOTES, 'UTF-8'); ?>">
<meta name="twitter:image:alt" content="Bao Predictions football predictions and free betting tips">
