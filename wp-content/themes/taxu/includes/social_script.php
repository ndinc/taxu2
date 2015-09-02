  <?php $social_scripts = array(
    'facebook',
    'twitter',
    // 'google',
    // 'hatena',
    // 'pocket',
    // 'evernote',
  ); ?>

  <?php if (in_array('facebook', $social_scripts)): ?>
  <!-- facebook -->
  <script>
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
  </script>
  <!-- //facebook -->
  <?php endif ?>

  <?php if (in_array('twitter', $social_scripts)): ?>
  <!-- twitter -->
  <script>
    !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');
  </script>
  <!-- //twitter -->
  <?php endif ?>

  <?php if (in_array('google', $social_scripts)): ?>
  <!-- 最後の +1 ボタン タグの後に次のタグを貼り付けてください。 -->
  <script type="text/javascript">
    window.___gcfg = {lang: 'ja'};
    (function() {
      var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
      po.src = 'https://apis.google.com/js/platform.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();
  </script>
  <?php endif ?>

  <?php if (in_array('hatena', $social_scripts)): ?>
  <!-- hatena -->
  <script type="text/javascript" src="http://b.st-hatena.com/js/bookmark_button.js" charset="utf-8" async="async"></script>
  <!-- //hatena -->
  <?php endif ?>

  <?php if (in_array('pocket', $social_scripts)): ?>
  <!-- pocket -->
  <script type="text/javascript">!function(d,i){if(!d.getElementById(i)){var j=d.createElement("script");j.id=i;j.src="https://widgets.getpocket.com/v1/j/btn.js?v=1";var w=d.getElementById(i);d.body.appendChild(j);}}(document,"pocket-btn-js");</script>
  <!-- //pocket -->
  <?php endif ?>

  <?php if (in_array('evernote', $social_scripts)): ?>
  <!-- evernote -->
  <script type="text/javascript" src="http://static.evernote.com/noteit.js"></script>
  <!-- //evernote -->
  <?php endif ?>

  <?php if (!is_develop()): ?>
  <!-- google analytics -->
  <!-- //google analytics -->
  <?php endif ?>

  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-66085789-1', 'auto');
    ga('send', 'pageview');

  </script>
