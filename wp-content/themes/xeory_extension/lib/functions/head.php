<?php


// wp_headに表示するMETA/OGP


// タイトル
function bzb_title(){
  if( is_front_page() || is_home() ):
    $title = get_bloginfo('name');
  elseif( is_category() ):
    global $post;
      $t_id = get_category( intval( get_query_var('cat') ) )->term_id;
      $cat_class = get_category($t_id);
      $cat_option = get_option('cat_'.$t_id);
      if(isset($cat_option['bzb_meta_title']) && $cat_option['bzb_meta_title'] !== '' ){
        $title = $cat_option['bzb_meta_title'];
      }else{
        $title = $cat_class->name;
      }
  elseif( is_date() ):
    if( is_day() ):
      $title  = get_query_var( 'year' ).'年';
      $title .= get_query_var( 'monthnum' ).'月';
      $title .= get_query_var( 'day' ).'日';
    elseif( is_month() ):
      $title  = get_query_var( 'year' ).'年';
      $title .= get_query_var( 'monthnum' ).'月';
    elseif( is_year() ):
      $title  = get_query_var( 'year' ).'年';
    endif;
  elseif( is_archive() ):
    $title = wp_title('');
  else:
    $title = get_the_title();
  endif;
  echo $title;
}



function get_bzb_title(){
  if( is_category() ):
    global $post;
      $t_id = get_category( intval( get_query_var('cat') ) )->term_id;
      $cat_class = get_category($t_id);
      $cat_option = get_option('cat_'.$t_id);
      if(isset($cat_option['bzb_meta_title']) && $cat_option['bzb_meta_title'] !== '' ){
        $title = $cat_option['bzb_meta_title'];
      }else{
        $title = $cat_class->name;
      }
  else:
    $title = get_the_title();
  endif;
  return $title;
}

if(!function_exists('bzb_header_meta')){
  
add_action('wp_head', 'bzb_header_meta', 1);
function bzb_header_meta(){
  
  global $post;
  global $term_id;
  

  $keyword = '';
  $description = '';
  $title = '';
  $type = '';
  $url = '';
  $image = '';
  

  // カテゴリーディスクリプションのPを削除
  remove_filter('term_description','wpautop');

  // OGP
  // og:title / og:type / og:description
  // 
  if( is_front_page() || is_home() ) :
    
    // TOPページ / HOMEページ

    $title = get_bloginfo('title');
    $type  = 'website';
    $description = get_bloginfo('description');
    $url =  home_url()  .'/';

    $logo_image = get_option('logo_image');
    $def_image = get_option('def_image');
    if(isset($def_image)){
      $image = $def_image;
    }else{
      $image = $logo_image;
    }

    $keyword = get_option('meta_keywords');
    
  elseif( is_category() ) :

    // カテゴリーページ
  
    $t_id = get_category( intval( get_query_var('cat') ) )->term_id;

    $cat_option = get_option('cat_'.$t_id);
    if(is_array($cat_option)){
    $cat_option = array_merge(array(
      'bzb_meta_title' => '',
      'bzb_meta_keywords' => ''),$cat_option);
    }
    $title = $cat_option['bzb_meta_title'];
    $type = 'article';
    $description = esc_attr(category_description()) ;
    $url = get_category_link($t_id);
    if( isset($cat_option['bzb_category_image']) && $cat_option['bzb_category_image'] !== '' ){
      $image = $cat_option['bzb_category_image'];
    }else{
      $image = get_option('def_image');
    }
    $keyword = $cat_option['bzb_meta_keywords'];
    
  else:
    
    // その他のページ
    if(isset($post)){
      $post_meta = get_post_meta($post->ID);

      $title = get_the_title();
      $type  = 'article';
      $description = get_post_meta( $post->ID,  'bzb_meta_description', true ) ? get_post_meta( $post->ID,  'bzb_meta_description', true ) : get_the_excerpt();
      $url = get_permalink();
      if(has_post_thumbnail($post->ID)){
        $pre_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), true);
        $image =  reset( $pre_image );
      }else{
        $image = get_option('def_image');
      }
      $keyword = isset($post_meta['bzb_meta_keywords'][0]) ? $post_meta['bzb_meta_keywords'][0] : '';
    }

  endif;
  
  // META
  $meta = '';
  $meta = '<meta name="keywords" content="'.$keyword.'" />' . "\n";
  $meta .= '<meta name="description" content="'.$description.'" />' . "\n";
  $robots = "";

  if( is_front_page() || is_home() ) :
    $meta .= '';
  elseif( is_category() ) :
    if( (isset($cat_option['bzb_meta_robots'][0]) && $cat_option['bzb_meta_robots'][0] == 'noindex') && (isset($cat_option['bzb_meta_robots'][1]) && $cat_option['bzb_meta_robots'][1] == 'nofollow' )) :
      $robots = 'noindex,nofollow';
    elseif( (isset($cat_option['bzb_meta_robots'][0]) && $cat_option['bzb_meta_robots'][0] == 'noindex') && (isset($cat_option['bzb_meta_robots'][1]) && $cat_option['bzb_meta_robots'][1] == null) ) :
      $robots = 'noindex';
    elseif( (isset($cat_option['bzb_meta_robots'][0]) && $cat_option['bzb_meta_robots'][0] == null) && (isset($cat_option['bzb_meta_robots'][1]) && $cat_option['bzb_meta_robots'][1] == 'nofollow' )) :
      $robots = 'nofollow';
    else :
      $robots = 'index';
    endif;
    if(get_option('blog_public')) : 
      $meta .= '<meta name="robots" content="'.$robots.'" />' . "\n";
    endif;
  else :
    if(isset($post)){
    $post_meta = get_post_meta($post->ID);
    (isset($post_meta['bzb_meta_robots'])) ? $bzb_meta_robots_arr = unserialize($post_meta['bzb_meta_robots'][0]): '';
    if( isset($bzb_meta_robots_arr) && in_array("noindex",$bzb_meta_robots_arr) && in_array("nofollow",$bzb_meta_robots_arr)) :
      $robots = 'noindex,nofollow';
    elseif( isset($bzb_meta_robots_arr) && in_array("noindex",$bzb_meta_robots_arr)) :
      $robots = 'noindex';
    elseif( isset($bzb_meta_robots_arr) && in_array("nofollow",$bzb_meta_robots_arr)) :
      $robots = 'nofollow';
    else :
      $robots = 'index';
    endif;
    if(get_option('blog_public')) : 
      $meta .= '<meta name="robots" content="'.$robots.'" />' . "\n";
    endif;
    }
    
  endif;
  $facebook_user_id =  get_option('facebook_user_id');
  if($facebook_user_id || $facebook_user_id !== ''){
    $meta .= '<meta property="og:admins" content="'.esc_html($facebook_user_id).'" />' . "\n";  
  }

  $facebook_app_id =  get_option('facebook_app_id');
  if($facebook_app_id || $facebook_app_id !== ''){
    $meta .= '<meta property="og:app_id" content="'.esc_html($facebook_app_id).'" />' . "\n";  
  }

  // OGP
  
  $meta .= '<meta property="og:title" content="'.esc_html($title).'" />' . "\n";
  $meta .= '<meta property="og:type" content="'.esc_html($type).'" />' . "\n";
  $meta .= '<meta property="og:description" content="'.esc_textarea($description).'" />' . "\n";
  $meta .= '<meta property="og:url" content="'.esc_url($url).'" />' . "\n";
  $meta .= '<meta property="og:image" content="'.esc_url($image).'" />' . "\n";
  $meta .= '<meta property="og:locale" content="ja_JP" />' . "\n";
  $meta .= '<meta property="og:site_name" content="'.esc_html(get_bloginfo('name')).'" />' . "\n";
  $meta .= '<link href="https://plus.google.com/'. esc_html(get_option('google_publisher')) .'" rel="publisher" />' . "\n";
  
  $twitter_id = get_option("twitter_id");
  if($twitter_id || $twitter_id){
    $meta .='<meta content="summary" name="twitter:card" />' . "\n";
    $meta .= '<meta content="' .esc_html($twitter_id) . '" name="twitter:site" />'. "\n\n";
  }
    
  echo $meta;
}
}//function_exists

