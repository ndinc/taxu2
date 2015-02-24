<?php
  global $_SITE;
  global $_PAGES;

  $_SITE = array(
    'domain' => array(
      'develop'=> array(
        'blogndinc:8889',
        'local.blogndinc.com',
        'blogndinc.ngrok.com'
      ),
      'staging'=> 'blogndinc.ndv.me',
      'production'=> 'blogndinc.ndinc.jp',
    ),
    'site_name' => 'nD inc. Blog',
    'site_url' => 'http://'.$_SERVER['HTTP_HOST'],
    'title' => 'nD inc. Blog',
    'description' => 'インターネットを使って、もっと楽しく、より豊かに。サテライトで web 開発を行う nD inc. の考えていること、やっていること、働きかたなどを発信するブログです。',
    'keywords' => 'nD,nd,ndinc,株式会社nD,サテライト,workstyle,ワークスタイル,ワーキングスタイル,ワーキングスタイル,internet,インターネット,web,ウェブ',
    'image' => get_sitepath('template_url').'/images/ogimage.png?v=1.0'
  );

  $_PAGES = array(
    'about' => array(
      'title' => 'About',
      'description' => 'xxx about desc',
      'image' => $_SITE['image']
    ),
    '404' => array(
      'title' => 'ページが存在しません',
      'description' => $_SITE['description'],
      'image' => $_SITE['image']
    )
  );
?>
