<?php


//************************************************************************************************************likebox

class MyLikeBox extends WP_Widget {
  function MyLikeBox() {
      parent::WP_Widget(false, $name = 'LIKEBOX');
    }
    function widget($args, $instance) {
        extract( $args );
        $facebook_page_url = '';
        $likebox_height = '';
        $facebook_app_id = '';

        $facebook_page_url = get_option('facebook_page_url');
        $likebox_height = isset($instance['likebox_height']) ? $instance['likebox_height']  : 600;
        $colorscheme = isset($instance['colorscheme']) ? $instance['colorscheme']  : "light";
        $facebook_app_id = get_option('facebook_app_id');

        $facebook = isset($instance['facebook']) ?apply_filters( 'facebook', $instance['facebook'] ) : "";
        $twitter = isset($instance['twitter']) ? apply_filters( 'twitter', $instance['twitter'] ) : "";
        $googleplus = isset($instance['googleplus']) ? apply_filters( 'googleplus', $instance['googleplus'] ) : "";
        $feedly = isset($instance['feedly']) ? apply_filters( 'feedly', $instance['feedly'] ) : "";
        $n = 0;
        $disp_instance = array($facebook, $twitter, $googleplus, $feedly);
        foreach($disp_instance as $value){
          if($value == '') {continue;}
          $n = $n + 1;
        }//foreach
?>
<style>
#facebookLikeBox{margin:0 10px;}
#facebookLikeBox .fbcomments,
#facebookLikeBox .fb_iframe_widget,
#facebookLikeBox .fb_iframe_widget[style],
#facebookLikeBox .fb_iframe_widget iframe[style],
#facebookLikeBox .fbcomments iframe[style],
#facebookLikeBox .fb_iframe_widget span{
    width: 100% !important;
    height:<?php echo $likebox_height;?>px !important;
}
</style>
        <div class="post-share-fb side-widget">

        <?php if(isset($likebox_height) && $likebox_height !== ''){ ?>
        <div class="fb-like-box" data-href="<?php echo $facebook_page_url;?>" data-height="<?php echo $likebox_height;?>" data-colorscheme="<?php echo $colorscheme;?>" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false" connections="100"></div>
        <?php } ?>

        </div>
    <?php
    }
    
    function update($new_instance, $old_instance) {
      $instance = $old_instance;
      $instance['likebox_height'] = trim($new_instance['likebox_height']);
      $instance['colorscheme'] = trim($new_instance['colorscheme']);

      return $instance;
    }

    function form($instance) {

      $likebox_height = (isset($instance['likebox_height']) && $instance['likebox_height'] !== '') ?  esc_attr($instance['likebox_height']) : "600";
      $colorscheme = (isset($instance['colorscheme']) && $instance['colorscheme'] !== '') ?  esc_attr($instance['colorscheme']) : "light";

      ?>

        <p>
           <label for="<?php echo $this->get_field_id('likebox_height'); ?>">
           LIKEBOXの高さ:
           </label>
          <input class="widefat" id="<?php echo $this->get_field_id('likebox_height'); ?>" name="<?php echo $this->get_field_name('likebox_height'); ?>" type="text" value="<?php echo $likebox_height; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('colorscheme') . $this->number; ?>">
          暗い背景用:
          </label>
          <input class="widefat" id="<?php echo $this->get_field_id('colorscheme'); ?>" name="<?php echo $this->get_field_name('colorscheme'); ?>" type="checkbox" value="dark" <?php checked($colorscheme, "dark");?> />
        </p>
        <?php
    }//form
}
add_action('widgets_init', create_function('', 'return register_widget("MyLikeBox");'));

?>