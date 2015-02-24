<?php


function wp_wpautop(){
  remove_filter('the_excerpt', 'wpautop');
  remove_filter('the_content', 'wpautop');
}
// add_action( 'init', 'wp_wpautop' );
// 自動整形のoff

function wp_head_adjust(){
  add_filter( 'show_admin_bar', '__return_false' );
}
add_action( 'init', 'wp_head_adjust' );
// ログイン時の管理者バーを出さない


function is_page_check(){
  global $wp_query;
  if (is_static()) {
    status_header( 200 );
    $wp_query->is_404 = false;
    $wp_query->is_static = true;
  }
  if(is_single()){
    the_post();
  }
}
add_action('template_redirect', 'is_page_check');
// ページステイタスの調整

function my_the_post_action( $post ) {
  global $wp_post_count;
  $wp_post_count++;
}
add_action( 'the_post', 'my_the_post_action' );
//$wp_post_count loopのカウンター


function custom_excerpt_length( $length ) {
  return 80;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );
// 本文抜粋の文字数

function new_excerpt_more($more) {
  return '';
}
add_filter('excerpt_more', 'new_excerpt_more');
// 本文抜粋の最後文字[...]を空に


function my_class_names($classes) {
  global $wp_query;
  // $classes[] = $locale;
  if(isset($wp_query->queried_object)){
    $uri = get_page_uri($wp_query->queried_object->ID);
    if(substr($uri,0,2) == 'en'){
      $classes[] = 'en';
    }
  }
  $filepath = get_page_slug();
  $classes[] = $filepath;
  return array_unique($classes);
}
add_filter('body_class','my_class_names');
// body classに statci file名を追加


function custom_feed_template(){
  // アクションフック リムーブ
  // remove_filter('do_feed_rdf', 'do_feed_rdf', 10);
  // remove_filter('do_feed_rss', 'do_feed_rss', 10);
  remove_filter('do_feed_rss2', 'do_feed_rss2', 10);
  // remove_filter('do_feed_atom', 'do_feed_atom', 10);

  // アクションフック 追加
  function custom_feed_rdf() {
      $template_file = '/feed/feed-rdf.php';
      load_template(get_template_directory() . $template_file);
  }
  // add_action('do_feed_rdf', 'custom_feed_rdf', 10, 1);

  function custom_feed_rss() {
      $template_file = '/feed/feed-rss.php';
      load_template(get_template_directory() . $template_file);
  }
  // add_action('do_feed_rss', 'custom_feed_rss', 10, 1);

  function custom_feed_rss2( $for_comments ) {
      $template_file = '/feed/feed-rss2' . ( $for_comments ? '-comments' : '' ) . '.php';
      load_template(get_template_directory() . $template_file);
  }
  add_action('do_feed_rss2', 'custom_feed_rss2', 10, 1);

  function custom_feed_atom( $for_comments ) {
      $template_file = '/feed/feed-atom' . ( $for_comments ? '-comments' : '' ) . '.php';
      load_template(get_template_directory() . $template_file);
  }
  // add_action('do_feed_atom', 'custom_feed_atom', 10, 1);
}
// add_action( 'init', 'custom_feed_template' );
// feedのカスタムテンプレートを使う

function myfeed_request($qv) {
  if (isset($qv['feed']))
    $qv['post_type'] = get_post_types();
  return $qv;
}
// add_filter('request', 'myfeed_request');

function remove_jquery(){
  remove_action('wp_head', 'wp_enqueue_scripts', 1);
}
add_action( 'init', 'remove_jquery' );
