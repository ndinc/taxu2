<?php

function get_wp_query ($args=null){
  global $wp_query;
  global $wp_post_count;
  $wp_post_count = -1;
  if(!$args) return $wp_query;
  $wp_temp_query = new WP_Query();
  $wp_temp_query->query($args);
  return $wp_temp_query;
}

function set_theme_locale(){
  session_start();
  global $locale;
  if (!isset($_SESSION['locale'])) {
    $_SESSION['locale'] = $locale;
  } else if (isset( $_GET['locale']) ){
    $locale = $_GET['locale'];
    $_SESSION['locale'] = $_GET['locale'];
  }
}

function get_page_slug(){
  global $post;
  $slug = is_home()? 'index' : get_query_var('name');
  return $slug;
}

function get_page_path(){
  global $wp_query;
  $page_path;
  if(is_home()){
    $page_path = 'pages/index.php';
  }else if(is_single()){
    $page_path = get_template_directory().'/single/'.get_post_type().'.php';
    if (!file_exists($page_path)){
      $page_path = get_template_directory().'/single/article.php';
    }
  }else if(is_page()){
    $p = get_post($wp_query->queried_object->ID);
    $slugs = explode('/', get_page_uri($p->ID));
    $file_name = array_pop($slugs);
    $page_path = get_template_directory().'/pages/'.$file_name.'.php';

    foreach ($slugs as $i => $slug) {
      $path = get_template_directory().'/pages/'.join('/',$slugs).'/'.$file_name.'.php';
      if(file_exists($path)){
        $page_path = $path;
        break;
      }
      array_pop($slugs);
    }
  }else if(is_static()){
    $page_path = 'pages/' . get_query_var('name') . '.php';
  }else if(is_tax()){
    $page_path = get_template_directory().'/page-template/taxonomy.php';
    if (!file_exists($page_path)){
      $page_path = 'pages/index.php';
    }
  }else if(is_404()){
    $page_path = "pages/404.php";
  }else{
    $page_path = "pages/index.php";
  }
  return $page_path;
}

function get_sitepath($type = 'url'){
  return ($type == 'directory')? get_template_directory().'/' : get_bloginfo($type);
}

function sitepath($type = 'url'){
  echo get_sitepath($type);
}

function is_static(){
  $page_path = get_static_file_path();
  return file_exists($page_path);
}

function get_static_file_path(){
  $filepath = get_req_path();
  $page_path = get_template_directory(). '/pages/' . $filepath . '.php';
  return $page_path;
}

function is_subpage() {
  global $post;                                 // load details about this page
    if ( is_page() && $post->post_parent ) {      // test to see if the page has a parent
           $parentID = $post->post_parent;

                   // the ID of the parent is this
           return $parentID;                      // return the ID
    } else {                                      // there is no parent so...
           return false;                          // ...the answer to the question is false
    };
};

function get_head_meta($name=null){
  global $post;
  global $_SITE;
  global $_PAGES;
  if($name == 'author'){
    return $_SITE['author'];
  }
  if (is_home()) {
    $meta = array(
      'title' => strip_tags(isset($_SITE['title'])? $_SITE['title'] : get_bloginfo('name')),
      'url' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
      'description' => strip_tags( isset($_SITE['description'])? $_SITE['description'] : get_bloginfo('description') ),
      'image' => $_SITE['image'],
      'site_name' => $_SITE['site_name'],
      'type' => 'website'
    );
  }else if(is_single()){
    $title = get_the_title();
    $desc = get_post_main_desc($post->ID);
    $image = get_post_main_image($post->ID);
    $meta = array(
      'title' => strip_tags( (isset($title)? $title.' | ' : ''  ).$_SITE['title'] ),
      'url' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
      'description' => str_replace(array("\r\n","\n","\r"), '', strip_tags( ( isset($desc)? $desc : $_SITE['description']  )) ),
      'image' => isset($image)? $image : $_SITE['image'],
      'site_name' => $_SITE['site_name'],
      'type' => 'article'
    );
  }else if( (is_static() or is_page()) and isset($_PAGES[get_page_slug()]) ){
    $p = $_PAGES[get_page_slug()];
    $image = !empty($p['image'])? $p['image'] : $_SITE['image'];
    $desc = !empty($p['description'])? $p['description'] : $_SITE['description'];
    $meta = array(
      'title' => $p['title'].' | '.$_SITE['title'],
      'url' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
      'description' => $desc,
      'image' => $image,
      'site_name' => $_SITE['site_name'],
      'type' => 'article'
    );
  }else if(is_page()){
    $title = get_the_title();
    $desc = get_the_excerpt();
    $image = get_post_main_image($post->ID);
    $meta = array(
      'title' => ( !empty($title)? $title.' | ' : '' ).$_SITE['title'],
      'url' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
      'description' => ( !empty($desc)? $desc : $_SITE['description']  ),
      'image' => isset($image)? $image : $_SITE['image'],
      'site_name' => $_SITE['site_name'],
      'type' => 'article'
    );
  }else if(is_tax()){
    $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
    $image = (function_exists('get_field'))? get_field('term_thumb', $term) : null;
    $meta = array(
      'title' => (!empty($term)? $term->name.' | ' : '' ).$_SITE['title'],
      'url' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
      'description' => !empty($term)? $term->description : $_SITE['description'],
      'image' => !empty($image)? $image['url'] : $_SITE['image'],
      'site_name' => $_SITE['site_name'],
      'type' => 'article'
    );
  }else{
    $meta = array(
      'title' => $_SITE['title'],
      'url' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
      'description' => $_SITE['description'],
      'image' => $_SITE['image'],
      'site_name' => $_SITE['site_name'],
      'type' => 'article'
    );
  }
  $meta = array_merge($_SITE, $meta);
  return ( isset($name) && isset($meta[$name]) )? $meta[$name] : $meta;
}


