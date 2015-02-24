<?php


//ヘッダーにおけるソーシャルボタン
function header_social_buttons(){

  $facebook_page_url = esc_url(get_option('facebook_page_url'));
  $twitter_from_db = esc_html(get_option('twitter_id'));
  $googleplus_from_db = esc_html(get_option('google_publisher'));
  $feedly_url = urlencode(get_bloginfo('rss2_url'));

  $header_social_buttons = '';

  $header_social_buttons .= '<div id="header-sns" class="sp-hide">';
  $header_social_buttons .=  '<ul>';
        if(isset($facebook_page_url) && $facebook_page_url !== ''){
          $header_social_buttons .= '<li class="facebook_icon"><a href="' . $facebook_page_url . '" target="_blank"><i class="fa fa-facebook-square"></i></li>';
        }
          if(isset($twitter_from_db) && $twitter_from_db !== ''){
          $header_social_buttons .= '<li class="twitter_icon"><a target="_blank" href="https://twitter.com/' . $twitter_from_db . '"><i class="fa fa-twitter-square"></i></a></li>';
          }
          if(isset($googleplus_from_db) && $googleplus_from_db !== ''){
          $header_social_buttons .= '<li class="google_icon"><a target="_blank" href="https://plus.google.com/' . $googleplus_from_db . '"><i class="fa fa-google-plus-square"></i></li>';
          }
          $header_social_buttons .= '<li class="feedly_icon"><a target="_blank" href="http://cloud.feedly.com/#subscription%2Ffeed%2F' . $feedly_url . '"><i class="fa fa-rss"></i></a></li>';
        $header_social_buttons .= '</ul>';
      $header_social_buttons .= '</div>';

      echo $header_social_buttons;
}

//記事上下のソーシャルボタン
function social_buttons(){

$disp_social_buttons = '';
$social_buttons = get_option('social_buttons');

if(!isset($social_buttons) || $social_buttons !== 'none'){

      $twitter_id = get_option('twitter_id');
      $page_url = get_permalink();
      $post_title = get_bzb_title();
      $page_url_encode = urlencode($page_url);
      $pid = get_the_ID();
      $social_flag = get_post_meta($pid,'post_social_buttons',true);

      if(isset($cf['bzb_meta_description'])){
        $bzb_meta_description = $cf['bzb_meta_description'][0];
      }

$disp_social_buttons .=<<<eof
  <!-- ソーシャルボタン -->
  <ul class="bzb-sns-btn">
    <li class="bzb-facebook">
      <div class="fb-like"
        data-href="{$page_url}"
        data-layout="button_count"
        data-action="like"
        data-show-faces="false"></div>
    </li>
    <li class="bzb-twitter">
      <a href="https://twitter.com/share" class="twitter-share-button" data-url="{$page_url}"  data-text="{$post_title}">Tweet</a>
      <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.async=true;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
    </li>
    <li class="bzb-googleplus">
      <div class="g-plusone" data-href="{$page_url_encode}"></div>
    </li>
    <li class="bzb-hatena">
      <a href="http://b.hatena.ne.jp/entry/{$page_url_encode}" class="hatena-bookmark-button" data-hatena-bookmark-title="{$post_title}" data-hatena-bookmark-layout="standard" data-hatena-bookmark-lang="ja" title="このエントリーをはてなブックマークに追加"><img src="//b.hatena.ne.jp/images/entry-button/button-only@2x.png" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" /></a><script type="text/javascript" src="//b.hatena.ne.jp/js/bookmark_button.js" charset="utf-8" async="async"></script>
    </li>
  </ul><!-- /bzb-sns-btns -->
eof;
  if($social_flag == 'none'){
    
  }else{
   echo $disp_social_buttons;
  }
  
}//if 


}//function

