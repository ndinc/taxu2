<?php



add_filter('body_class', 'post_layout');

function post_layout($classes){

  // レイアウトの設定
  global $post;

  $post_layout = "";
  if( is_front_page() || is_home() || is_category() || is_archive() ):
      $post_layout = get_option('post_layout');
  elseif( is_single() || is_page() || is_page_template('page-lp.php') ):
  $cf = get_post_meta($post->ID);
    if(isset($cf['bzb_post_layout']) && $cf['bzb_post_layout'] !== ''){
      if( is_array( $cf['bzb_post_layout'] ) ){
        $post_layout = reset($cf['bzb_post_layout']);
      }else{
        $post_layout = $cf['bzb_post_layout'];
      }
    }else{
      $post_layout = get_option('post_layout');
    }
  endif;
  $classes[] = esc_attr($post_layout);

  return $classes;
}

add_filter('body_class', 'color_scheme');

function color_scheme($classes){
  $color_scheme = get_option('color_scheme');
  $classes[] = $color_scheme;

  return $classes;
}

function show_facebook_block(){

$facebook_block = '';

$facebook_app_id = esc_html(get_option('facebook_app_id'));

$facebook_block=<<<EOF
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id; js.async = true;
  js.src = "//connect.facebook.net/ja_JP/sdk.js#xfbml=1&appId={$facebook_app_id}&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
EOF;
echo $facebook_block;

}

add_action('wp_footer', 'show_google_plus_block');
function show_google_plus_block(){
$google_block = '';
$google_block =<<<EOF
<script src="https://apis.google.com/js/platform.js" async defer>
  {lang: 'ja'}
</script>
EOF;
  echo $google_block;
}


