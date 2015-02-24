<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="ja"><![endif]-->
<!--[if IE 7]>   <html class="no-js lt-ie9 lt-ie8" lang="ja"><![endif]-->
<!--[if IE 8]>   <html class="no-js lt-ie9" lang="ja"><![endif]-->
<!--[if gt IE 8]><!--><html lang="ja"><!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
  <title><?php echo get_head_meta('title'); ?></title>
  <meta name="description" content="<?php echo get_head_meta('description'); ?>">
  <meta name="keywords" content="<?php echo get_head_meta('keywords'); ?>">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <?php include "includes/ogp.php"; ?>
  <link rel="apple-touch-icon" href="<?php sitepath('template_url'); ?>/images/apple-touch-icon.png">
  <link rel="shortcut icon" type="image/x-icon" href="<?php sitepath('template_url'); ?>/images/favicon.ico">
  <link rel="stylesheet" href="<?php sitepath('template_url'); ?>/stylesheets/libs/foundation/normalize.css" type="text/css">
  <link rel="stylesheet" href="<?php sitepath('template_url'); ?>/stylesheets/libs/foundation/foundation.css" type="text/css">
  <link rel="stylesheet" href="<?php sitepath('template_url'); ?>/stylesheets/vendor.css?<?php echo get_filemtime('/stylesheets/vendor.css') ?>" type="text/css">
  <link rel="stylesheet" href="<?php sitepath('template_url'); ?>/stylesheets/style.css?<?php echo get_filemtime('/stylesheets/style.css') ?>" type="text/css">
  <?php if (is_develop()): ?>
  <?php else: ?>
  <?php endif ?>
  <link rel="stylesheet" href="<?php sitepath('template_url'); ?>/stylesheets/style.css" type="text/css">
  <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.7.1/modernizr.min.js"></script>
  <script type="text/javascript" src="http://fast.fonts.net/jsapi/d11d5c2c-3d5a-4c4e-8b4b-7c002575d3f8.js"></script>
  <!--[if lt IE 9]>
    <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.2/html5shiv.js"></script>
    <script src="//s3.amazonaws.com/nwapi/nwmatcher/nwmatcher-1.2.5-min.js"></script>
    <script src="//html5base.googlecode.com/svn-history/r38/trunk/js/selectivizr-1.0.3b.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
    <script src="<?php sitepath('template_url'); ?>/javascripts/ie/REM-unit-polyfill/rem.min.js" type="text/javascript"></script>
  <![endif]-->
  <?php if (function_exists('wp_head')) wp_head(); ?>
</head>
<body <?php echo body_class('is-loading'); ?>>
  <div id="container" class="js-pjax_container">
<!--     <div id="header">
      <div class="header-content">
        <div class="row">
          <div class="small-push-8 small-4 medium-push-10 medium-2 columns">
            <?php if (is_home()): ?>
              <h1 class="header-title"><img src="<?php sitepath('template_url'); ?>/images/nd_logo.png" alt="<?php bloginfo('name') ?> - <?php bloginfo('description') ?>"></h1>
            <?php else: ?>
              <p class="header-title"><a href="<?php sitepath('url') ?>/"><img src="<?php sitepath('template_url'); ?>/images/nd_logo.png" alt="<?php bloginfo('name') ?>"></a></h1>
            <?php endif ?>

          </div>
        </div>
      </div>
    </div> -->