//ページ固有のJS（ヘッダー内）

add_action('wp_head', 'bzb_post_javascript4head', 888);

function bzb_post_javascript4head(){
  global $post;

  if( !is_object($post) ) 
        return;

  $bzb_post_asset_js4head = get_post_meta( $post->ID ,'bzb_post_asset_js4head', true);
  if( isset($bzb_post_asset_js4head) && is_array($bzb_post_asset_js4head)):
    $reset_js = $bzb_post_asset_js4head;
    $js = reset($reset_js);
  else:
    $js = $bzb_post_asset_js4head;
  endif;
  if( $js && $js !==''): 
  ?>
      <?php echo $js; ?>
  <?php
  endif;
}

// ページ固有のcss

add_action('wp_head', 'bzb_post_style', 888);

function bzb_post_style(){
  global $post;

  if( !is_object($post) ) 
        return;

  if( is_array(get_post_meta( $post->ID ,'bzb_post_asset_css'))):
    $reset_css = get_post_meta( $post->ID ,'bzb_post_asset_css');
    $css = reset($reset_css);
  else:
    $css = get_post_meta( $post->ID ,'bzb_post_asset_css');
  endif;
  if( $css && $css !==''): 
  ?>
    <style type="text/css">
      <?php echo $css; ?>
    </style>
  <?php
  endif;
}

// ページ固有のjs

add_action('wp_footer', 'bzb_post_script', 999);

function bzb_post_script(){
  global $post;

  if( !is_object($post) ) 
        return;
      
  if( is_array(get_post_meta( $post->ID,'bzb_post_asset_js'))):
    $pre_js = get_post_meta( $post->ID, 'bzb_post_asset_js');
    $js = reset($pre_js);
  else:
    $js = get_post_meta( $post->ID, 'bzb_post_asset_js');
  endif;
  if( $js && $js !=='' ): 
  ?>
      <?php echo $js; ?>
  <?php
  endif;
}
