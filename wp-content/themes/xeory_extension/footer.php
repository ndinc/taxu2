<footer id="footer">
  <div class="footer-01">
    <div class="wrap">
    
      <div id="footer-brand-area" class="row">
        <div class="footer-logo gr6">
        <?php
        $footer_logo = get_option('footer-logo');
          if( isset($footer_logo) && $footer_logo !== '' ){
        ?>
          <img src="<?php echo get_option('footer-logo');?>" alt="<?php echo get_bloginfo('name'); ?>" />
        <?php
          }else{
            echo get_bloginfo('name');
          }
        ?>
        </div>
        <div class="footer-address gr6">
          <?php echo get_option('footer-address');?>
        </div>
      </div><!-- /footer-brand-area -->
    
      <div id="footer-content-area" class="row">
        <div id="footer-list-area" class="gr6">
          <div class="row">
          
      <?php if( has_nav_menu( 'footer_nav' ) ){ 
      //get_nav_menu_name();
        echo '<div id="footer-cont-about" class="gr4">';
        echo "<h4>" . get_option('footer_menu_title') . "</ph4>";
        wp_nav_menu(
          array(
            'theme_location'  => 'footer_nav',
            'menu_class'      => '',
            'menu_id'         => 'fnav',
            'container'       => 'nav',
            'items_wrap'      => '<ul id="footer-nav" class="%2$s">%3$s</ul>'
          )
        );
        echo '</div>';
      } //if footer_nav 
      ?>
      
    <?php if( has_nav_menu( 'global_nav' ) ){ ?>
    
            <div id="footer-cont-content" class="gr4">
              <h4>ブログコンテンツ</h4>
      <?php
        wp_nav_menu(
          array(
            'theme_location'  => 'global_nav',
            'menu_class'      => 'clearfix',
            'menu_id'         => 'footer-gnav-ul',
            'container'       => 'div',
            'container_id'    => 'footer-gnav-container',
            'container_class' => 'gnav-container'
          )
        );?>
    </div>
    <?php } ?>

            <div id="footer-cont-sns" class="gr4">
              <h4>ソーシャルメディア</h4>
              <?php footer_social_buttons();?>
            </div>
          </div>
        </div>
        <div class="gr6">
          <div class="row">
        <?php
         $facebook_page_url = get_option('facebook_page_url');
         if(isset($facebook_page_url) && $facebook_page_url !== ''){ 
        ?>
        <div id="footer-facebook" class="gr12 text-right">
          <div class="fb-like-box" data-href="<?php echo $facebook_page_url;?>" data-height="300" data-colorscheme="dark" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false" connections="100"></div>
        </div>
        <?php } ?>        
          </div>
        </div>
      </div>
      
      
      
    </div><!-- /wrap -->
  </div><!-- /footer-01 -->
  <div class="footer-02">
    <div class="wrap">
      <p class="footer-copy">
        © Copyright <?php echo date('Y'); ?> <?php echo get_bloginfo('name'); ?>. All rights reserved.
      </p>
    </div><!-- /wrap -->
  </div><!-- /footer-02 -->
  <?php
  // }
  ?>
</footer>

<a href="#" class="pagetop"><span><i class="fa fa-angle-up"></i></span></a>
<?php wp_footer(); ?>

<script>
(function($){

$(function() {
    $("#header-fnav").hide();
  $("#header-fnav-area").hover(function(){
    $("#header-fnav").fadeIn('fast');
  }, function(){
    $("#header-fnav").fadeOut('fast');
  });
});


// グローバルナビ-サブメニュー
$(function(){
  $(".sub-menu").css('display', 'none');
  $("#gnav-ul li").hover(function(){
    $(this).children('ul').fadeIn('fast');
  }, function(){
    $(this).children('ul').fadeOut('fast');
  });
});

// トップページメインビジュアル
$(function(){
  h = $(window).height();
  hp = h * .3;
  $('#main_visual').css('height', h + 'px');
  $('#main_visual .wrap').css('padding-top', hp + 'px');
});

$(function(){
	if(window.innerWidth < 768) {
  h = $(window).height();
  hp = h * .2;
  $('#main_visual').css('height', h + 'px');
  $('#main_visual .wrap').css('padding-top', hp + 'px');
	}
});

// sp-nav
$(function(){
  var header_h = $('#header').height();
  $('#gnav-sp').hide();
  $('#gnav-sp').css('top', header_h);
  $('#header-nav-btn a').click(function(){
    $('#gnav-sp').slideToggle();
    $('body').append('<p class="dummy"></p>');
  });
  $('body').on('click touchend', '.dummy', function() {
    $('#gnav-sp').slideUp();
    $('p.dummy').remove();
    return false;
  });
});

})(jQuery);

</script>


</body>
</html>