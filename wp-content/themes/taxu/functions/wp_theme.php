<?php

function get_post_query($type, $num=10){
  global $paged;
  switch($type){

    case 'article':
      return get_wp_query(array(
        'post_type' => 'post',
        'posts_per_page' => $num,
        'paged'=> $paged,
      ));
      break;
    default:
      return get_wp_query();
  }
}

function the_list_title(){
  echo strip_tags(get_the_title());
}
// <?php $wp_post_query = get_post_query('post', -1);
// <?php while ($wp_post_query->have_posts()) : $wp_post_query->the_post();
// <?php endwhile