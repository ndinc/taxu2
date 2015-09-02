    <div id="footer" class="is-active">
      <div class="footer-nav js-footer_nav">
        <a href="javascript:void(0)" class="footer-nav-trigger js-footer_nav_trigger">Menu<span></span></a>
        <nav class="footer-main-nav js-footer_main_nav">
          <ul>
            <li><a href="<?php sitepath('url') ?>/">All</a></li>
            <?php  $wp_post_query = get_post_query('article', 3); ?>
            <?php  while ($wp_post_query->have_posts()) : $wp_post_query->the_post(); ?>
              <li><a href="<?php the_permalink(); ?>"><?php the_list_title() ?></a></li>
            <?php  endwhile ?>
            <li><a href="mailto:hello@ndinc.jp" data-ga="mailto">hello@ndinc.jp</a></li>
            <li><a href="http://ndinc.jp" target="_blank">http://ndinc.jp/</a></li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
  <script type="text/javascript" src="http://fast.fonts.net/jsapi/eff36716-471f-44c4-bb6b-7962cd7f417b.js"></script>

  <?php if (is_develop()): ?>
    <script type="text/javascript" src="<?php sitepath('template_url'); ?>/javascripts/vendor.js"></script>
    <script type="text/javascript" src="<?php sitepath('template_url'); ?>/javascripts/script.js"></script>
    <script src="http://localhost:35729/livereload.js"></script>
  <?php else: ?>
    <!--[if lt IE 9]>
      <script src="//cdnjs.cloudflare.com/ajax/libs/css3pie/2.0beta1/PIE_IE678.js"></script>
    <![endif]-->
    <!--[if IE 9]>
      <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/css3pie/2.0beta1/PIE_IE9.js"></script>
    <![endif]-->
    <script type="text/javascript" src="<?php sitepath('template_url'); ?>/javascripts/script.min.js"></script>
  <?php endif; ?>
  <?php include 'includes/social_script.php' ?>
  <?php if (function_exists('wp_footer')) wp_footer(); ?>
  </body>
</html>