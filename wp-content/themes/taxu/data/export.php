<?php
  global $_PAGES;
  global $_SITE;
  header( 'Content-Type: application/json' );
  $output = array();
  $output['site_name'] = $_SITE['site_name'];
  $static_page_list = array();

  $static_page_list[] = array(
    'title' => 'トップページ',
    'slug' => 'index',
    'link' => get_sitepath('url')
  );

  if(is_wp()){

    // start static page

    $custom_posts = get_posts(array(
      'post_type' => 'page',
      'posts_per_page' => -1
    ));


    foreach ($custom_posts as $i => $p) {
      $static_page_list[] = array(
        'title' => $p->post_title,
        'slug' => $p->post_name,
        'link' => get_permalink( $p->ID )
      );
    }

    $handle = opendir(get_template_directory().'/pages/');
    $static_pages = array();
    while (false !== ($file = readdir($handle))) {
      if($file !== '.' && $file !== '..'){
        $slug = str_replace('.php', '', $file);
        foreach ($static_page_list as $page) {
          if ($page['slug'] == $slug || $slug == '404') goto escape_loop;
        }
        $static_page_list[] = array(
          'title' => !empty($_PAGES[$slug])? $_PAGES[$slug]['title'] : $slug,
          'slug' => $slug,
          'link' => get_sitepath('url').'/'.$slug. '/'
        );
        escape_loop:
      }
    }
    $static_page_list[] = array(
      'title' => !empty($_PAGES['404'])? $_PAGES['404']['title'] : '404',
      'slug' => '404',
      'link' => get_sitepath('url').'/abcdefgh/'
    );
    $output['list']['page'] = $static_page_list;
    // end static page

    // start taxonomy page
    $taxonomy_page_list = array();

    $taxonomies = get_taxonomies('','names');
    $exclude_taxonomy = array('link_category','post_format','nav_menu');
    foreach ($exclude_taxonomy as $taxonomy) {
      unset($taxonomies[ $taxonomy ]);
    }
    foreach ($taxonomies as $taxonomy) {
      $terms = get_terms($taxonomy, array('hide_empty'=>false));
      foreach ($terms as $term) {
        if($term->name !== '未分類')
        $taxonomy_page_list[$taxonomy][] = array(
          'title' => $term->name,
          'slug' => $term->slug,
          'link' => get_term_link( $term, $taxonomy )
        );
      }

    }
    $output['list']['taxonomy'] = $taxonomy_page_list;

    // end taxonomy page

    // start single page
    $single_page_list = array();

    $post_types = get_post_types( '', 'names' );
    $exclude_post_types = array('attachment','revision','nav_menu_item','acf', 'page');
    foreach ($exclude_post_types as $post_type) {
      unset($post_types[ $post_type ]);
    }

    foreach ($post_types as $key => $post_type) {
      $single_page_list[$post_type] = array();
      $custom_posts = get_posts(array(
        'post_type' => $post_type,
        'posts_per_page' => 5
      ));
      foreach ($custom_posts as $i => $p) {
        $single_page_list[$post_type][] = array(
          'title' => $p->post_title,
          'slug' => $p->post_name,
          'link' => get_permalink( $p->ID )
        );
      }
      // $single_page_list[$post_type][] = $custom_posts;

    }
    $output['list']['single'] = $single_page_list;
    // end single page

  }else{
    $handle = opendir('pages/');
    $static_pages = array();
    while (false !== ($file = readdir($handle))) {
      if($file !== '.' && $file !== '..'){
        $slug = str_replace('.php', '', $file);
        foreach ($static_page_list as $page) {
          if ($page['slug'] == $slug || $slug == '404') goto escape_loop_static;
        }
        $static_page_list[] = array(
          'title' => !empty($_PAGES[$slug])? $_PAGES[$slug]['title'] : $slug,
          'slug' => $slug,
          'link' => get_sitepath('url').'/'.$slug. '/'
        );
        escape_loop_static:
      }
    }
    $static_page_list[] = array(
      'title' => !empty($_PAGES['404'])? $_PAGES['404']['title'] : '404',
      'slug' => '404',
      'link' => get_sitepath('url').'/abcdefgh/'
    );
    $output['list']['page'] = $static_page_list;


    // start taxonomy page
    $taxonomy_page_list = array();

    $taxonomies = $_CATEGORIES;
    foreach ($taxonomies as $taxonomy_slug => $taxonomy) {
      $terms = $taxonomy['terms'];
      foreach ($terms as $term_slug => $term) {
        $taxonomy_page_list[$taxonomy_slug][] = array(
          'title' => $term['name'],
          'slug' => $term_slug,
          'link' => get_term_link($term_slug, $taxonomy_slug)
        );
      }

    }
    $output['list']['taxonomy'] = $taxonomy_page_list;

    // end taxonomy page

    // start single page
    $single_page_list = array();
    foreach ($_SINGLE_POSTS as $post_type => $custom_posts) {
      $single_page_list[$post_type] = array();

      $wp_post_query = get_post_query($post_type, -1);
      while ($wp_post_query->have_posts()) : $wp_post_query->the_post();
        $single_page_list[$post_type][] = array(
          'title' => get_the_title(),
          'slug' => get_the_slug(),
          'link' => get_permalink()
        );
      endwhile;
    }
    $output['list']['single'] = $single_page_list;
    // end single page
  }

  echo json_encode( $output );
