<?php
/*
Plugin Name: MKSocialLocker
Description: With this plugin you can hide your content.
Version: 1.0
Author: Mustafa KÜÇÜK - WpAJANS
Author URI: https://wpajans.net/
Plugin URI: http://demo.wpajans.net/mklocker/
*/

## Front Assets ##
function mkSocialLockerFrontAssets(){
  wp_enqueue_style( 'mkSocialLockerIcons', plugins_url( 'css/icons.css', __FILE__ ));
  wp_enqueue_style( 'mkSocialLocker', plugins_url( 'css/mkSocialLocker.css', __FILE__ ));
  wp_enqueue_script( 'mkSocialLocker', plugins_url( 'js/mkSocialLocker.js', __FILE__ ), false );
}

add_action('wp_footer','mkSocialLockerFrontAssets');
## MKSocialLocker Footer Codes ##
function mkSocialLockerFooter(){
  $postTitle = get_the_permalink();
  $postURL   = get_the_title();
  $getFacebookAppID = get_option('mkFacebookAppID');
  $getFacebookHashtag = get_option('mkFacebookHashtag');
  $getTwitterContent = str_replace(array("[url]","[title]"), array($postURL,$postTitle), get_option("mkTwitterContent"));
  $getFacebookAlert = (get_option("mkWarningTextFacebook")==""?"Please share to see the content":get_option("mkWarningTextFacebook"));
  echo'<script src="https://apis.google.com/js/platform.js" async defer>{lang: "tr"}</script>
  <script type="text/javascript">
  var postURL = "'.$postTitle.'";
  var postTitle = "'.$postURL.'";
  var appID = "'.$getFacebookAppID.'";
  var hashTag = "'.$getFacebookHashtag.'";
  var getTwitterContent = "'.$getTwitterContent.'";
  var getFacebookAlert = "'.$getFacebookAlert.'";
</script>
';
}

add_action("wp_footer","mkSocialLockerFooter");


## MKSocialLocker Text Editor Button ##
function appthemes_add_quicktags() {
    if (wp_script_is('quicktags')){
      echo"<script type='text/javascript'>QTags.addButton( 'mkLocker', 'mkLocker', '[mkLocker]', '[/mkLocker]', 'mkLocker', 'mkLocker', 1 );</script>";
    }
}
add_action( 'admin_print_footer_scripts', 'appthemes_add_quicktags' );

## MKSocialLocker Shortcode ##
function mkSocialLockerShortcode( $atts, $content=null ){
  $mkContent = '<div id="mkSocialLocker">
    <div class="mkLockerAlert">'.(get_option("mkWarningText")==""?"Oops! Please Share with your account!":get_option("mkWarningText")).'</div>
    <div class="mkSocialLocker">
    <div class="mkSocialLockerLeftText">LOCKED</div>
    <div class="mkSocialLockerRight">
      <div class="mkSocialLockerRightMedia">
        <ul>
        <li class="mkSocialLockerFB" id="mkLockerFB"><i class="icon-facebook"></i> Share</li>
        <li class="mkSocialLockerTW" id="mkLockerTW"><i class="icon-twitter"></i> Tweet</li>
        <li class="mkSocialLockerGP" id="mkLockerGP"><i class="icon-gplus"></i> <g:plusone size="small" callback="callbackGoogleAction"></g:plusone></li>
        </ul>
      </div>
    </div>
    </div>
  </div>
  <div id="mkSocialLockerHideContent">'.$content.'</div>';
  return ( get_option('mkForLoggedin') == "on" ? ( is_user_logged_in() ? $content : $mkContent ) : $mkContent );
}

add_shortcode( "mkLocker", "mkSocialLockerShortcode" );

## Admin Panel ##

function mkSocialLockerMenu(){
  add_menu_page("SocialLocker","SocialLocker","manage_options","mkSocialLocker","mkSocialLockerPage");
}

add_action("admin_menu","mkSocialLockerMenu");

function mkSocialLockerPage(){
  if ($_POST) {
    if (!isset($_POST['mkSocialLockerNonce']) || ! wp_verify_nonce( $_POST['mkSocialLockerNonce'], 'mkSocialLockerNonce' ) ) {
      print 'Sorry, please try agin later!';
    exit;
    }else{
      $facebookAppID    =   sanitize_text_field($_POST["facebookAppID"]);
      $facebookHashtag  =   sanitize_text_field($_POST["facebookHashtag"]);
      $twitterContent   =   sanitize_text_field($_POST["twitterContent"]);
      $warningText   =   sanitize_text_field($_POST["warningText"]);
      $warningTextFacebook   =   sanitize_text_field($_POST["warningTextFacebook"]);
      $forLoggedin   =   sanitize_text_field($_POST["forLoggedin"]);
      update_option("mkFacebookAppID",$facebookAppID);
      update_option("mkFacebookHashtag",$facebookHashtag);
      update_option("mkTwitterContent",$twitterContent);
      update_option("mkWarningText",$warningText);
      update_option("mkWarningTextFacebook",$warningTextFacebook);
      update_option("mkForLoggedin",$forLoggedin);
      echo '<div class="notice notice-success is-dismissible"><p>Saved changes!</p></div>';
  }
}
?>
<form action="" method="post">
  <h1>General Settings</h1>
  <hr>
  <?php wp_nonce_field('mkSocialLockerNonce','mkSocialLockerNonce'); ?>
  <label for="facebookAppID">Facebook Application ID</label>
  <input type="text" name="facebookAppID" id="facebookAppID" value="<?php echo get_option('mkFacebookAppID'); ?>">
  <hr>
  <label for="facebookHashtag">A hashtag for Facebook</label>
  <input type="text" name="facebookHashtag" id="facebookHashtag" value="<?php echo get_option('mkFacebookHashtag'); ?>"> // #wpajans
  <hr>
  <label for="twitterContent">Twitter share content</label>
  <textarea id="twitterContent" name="twitterContent" placeholder="[url] ---> [title]"><?php echo get_option('mkTwitterContent'); ?></textarea>
  <hr>
  <label for="forLoggedin">Remove locks for logged-in users</label>
  <input type="checkbox" name="forLoggedin" id="forLoggedin" <?php echo (get_option('mkForLoggedin')=="on"?"CHECKED":"");?>>
  <hr>  
  <h1>Template Settings</h1>
  <hr>
  <label for="warningText">Alert Text</label>
  <textarea id="warningText" name="warningText" placeholder="Oops! Please Share with your account!"><?php echo get_option('warningText'); ?></textarea>
  <hr>
  <label for="warningTextFacebook">Alert text when Facebook sharing is canceled</label>
  <textarea id="warningTextFacebook" name="warningTextFacebook" placeholder="Oops! Please share to see the content!"><?php echo get_option('warningTextFacebook'); ?></textarea>
  <hr>  
  <button type="submit">Save changes</button>
</form>
<?php }