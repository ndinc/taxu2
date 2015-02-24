<?php
function register_custom_post() {
  $args = array(
    'labels' => array(
      'name' => _x('記事', 'post type general name'),
    ),
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'query_var' => true,
    'exclude_from_search' => false,
    'rewrite' => true,
    'hierarchical' => false,
    'menu_position' => 4,
    'supports' => array('title','editor','author')
    // 'taxonomies' => array('category')
  );
  register_post_type('article', $args);

  register_taxonomy(
    'tax',
    'article',
    array(
      'hierarchical' => true,
      'update_count_callback' => '_update_post_term_count',
      'label' => '分類',
      'singular_label' => '分類',
      'public' => true,
      'rewrite' => array(
        // 'slug' => '',
        'with_front' => false,
        'hierarchical' => true
      )
    )
  );
}

// add_action('init','register_custom_post');