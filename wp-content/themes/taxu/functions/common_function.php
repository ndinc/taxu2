<?php
function get_current_url(){
  return ((empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
}

function get_locale_url($locale='ja'){
  $full_url = get_current_url();
  $path = explode('?', $full_url);
  if (count($path) > 1){
    $url = array_shift($path).'?';
    $search = implode('', $path);
    $queries = explode('&', $search);
    foreach ($queries as $i => $query) {
      $q = explode('=', $query);
      if($q[0] == 'locale'){
        $url .= $q[0].'='.$locale;
      }else{
        $url .= '&'.$query;
      }
    }
    return $url;
  }else{
    return $path[0].'?locale='.$locale;
  }
}

function is_lang($lang){
  global $locale;
  return $lang == $locale;
}

function share_btns($url=false, $sns=false, $class=false){
  $sns_btns = new SnsBtns($url, $sns, $class);
  return $sns_btns->get_sns_html();
}

function is_mobile() {
  return BrowserType::isMobile();
}

function is_legacy() {
  return BrowserType::isLegacyBrowser();
}

function is_prod() {
  global $_SITE;
  return strpos($_SERVER['HTTP_HOST'], $_SITE['domain']['production']) !== false;
}

function is_staging() {
  global $_SITE;
  return strpos($_SERVER['HTTP_HOST'], $_SITE['domain']['staging']) !== false;
}

function is_develop() {
  global $_SITE;
  $is_develop = false;
  if(is_array($_SITE['domain']['develop'])){
    foreach ($_SITE['domain']['develop'] as $i => $domain) {
      if(strpos($_SERVER['HTTP_HOST'], $domain) !== false){
        $is_develop = true;
      }
    }
  }else if(strpos($_SERVER['HTTP_HOST'], $_SITE['domain']['develop']) !== false){
    $is_develop = true;
  }
  return $is_develop;
}

function is_wp() {
  global $wp_query;
  return !empty($wp_query);
}

function d($str){
  echo '<pre>';
    print_r($str);
  echo '</pre>';
}

function get_req_path(){
  $filename = substr($_SERVER['REQUEST_URI'], -1) == '/' ? substr($_SERVER['REQUEST_URI'], 1, -1) : substr($_SERVER['REQUEST_URI'], 1);
  return $filename;
}

function get_filemtime($path){
  return filemtime( get_sitepath('directory').$path);
}

class SnsBtns {
  public $url;

  function __construct($url=false, $sns=false, $class=false){
    $this->url = isset($url)? $url : false;
    $this->sns = (!empty($sns))? $sns : array('facebook', 'twitter');
    $this->class = (!empty($class))? $class : 'm-share';
    $this->sns_htmls = array(
      'facebook' => $this->get_facebook_html(),
      'twitter' => $this->get_twitter_html(),
      'google' => $this->get_google_html(),
      'hatena' => $this->get_hatena_html(),
      'pocket' => $this->get_pocket_html(),
      'evernote' => $this->get_evernote_html()
    );

  }

  public function get_sns_html(){
    $html = '<div class="'. $this->class. '">';

    foreach ($this->sns as $i => $sns) {
      $html .= $this->sns_htmls[$sns];
    }

    $html .= '</div>';

    return $html;
  }

  private function get_facebook_html(){
    $html = '<span class="facebook">
      <div class="fb-like" '.(isset($this->url)? 'data-href="'.$this->url.'"' : '').' data-layout="button" data-action="like" data-show-faces="false" data-share="false"></div>
    </span>';
    return $html;
  }

  private function get_twitter_html(){
    $html = '<span class="twitter">
      <a href="https://twitter.com/share" class="twitter-share-button"'.(isset($this->url)? 'data-url="'.$this->url.'"' : '').'" data-count="none" data-lang="ja">ツイート</a>
    </span>';
    return $html;
  }

  private function get_hatena_html(){
    $html = '
    <span class="hatena">
      <a href="'.(isset($this->url)? 'http://b.hatena.ne.jp/entry/'.$this->url : '').'" class="hatena-bookmark-button" data-hatena-bookmark-layout="standard-balloon" data-hatena-bookmark-lang="ja" title="このエントリーをはてなブックマークに追加">
        <img src="http://b.st-hatena.com/images/entry-button/button-only@2x.png" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" />
      </a>
    </span>';
    return $html;
  }

  private function get_google_html(){
    $html = '
    <span class="google">
      <div class="g-plusone" data-size="medium"></div>
    </span>
    ';
    return $html;
  }

  private function get_pocket_html(){
    $html = '
    <span class="pocket">
      <a data-pocket-label="pocket" '.(isset($this->url)? 'data-save-url="'.$this->url.'"' : '').' data-pocket-count="none" class="pocket-btn" data-lang="en"></a>
    </span>';
    return $html;
  }

  private function get_evernote_html(){
    $html = '
    <span class="evernote">
      <a href="#" onclick="Evernote.doClip({contentId:\'main\',providerName:\'localpocky\\\'s reports\'}); return false;">
        <img src="http://static.evernote.com/article-clipper-jp.png" alt="Clip to Evernote">
      </a>
    </span>';
    return $html;
  }
}


/**
 * ブラウザの判別
 *
 * @version 1.1.1
 */
class BrowserType {
      /**
       * ブラウザのタイプを取得する
       *
       * @return int 0ならモダンブラウザ、1ならバージョンが10未満のIE、2はそのほかのレガシーブラウザ
       */
      public static function getBrowserType() {
            if( stristr($_SERVER['HTTP_USER_AGENT'], 'opera') ||
                stristr($_SERVER['HTTP_USER_AGENT'], 'safari') ||
                stristr($_SERVER['HTTP_USER_AGENT'], 'chrome') ||
                stristr($_SERVER['HTTP_USER_AGENT'], 'firefox') ) {
                  $type = 0;
            } else if ( stristr($_SERVER['HTTP_USER_AGENT'], "msie") ) {
                  if( preg_match('/msie ?[5-9]{1}[ \.]?/i', $_SERVER['HTTP_USER_AGENT']) ) {
                        $type = 1;
                  } else {
                        $type = 0;
                  }
            } else if( ! empty($_SERVER['HTTP_ACCEPT']) ) {
                  if( strpos('application/xml', $_SERVER['HTTP_ACCEPT']) ||
                      strpos('application/xhtml+xml', $_SERVER['HTTP_ACCEPT']) ) {
                        $type = 0;
                  } else {
                        $type = 2;
                  }
            } else {
                  $type = 2;
            }

            return (int) $type;
      }

      /**
       * モダンブラウザかどうかの判定
       *
       * @return bool モダンブラウザならtrue、そうでなければfalse
       */
      public static function isModernBrowser() {
            return (bool) (self::getBrowserType() === 0);
      }

      /**
       * レガシーブラウザかどうかの判定
       *
       * @return bool レガシーブラウザならtrue、そうでなければfalse
       */
      public static function isLegacyBrowser() {
            return (bool) (self::getBrowserType() > 0);
      }

      /**
       * versionが10未満のIEかどうかの判定
       *
       * @return bool versionが10未満のIEならtrue、そうでなければfalse
       */
      public static function isOldIe() {
            return (bool) (self::getBrowserType() === 1);
      }

      /**
       * IEのバージョンを整数値で取得する
       *
       * @return int IEならば1以上の整数値、そうでなければ0
       */
      public static function getIeVer() {
            if ( self::getBrowserType() !== 1 ) {
                  $ie_ver = 0;
            } else {
                  preg_match('/msie ?([0-9]+)/i', $_SERVER['HTTP_USER_AGENT'], $ie_ver);
                  $ie_ver = $ie_ver[1];
            }

            return (int) $ie_ver;
      }

      /**
       * スマートフォンやタブレットなどのモバイル端末かどうかを判定する
       *
       * @return bool モバイル端末ならばtrue、そうでなければfalse
       * @link http://core.trac.wordpress.org/browser/tags/3.5.1/wp-includes/vars.php
       */
      public static function isMobile() {
            if ( self::isLegacyBrowser() ) {
                  $is_mobile = false;
            } else if ( stristr($_SERVER['HTTP_USER_AGENT'], 'mobile') ||
                        stristr($_SERVER['HTTP_USER_AGENT'], 'android') ||
                        stristr($_SERVER['HTTP_USER_AGENT'], 'silk/') ||
                        stristr($_SERVER['HTTP_USER_AGENT'], 'kindle') ||
                        stristr($_SERVER['HTTP_USER_AGENT'], 'blackberry') ||
                        stristr($_SERVER['HTTP_USER_AGENT'], 'opera mini') ||
                        stristr($_SERVER['HTTP_USER_AGENT'], 'opera mobi') ) {
                  $is_mobile = true;
            } else {
                  $is_mobile = false;
            }

            return (bool) $is_mobile;
      }
}