// パンくず
if(!function_exists('bzb_breadcrumb')){

function bzb_breadcrumb(){
  
  global $post;
  
  $bc  = '<ul class="breadcrumb clearfix">';
  $bc .= '<li><a href="'.home_url().'"><i class="fa fa-home"></i> HOME</a> / </li>';
  if( is_search() ) :
    $bc .= '<li><i class="fa fa-search"></i> 「'.get_search_query().'」の検索結果</li>';
  elseif( is_tag() ) :
    $bc .= '<li><i class="fa fa-tag"></i> '.single_tag_title("",false).'</li>';
  elseif( is_404() ) :
    $bc .= '<li><i class="fa fa-question-circle"></i> ページが見つかりませんでした</li>';
  elseif( is_date() ) :
    $bc .= '<li><i class="fa fa-clock-o"></i> ';
    if( is_day() ):
      $bc .= get_query_var( 'year' ).'年 ';
      $bc .= get_query_var( 'monthnum' ).'月 ';
      $bc .= get_query_var( 'day' ).'日';
    elseif( is_month() ):
      $bc .= get_query_var( 'year' ).'年 ';
      $bc .= get_query_var( 'monthnum' ).'月 ';
    elseif( is_year() ):
      $bc .= get_query_var( 'year' ).'年 ';
    endif;
    $bc .= '</li>';
  elseif( is_category() ) :
    $cat = get_queried_object();
    if( $cat -> parent != 0 ):
      $ancs = array_reverse(get_ancestors( $cat->cat_ID, 'category' ));
      foreach( $ancs as $anc ):
        $bc .= '<li><a href="'.get_category_link($anc).'"><i class="fa fa-folder"></i> '.get_cat_name($anc).'</a> / </li>';
      endforeach;
    endif;
    $bc .= '<li><i class="fa fa-folder"></i> '.$cat->cat_name.'</li>';
  elseif( is_author() ) :
    $bc .= '<li><i class="fa fa-user"></i> '.get_the_author_meta('display_name').'</li>';
  elseif( is_page() ) :
    if( $post->post_parent != 0 ):
      $ancs = array_reverse( $post->ancestors );
      foreach( $ancs as $anc ):
        $bc .= '<li><a href="'.get_permalink( $anc ).'"><i class="fa fa-file"></i> '.get_the_title($anc).'</a> / </li>';
      endforeach;
    endif;
    $bc .= '<li><i class="fa fa-file"></i> '.$post->post_title.'</li>';
  elseif( is_attachment() ) :
    if( $post->post_parent != 0 ):
      $bc .= '<li><a href="'.get_permalink( $post->post_parent ).'"><i class="fa fa-file-text"></i> '.get_the_title( $post->post_parent ).'</a> / </li>';
    endif;
    $bc .= '<li><i class="fa fa-picture-o"></i> '.$post->post_title.'</li>';
  elseif( is_single() ) :
    if ('contents'==get_post_type()) :
      $cats = get_the_terms( $post->ID , 'contents-cat');
    foreach($cats as $cat_term){
      $ancestors = get_ancestors( $cat_term->term_id, 'contents-cat' );
      echo get_category_parents(7);
    }

    $current_cat = '';
    if(isset($cats->parent) && $cats->parent != 0)://親カテゴリがある場合の処理

        $ancs = get_ancestors( $cats->term_id, 'contents-cat' );//print_r($anc);
        foreach( $ancs as $anc ):
          $bc .= '<li><a href="'.get_category_link( $anc ).'"><i class="fa fa-folder"></i> '.get_cat_name($anc).'</a> / </li>'; 
        endforeach;

    else:

      foreach ( $cats as $cat_term ) {
          if ( ! $current_cat || cat_is_ancestor_of( $current_cat, $cat_term ) ) {
              $current_cat = $cat_term->name;
              $current_term_link = get_term_link($cat_term->term_id, 'contents-cat');
          }
      }

    endif;


      $bc .= '<li><a href="'. $current_term_link.'"><i class="fa fa-folder"></i> '.$current_cat.'</a> / </li>';
      $bc .= '<li><i class="fa fa-file-text"></i> '. $post->post_title.'</li>';  
    else:
      $cats = get_the_category( $post->ID );//print_r($cats);
      $cat = $cats[0];
      if( $cat->parent != 0 ):
        $ancs = array_reverse(get_ancestors( $cat->cat_ID, 'category' ));
        foreach( $ancs as $anc ):
          $bc .= '<li><a href="'.get_category_link( $anc ).'"><i class="fa fa-folder"></i> '.get_cat_name($anc).'</a> / </li>'; 
        endforeach;
      endif;
      $bc .= '<li><a href="'.get_category_link( $cat->cat_ID ).'"><i class="fa fa-folder"></i> '.$cat->cat_name.'</a> / </li>';
      $bc .= '<li><i class="fa fa-file-text"></i> '.$post->post_title.'</li>';

    endif;
  else:

  endif;
  
  $bc .= '</ul>';
  
  echo $bc;

}

}

// レイアウト

function bzb_layout_main(){
  global $post;

  if( !is_object($post) ) 
        return;

  $cf = get_post_meta($post->ID);
  $main_layout = '';
  $post_layout = '';

  if(isset( $cf['bzb_post_layout'] )){
    if( is_array( $cf['bzb_post_layout'] ) ){
      $post_layout = reset($cf['bzb_post_layout']);
    }else{
      $post_layout = $cf['bzb_post_layout'];
    }
  }
  $post_layout = get_option('post_layout');
  if( is_single() || is_page() ) :
    if( "right-content" == $post_layout ) :
      $main_layout = "col-md-8  col-md-push-4";
    elseif( "one-column" == $post_layout) :
      $main_layout = "col-md-10 col-md-offset-1";
    else:
      $main_layout = "col-md-8";
    endif;
  elseif( "right-content" == $post_layout ) :
    $main_layout = "col-md-8  col-md-push-4";
  elseif( "one-column" == $post_layout ) :
    $main_layout = "col-md-10 col-md-offset-1";
  else :
    $main_layout = "col-md-8";
  endif;
  
  echo 'class="'.esc_attr($main_layout).'"';
}

