<?php
session_start();

include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );

$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );

if (isset($_REQUEST['code'])) {
	$keys = array();
	$keys['code'] = $_REQUEST['code'];
	$keys['redirect_uri'] = WB_CALLBACK_URL;
	try {
		$token = $o->getAccessToken( 'code', $keys ) ;
		if ($token) {
			$_SESSION['token'] = $token;
			setcookie( 'weibojs_'.$o->client_id, http_build_query($token) );
		}
	} catch (OAuthException $e) {
	}
}

?>
<html>
<script type="text/javascript">

 parent.closeDialog();
</script>
</html>
<?php
/* This will give an error. Note the output
 * above, which is before the header() call */
// header('HTTP/1.1 303 See Other');
// header('Location: http://survey.caritglobal.com/');
// exit; //from www.kjsc.com.cn
?>

