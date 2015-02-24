<?php

function my_dashboard_setup_function() {
  wp_add_dashboard_widget( 'custom_help_widget', 'How-to Screencasts', 'custom_dashboard_help' );
}

function custom_dashboard_help() {?>
  <ul>
    <li>
      <a target="_blank" href="<?php echo get_bloginfo('template_directory');?>/tutorials/" style="font-size: 14px; margin-bottom: 10px; display: inline-block;" >
        <b>☞ テキスト版ドキュメンテーション</b>
      </a>
    </li>
  </ul>
<?php
}
// add_action( 'wp_dashboard_setup', 'my_dashboard_setup_function' );

function set_eye_catch_iamge() {
  add_theme_support('post-thumbnails');
  set_post_thumbnail_size(1200, 1200, true);
}
add_action( 'after_setup_theme', 'set_eye_catch_iamge' );

function update_profile_fields( $contactmethods ) {
  //項目の削除
  unset($contactmethods['aim']);
  unset($contactmethods['jabber']);
  unset($contactmethods['yim']);
  //項目の追加
  $contactmethods['twitter'] = 'Twitter';
  $contactmethods['facebook'] = 'Facebook';

  return $contactmethods;
}
add_filter('user_contactmethods','update_profile_fields',10,1);


function remove_menu(){
  // remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=category' ); //カテゴリー
  // remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=post_tag' ); //投稿タグ

  if (!current_user_can('level_10')) { //level10以下のユーザーの場合メニューをunsetする
  remove_submenu_page( 'index.php', 'update-core.php' );  //更新

  // remove_menu_page('edit.php');
  remove_menu_page('upload.php');

  remove_menu_page('link-manager.php'); // リンク
  remove_submenu_page( 'link-manager.php', 'link-add.php' );  //新規追加
  remove_submenu_page( 'link-manager.php', 'edit-tags.php?taxonomy=link_category' );  //リンクカテゴリー

  // remove_menu_page('edit.php?post_type=page'); // 固定ページ
  // remove_submenu_page( 'edit.php?post_type=page', 'post-new.php?post_type=page' );  //新規追加

  remove_menu_page('edit-comments.php');
  remove_menu_page('themes.php'); // 外観
  remove_menu_page('plugins.php'); // プラグイン


  // remove_submenu_page( 'users.php', 'user-new.php' ); //サブメニュー新規追加
  remove_menu_page('tools.php'); // ツール
  remove_menu_page('options-general.php'); // 設定
  // remove_menu_page('admin.php');
  remove_menu_page('backwpup');

  }
}
add_action('admin_menu','remove_menu');

function custom_columns ($columns) {
  // unset($columns['cb']);           // チェックボックス
  // unset($columns['title']);        // タイトル
  // unset($columns['author']);       // 作成者
  // unset($columns['categories']);   // カテゴリー
  // unset($columns['tags']);         // タグ
  // unset($columns['comments']);     // コメント
  unset($columns['date']);         // 日付
  return $columns;
}
add_filter('manage_posts_columns', 'custom_columns');

function remove_default_post_screen_metaboxes() {
  // remove_meta_box( 'postexcerpt','post','normal' );       // 抜粋
  remove_meta_box( 'trackbacksdiv','post','normal' );     // トラックバック送信
  // remove_meta_box( 'postcustom','post','normal' );        // カスタムフィールド
  remove_meta_box( 'commentstatusdiv','post','normal' );  // ディスカッション
  remove_meta_box( 'commentsdiv','post','normal' );       // コメント
  // remove_meta_box( 'slugdiv','post','normal' );           // スラッグ
  // remove_meta_box( 'authordiv','post','normal' );         // 作成者
  // remove_meta_box( 'revisionsdiv','post','normal' );      // リビジョン
  remove_meta_box( 'formatdiv','post','normal' );         // フォーマット
  // remove_meta_box( 'categorydiv','post','side' );       // カテゴリー
  // remove_meta_box( 'tagsdiv-post_tag','post','side' );  // タグ
  // remove_meta_box( 'postimagediv','post','side' );  // アイキャッチ
}
add_action('do_meta_boxes','remove_default_post_screen_metaboxes');

// 固定ページ
function remove_default_page_screen_metaboxes() {
  // remove_meta_box( 'postcustom','page','normal' );        // カスタムフィールド
  remove_meta_box( 'commentstatusdiv','page','normal' );  // ディスカッション
  remove_meta_box( 'commentsdiv','page','normal' );       // コメント
  // remove_meta_box( 'slugdiv','page','normal' );           // スラッグ
  // remove_meta_box( 'authordiv','page','normal' );         // 作成者
  remove_meta_box( 'revisionsdiv','page','normal' );      // リビジョン
}
add_action('do_meta_boxes','remove_default_page_screen_metaboxes');

