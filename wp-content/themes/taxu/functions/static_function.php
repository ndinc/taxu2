<?php
function body_class($custom_class=null){
  $body_class = array(get_page_type(), get_page_file(), get_page_slug());
  if($custom_class){
    $body_class[] = $custom_class;
  }
  if($_SERVER['REQUEST_URI'] == '/'){
    $body_class[] = 'home';
  }
  $body_class = array_unique($body_class);
  return 'class="'.join(' ', $body_class).'"';
}

function get_sitepath($type = 'url'){
  $out = '';
  switch ($type) {
    case 'url':
      $out = 'http://'.$_SERVER['HTTP_HOST'];
      break;
    case 'template_url':
      $out = 'http://'.$_SERVER['HTTP_HOST'];
      break;
    case 'directory':
      $out = '';
      break;
    default:
      $out = 'http://'.$_SERVER['HTTP_HOST'];
      break;
  }
  return $out;
}

function sitepath($type = 'url'){
  echo get_sitepath($type);
}


function get_page_type(){
  $type = !empty($_GET['type'])? $_GET['type'] : 'pages';
  $type = (is_tax())? 'page-template' : $type;
  return $type;
}

function get_page_file(){
  $file = !empty($_GET['file'])? $_GET['file'] : 'index';
  $file = (is_tax())? 'taxonomy' : $file;
  return $file;
  // return !empty($_GET['file'])? $_GET['file'] : 'index';
}

function get_page_req_path(){
  $type = get_page_type();
  $file = get_page_file();
  $page_path = $type."/".$file.".php";
  // echo $page_path;
  return $page_path;
}

function get_page_path(){
  if(is_404()){
    $page_path = "pages/404.php";
  }else{
    $page_path = get_page_req_path();
  }
  if(is_single()){
    $wp_post_query = get_post_query( get_page_file() );
    $wp_post_query->the_post( get_page_slug() );
  }
  return $page_path;
}

function get_page_slug(){
  $slug = !empty($_GET['slug'])? $_GET['slug'] : get_page_file();
  if(is_404()){
    $slug = '404';
  }
  return $slug;
}

function is_404(){
  $page_path = get_page_req_path();
  return !file_exists($page_path);
}

function is_single(){
  global $_SINGLE_POSTS;
  return (!empty($_GET['type']) && $_GET['type'] == 'single' && $_SINGLE_POSTS[get_page_file()] && $_SINGLE_POSTS[get_page_file()][get_page_slug()]);
}

function is_tax(){
  global $_CATEGORIES;
  return (!empty($_GET['type']) && !empty($_GET['file']) && $_GET['type'] == 'single' && $_CATEGORIES[$_GET['file']] && $_CATEGORIES[$_GET['file']]['terms'][$_GET['slug']]);
}

function is_page(){
  global $_PAGES;
  return !empty($_PAGES[get_page_slug()]);
}

function is_home(){
  return ($_SERVER['REQUEST_URI'] == '/');
}

function get_head_meta($name=null){
  global $_SITE;
  global $_PAGES;
  global $_SINGLE_POSTS;
  if($name == 'author'){
    return $_SITE['author'];
  }

  if (is_home()) {
    $meta = array(
      'title' => $_SITE['title'],
      'url' => get_sitepath('url').'/'.get_req_path(),
      'description' => $_SITE['description'],
      'image' => $_SITE['image'],
      'type' => 'website'
    );
  }else if(is_single()){
    $p = $_SINGLE_POSTS[get_page_file()][get_page_slug()];
    $meta = array(
      'title' => $p['title'].' | '.$_SITE['title'],
      'url' => get_sitepath('url').'/'.get_req_path(),
      'description' => $p['description'],
      'image' => $p['image'],
      'type' => 'article'
    );
  }else if( is_page() ){
    $p = $_PAGES[get_page_slug()];
    $image = !empty($p['image'])? $p['image'] : $_SITE['image'];
    $desc = !empty($p['description'])? $p['description'] : $_SITE['description'];
    $meta = array(
      'title' => $p['title'].' | '.$_SITE['title'],
      'url' => get_sitepath('url').'/'.get_req_path(),
      'description' => $desc,
      'image' => $image,
      'type' => 'article'
    );
  }else{
    $meta = array(
      'title' => $_SITE['title'],
      'url' => get_sitepath('url').'/'.get_req_path(),
      'description' => $_SITE['description'],
      'image' => $_SITE['image'],
      'type' => 'article'
    );
  }
  $meta = array_merge($_SITE, $meta);
  return ( isset($name) && isset($meta[$name]) )? $meta[$name] : $meta;
}

// function get_posts($type){
//   global $_SINGLE_POSTS;
//   if($type){
//     return $_SINGLE_POSTS[$type];
//   }else{
//     return array();
//   }
// }

// function get_post($slug=null, $file=null){
//   global $_SINGLE_POSTS;
//   $file = !empty($file)? $file : get_page_file();
//   $slug = !empty($slug)? $slug : get_page_slug();
//   if($file && $slug){
//     return $_SINGLE_POSTS[$file][$slug];
//   }else{
//     return array();
//   }
// }


function get_post_query($type, $num){
  $wp_temp_query = new PostQuery($type, $num);
  return $wp_temp_query;
}

class PostQuery {
  function __construct($type, $num=-1){
    global $_SINGLE_POSTS;
    global $_CATEGORIES;
    global $post_type;

    if($_CATEGORIES && $_CATEGORIES[$type]){
      $post_type = $_CATEGORIES[$type]['post_type'];
      $slug = get_page_slug();
      // $this->posts = array_filter($_SINGLE_POSTS[$post_type], function($p) use ($type, $slug){
      //   if(is_array($p[$type])){
      //     return in_array($slug, $p[$type]);
      //   }else{
      //     return $p[$type] == $slug;
      //   }
      // });
      $this->posts = array();
      foreach ($_SINGLE_POSTS[$post_type] as $i => $p) {
        if( (is_array($p[$type]) && in_array($slug, $p[$type]) ) or $p[$type] == $slug){
          $this->posts[] = $p;
        }
      }
    }else{
      $post_type = $type;
      $this->posts = $_SINGLE_POSTS[$type];
    }
    $this->keys = array_keys($this->posts);
    $this->index = 0;
    $this->max = ($num == -1)? count($this->posts) : $num;
  }
  public function have_posts(){
    return ($this->index < $this->max);
  }
  public function the_post($slug=null){
    global $post;
    $key = !empty($slug)? $slug : $this->keys[$this->index];
    $post = $this->posts[$key];
    $post['slug'] = $key;
    $this->index = $this->index + 1;
  }
}


function get_the_title(){
  global $post;
  return $post['title'];
}

function get_the_content(){
  global $post;
  return $post['content'];
}

function get_the_post($name){
  global $post;
  return $post[$name];
}

function get_the_slug(){
  global $post;
  return $post['slug'];
}

function get_post_terms($taxonomy){
  global $_CATEGORIES;
  global $post;
  return $_CATEGORIES[$taxonomy]['terms'][$post[$taxonomy]];
}

function get_term_link($term, $taxonomy){
  return get_sitepath('url').'/'.$taxonomy.'/'.$term.'/';
}

function get_permalink(){
  global $post;
  global $post_type;
  return get_sitepath('url').'/'.$post_type.'/'.get_the_slug().'/';
}

