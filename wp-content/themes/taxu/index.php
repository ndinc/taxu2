<?php
if(empty($wp_query)){
  require( 'functions.php' );
  require( 'data/data.php' );
}
require( 'data/meta.php' );

if(!empty($_GET['page_list']) && $_GET['page_list'] == 1){
  include "data/export.php";
  exit;
}

include "header.php";

include get_page_path();

include "footer.php";
