<div id="main">
  <div class="articles js-articles" data-paginate="<?php echo get_next_paging_href() ?>">
    <?php  $wp_post_query = get_post_query('article', 10); ?>
    <?php  while ($wp_post_query->have_posts()) : $wp_post_query->the_post(); ?>
      <?php include get_sitepath('directory').'includes/article.php'; ?>
    <?php  endwhile ?>
  </div>
</div>