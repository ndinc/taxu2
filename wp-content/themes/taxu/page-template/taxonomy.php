  <div id="main">
    <div class="wrapper">
      <div class="articles">
      <?php  $wp_post_query = get_post_query('category', -1); ?>
      <?php  while ($wp_post_query->have_posts()) : $wp_post_query->the_post(); ?>
        <?php include get_sitepath('directory').'includes/article.php'; ?>
      <?php  endwhile ?>
      </div>
    </div>
  </div>