function bzb_layout_side(){
  global $post;

  if( !is_object($post) ) 
        return;
      
  $cf = get_post_meta($post->ID);
  $post_layout = '';

  if(isset($cf['bzb_post_layout'])){
    if( is_array( $cf['bzb_post_layout'] ) ){
      $post_layout = reset($cf['bzb_post_layout']);
    }else{
      $post_layout = $cf['bzb_post_layout'];
    }
  }
  $bzb_option = get_option('bzb_option');
  if( is_single() || is_page() ) :
    if( "right-content" == $post_layout ) :
      $side_layout = "col-md-4 col-md-pull-8";
    elseif( "one-column" == $post_layout) :
      $side_layout = "display-none";
    else:
      $side_layout = "col-md-4";
    endif;
  elseif( "right-content" == $bzb_option['post_layout'] ) :
    $side_layout = "col-md-4 col-md-pull-8";
  elseif( "one-column" == $bzb_option['post_layout'] ) :
    $side_layout = "display-none";
  else :
    $side_layout = "col-md-4";
  endif;
  
  echo 'class="'.esc_attr($side_layout).'"';
}

function bzb_layout_side_lp(){
  global $post;
  $cf = get_post_meta($post->ID);
  $post_layout = "";
  if(isset($cf['bzb_post_layout'])){
    if( is_array( $cf['bzb_post_layout'] ) ){
      $post_layout = reset($cf['bzb_post_layout']);
    }else{
      $post_layout = $cf['bzb_post_layout'];
    }
  }
    if( "right-content" == $post_layout ) :
      $side_layout = "col-md-4 col-md-pull-8";
    elseif( "one-column" == $post_layout) :
      $side_layout = "display-none";
    else:
      $side_layout = "col-md-4";
    endif;
  
  echo 'class="'.esc_attr($side_layout).'"';
}





function get_cta($pid = ""){
  global $post;
  $check_cta = '';

  $bzb_cta = get_post_meta($post->ID, 'bzb_cta', true); 
  if(is_array($bzb_cta)){
    extract($bzb_cta);
  } 
  //print_r($bzb_cta);

  if( 'none' == $check_cta || '' == $check_cta ) {
    return false;
    //nothing
  }elseif($check_cta == 'custompost'){
    $cp_id =  $cta_select;
    $bzb_cta = get_post_meta($cp_id, 'bzb_cta', true); 
    extract($bzb_cta);//select_button,select_button_url

      $customposts = get_post($cp_id);

      $bzb_cta['title'] = ($customposts->post_title);
      $bzb_cta['content'] = nl2br($customposts->post_content);
      $bzb_cta['button_text'] = ($select_button);
      $bzb_cta['button_url'] = esc_url($select_button_url);

      $thumbnail_id = get_post_thumbnail_id($cp_id);
      $image = wp_get_attachment_image_src( $thumbnail_id, 'medium' );
      $src = $image[0];
      $width = $image[1];
      $height = $image[2];

      $bzb_cta['image'] = '<img src="' . $src . '" width="' . $width . '" height="' . $height . '">';

  }elseif($check_cta == 'pageorg'){//オリジナルはエスケープ処理を入れている
      

          $cta_title = ($org_title);
          $bzb_cta['title'] = esc_html($cta_title);
           //$bzb_cta['content'] = esc_html(reset($cf['bzb_cta_org_content']));
          $bzb_cta['content'] = $org_content;
          $bzb_cta['image'] = '<img src="' . esc_url($org_image) . '">';
          $bzb_cta['button_text'] = ($org_button_text);
          $bzb_cta['button_url'] = esc_url($org_button_url);
  }//if

  //print_r($bzb_cta);
  if(isset($bzb_cta['title']) && $bzb_cta['title'] !== '' && isset($bzb_cta['content']) && $bzb_cta['content'] !== ''  && isset($bzb_cta['image']) && $bzb_cta['image'] !== '<img src="">' ){
    make_cta_block($bzb_cta);
  }

}//func




