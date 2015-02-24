<?php
  require( 'functions/common_function.php' );

  if(is_wp()){
    require( 'functions/wp_admin.php' );
    require( 'functions/wp_hook.php' );
    require( 'functions/wp_custom_post.php' );
    require( 'functions/wp_function.php' );
    require( 'functions/wp_theme.php' );
  }else{
    require( 'functions/static_function.php' );
    require( 'functions/static_theme.php' );
  }