function get_post_main_desc($post_id){
  $desc = null;
  $desc = get_the_excerpt();
  return apply_filters('get_post_main_desc', $desc);
;
}

function get_post_main_image($post_id, $size='full'){
  $src = null;
  $main_thumb_id = get_post_meta($post_id, 'main_thumb', true);
  $eyecatch_thumb_id = get_post_thumbnail_id($post_id);
  if($main_thumb_id && is_numeric($main_thumb_id)){
    $image = wp_get_attachment_image_src( $main_thumb_id, $size );
    $src = $image[0];
  }else if($eyecatch_thumb_id){
    $image = wp_get_attachment_image_src( $eyecatch_thumb_id, $size );
    $src = $image[0];
  }else{
    global $post;
    $src = get_the_post_image($post->ID, $size);
  }
  return apply_filters('get_post_main_image', $src);
}

function page_navi($my_query) {
  global $paged;
  global $wp_rewrite;
  $paginate_base = get_pagenum_link(1);
  if (strpos($paginate_base, '?') || ! $wp_rewrite->using_permalinks()) {
      $paginate_format = '';
      $paginate_base = add_query_arg('paged', '%#%');
  } else {
      $paginate_format = (substr($paginate_base, -1 ,1) == '/' ? '' : '/') .
      user_trailingslashit('page/%#%/', 'paged');;
      $paginate_base .= '%_%';
  }
  echo paginate_links( array(
      'base' => $paginate_base,
      'format' => $paginate_format,
      'total' => $my_query->max_num_pages,
      'mid_size' => 5,
      'current' => ($paged ? $paged : 1),
      'prev_text'    => __('Previous'),
      'next_text'    => __('Next '),
  ));
}

function get_post_terms(){

}

function the_post_image($post_id, $size, $order=0) {
  echo get_the_post_image($post_id, $size, $order, $max);
}

function get_the_post_image($post_id, $size, $order=0) {
  if (!is_array($size) and is_numeric($size)) {
    $size = array($size, $size);
  }
  $attachments = get_posts(array(
   'post_type' => 'attachment',
   'numberposts' => 1,
   'post_status' => null,
   'post_parent' => $post_id
  ));
  if (empty($attachments) ) return null;
  $attachment = $attachments[$order];
  $attachment_image = wp_get_attachment_image_src($attachment->ID, $size);
  return $attachment_image[0];
}

function get_the_crop_image($url, $crop, $size){
  $url = urlencode($url);
  $output = get_bloginfo('template_url').'/thumbnail.php?image='.$url.'&crop='.$crop.'&size='.$size;
  return $output;
}

function get_next_paging_href($wp_post_query=null){
  global $paged;
  global $wp_query;

  if (empty($paged)){
    $paged = 1;
  }
  if(empty($wp_post_query)){
    $wp_post_query = $wp_query;
  }
  $next_paged = $paged + 1;
  return ($wp_post_query->max_num_pages >= $next_paged)? get_pagenum_link($next_paged) : '';
}

function get_prev_paging_href($wp_post_query){
  global $paged;
  global $wp_query;

  if (empty($paged)){
    $paged = 1;
  }
  if(empty($wp_post_query)){
    $wp_post_query = $wp_query;
  }
  $next_paged = $paged - 1;
  return (0 < $next_paged)? get_pagenum_link($next_paged) : '';
}