function make_cta_block($bzb_cta){

$title = '';
$cta_content = '';
$title = (isset($bzb_cta['title']) && $bzb_cta['title'] !== '') ? $bzb_cta['title'] : "";
$cta_content = (isset($bzb_cta['content']) && $bzb_cta['content'] !== '') ? $bzb_cta['content'] : ""; 
$button_text = (isset($bzb_cta['button_text']) && $bzb_cta['button_text'] !== '') ? $bzb_cta['button_text'] : ""; 
$button_url = (isset($bzb_cta['button_url']) && $bzb_cta['button_url'] !== '') ? $bzb_cta['button_url'] : ""; 
$image = (isset($bzb_cta['image']) && $bzb_cta['image'] !== '') ? $bzb_cta['image'] : "";

$source_html=<<<eof
<!-- CTA BLOCK -->
<div class="post-cta">
<h4 class="cta-post-title">{$title}</h4>
<div class="post-cta-inner">
  <div class="cta-post-content clearfix">
  
    
    <div class="post-cta-cont">
    <div class="post-cta-img">{$image}</div>
      {$cta_content}
      <br clear="both">
      <p class="post-cta-btn"><a class="button" href="{$button_url}">{$button_text}</a></p>
    </div>
  
  </div>
</div>
</div>
<!-- END OF CTA BLOCK -->
eof;

  echo $source_html;


}//func

/* 固定ページと記事ページのprev/nextの削除 */
remove_action('wp_head','adjacent_posts_rel_link_wp_head',10);

// 一番最初の投稿にクラスを付与
function bzb_firstpost($class) {
	global $post, $posts;
	if (  is_home() && !is_paged() && ($post == $posts[0]) ||
	      is_category() && !is_paged() && ($post == $posts[0]) ||
	      is_archive() && !is_paged() && ($post == $posts[0]) ||
	      is_tag() && !is_paged() && ($post == $posts[0]) ) $class[] = 'firstpost';
	return $class;
}
add_filter('post_class', 'bzb_firstpost');

// 最初の投稿の条件分岐
function is_bzb_firstpost(){
    global $wp_query;
    return ($wp_query->current_post === 0);
}


function get_nav_menu_name(){

global $wpdb;
//このSQLはテーブル名一覧を返却するSQLです。
$sql = "SELECT distinct(A.name) FROM (" . $wpdb->prefix . "terms A left join " . $xpdb->prefix . "term_relationships B on A.term_id = B.term_taxonomy_id) left join xeory_posts C ON B.object_id = C.ID WHERE post_type = 'nav_menu_item';";

$results = $wpdb->get_results($sql);

$menu_title = object2array($results);
echo $menu_title[0]['name'];

}


function object2array($data)
{
  if (is_object($data)) {
    $data = (array)$data;
  }

  if (is_array($data)) {
    foreach ($data as $key => $value) {
      $key1 = (string)$key;
      $key2 = preg_replace('/\W/', ':', $key1);

      if (is_object($value) or is_array($value)) {
        $data[$key2] = object2array($value);
      } else {
        $data[$key2] = (string)$value;
      }

      if ($key1 != $key2) {
        unset($data[$key1]);
      }
    }
  }

  return $data;
}

function bzb_category_title(){
    global $post;
    
      $t_id = get_category( intval( get_query_var('cat') ) )->term_id;
      $cat_class = get_category($t_id);
      $cat_option = get_option('cat_'.$t_id);

      if(isset($cat_option['bzb_meta_title']) && $cat_option['bzb_meta_title'] !== '' ){
        $category_title = $cat_option['bzb_meta_title'];
      }else{
        $category_title = $cat_class->name;
      }
  echo esc_html($category_title);
}

function bzb_category_description(){
    global $post;
    
      $t_id = get_category( intval( get_query_var('cat') ) )->term_id;
      $cat_class = get_category($t_id);
      $cat_option = get_option('cat_'.$t_id);
      
      if(is_array($cat_option)){
        $cat_option = array_merge(array('cont'=>''),$cat_option);
      }
      
      echo $content = apply_filters( 'the_content', stripslashes($cat_option['bzb_meta_content']), 10 );
}

/* 抜粋
----------------------------------------------- */

function bzb_excerpt($length) {
  global $post;
  $content = mb_substr(strip_tags($post->post_excerpt),0,$length);
   
  if(!$content){
    $content =  $post->post_content;
    $content =  strip_shortcodes($content);
    $content =  strip_tags($content);
    $content =  str_replace("&nbsp;","",$content); 
    $content =  html_entity_decode($content,ENT_QUOTES,"UTF-8");
    $content =  mb_substr($content,0,$length);
  }
  return $content;
}
