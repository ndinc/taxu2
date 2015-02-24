<?php
global $_SINGLE_POSTS;
global $_CATEGORIES;
global $_POST_TYPES;

$_POST_TYPES = $post_types = array('article');
$_SINGLE_POSTS = array();

foreach ($post_types as $i => $post_type) {
  require( $post_type.'.php' );
  $_SINGLE_POSTS[$post_type] = $datas;
}

$_CATEGORIES = array(
  'category' => array(
    'post_type' => 'article',
    'terms' => array(
      'cat_a' => array(
        'name' => 'CAT_A'
      ),
      'cat_b' => array(
        'name' => 'CAT_B'
      ),
      'cat_c' => array(
        'name' => 'CAT_C'
      )
    )
  )
);
