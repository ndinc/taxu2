        <article class="article<?php echo ($wp_post_count == 0)? ' is-active' : ''; ?>">
          <div class="row">
            <div class="small-12 medium-12 large-8 large-push-1 columns">
              <header class="entry-header">
                <p class="entry-category">
                  <?php $terms = wp_get_post_terms($post->ID, 'category'); ?>
                  <?php foreach ($terms as $i => $term): ?>
                    <span><?php echo $term->name; ?></span>
                  <?php endforeach ?>
                </p>
                <h1><a href="<?php the_permalink() ?>"><?php echo get_the_title(); ?></a></h1>
                <p class="entry-meta"><time class="date"><?php echo get_post_time('M d, Y') ?></time> - <span class="author"><?php the_author() ?></span></p>
              </header>
              <div class="entry-content wp-content">
                <?php the_content(); ?>
              </div>
              <footer class="entry-footer">
              </footer>
            </div>
          </div>
        </article>