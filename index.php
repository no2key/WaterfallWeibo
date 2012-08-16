<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
session_start();
include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );

$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );

$code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );

if (isset($_SESSION['token'])) {
	$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
	$uid_get = $c->get_uid();
	if(isset($uid_get['error'])){
		print_r($uid_get);
	}else{

		$ms  = $c->home_timeline(); // done
		$uid = $uid_get['uid'];
		$user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息

	}
}


?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<title>jQuery Wookmark Plug-in API Example</title>
<meta name="description"
	content="An very basic example of how to use the Wookmark jQuery plug-in.">
<meta name="author" content="Christoph Ono">

<meta name="viewport" content="width=device-width,initial-scale=1">

<!-- CSS Reset -->
<link rel="stylesheet" href="css/reset.css">

<!-- Styling for your grid blocks -->
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/colorbox.css" />
<style type="text/css">
#nav_left_layout {
	position: absolute;
	height: 80px;
	width: 100%;
	background: #dedede;
	z-index: 100;
}
</style>
</head>

<body>

	<div id="container">
		<div height="100px" width="100%">
			<H1>Test</H1>
		</div>
		<header id="nav_left_layout" style="display: none;">
			<h1>jQuery Wookmark Plug-in API Example</h1>
			<p>
				Scroll down to see more content loaded via the
				<?php
				if (!isset($uid)) {
					?>

				<a class="iframe" href="<?=$code_url?>">Login</a>.
			</p>

			<?php
				} else {
					?>
			<?=$user_message['screen_name']?>
			,您好！
			</p>
			<?php
				}
				?>

			<?php
			if (isset($token)) {

				if ($token) {
					?>
			<p>
				token =
				<?php print_r($token)?>
				<?php
				}
			}
			?>
		
		</header>
		<div id="main" role="main">

			<ul id="tiles">
				<!-- These is where we place content loaded from the Wookmark API -->
			</ul>

			<div id="loader">
				<div id="loaderCircle"></div>
			</div>

		</div>

		<footer> </footer>
	</div>

	<!-- include jQuery -->
	<script src="js/jquery-1.7.1.min.js"></script>

	<!-- Include the plug-in -->
	<script src="js/jquery.wookmark.js"></script>
	<script src="js/jquery.colorbox.js"></script>

	<!-- Once the page is loaded, initalize the plug-in. -->
	<script type="text/javascript">
 

    var handler = null;
    var page = 1;
    var isLoading = false;
    
    <?php
	if (!isset($uid)) {
	?>
	var apiURL = 'https://api.weibo.com/2/statuses/public_timeline.json'
	var access_token =null;
    <?php
	} else {
	?>
	var apiURL = 'https://api.weibo.com/2/statuses/home_timeline.json'
	var access_token = '<?=$_SESSION['token']['access_token']?>'
	<?php
	}
	?>
    
    var result = null;
    // Prepare layout options.
    var options = {
      autoResize: true, // This will auto-update the layout when the browser window is resized.
      container: $('#tiles'), // Optional, used for some extra CSS styling
      offset: 2, // Optional, the distance between grid items
      itemWidth: 210 ,// Optional, the width of a grid item
      resizeDelay: 1000
    };
    
    /**
     * When scrolled all the way to the bottom, add more tiles.
     */
    function onScroll(event) {
      // Only check when we're not still waiting for data.
      if(!isLoading) {
        // Check if we're within 100 pixels of the bottom edge of the broser window.
        var closeToBottom = ($(window).scrollTop() + $(window).height() > $(document).height() - 100);
        if(closeToBottom) {
          loadData();
        }
      }
    };
    

    function closeDialog(){
      $("#colorbox").colorbox.close();
    }
    /**
     * Refreshes the layout.
     */
    function applyLayout() {
      // Clear our previous layout handler.
      if(handler) handler.wookmarkClear();
      
      // Create a new layout handler.
      handler = $('#tiles li');
      handler.wookmark(options);
    };
    
    /**
     * Loads data from the API.
     */
    function loadData() {
      isLoading = true;
      $('#loaderCircle').show();
      
      $.ajax({
        url: apiURL,
        dataType: 'jsonp',
        data: {page: page,access_token: access_token}, // Page parameter to make sure we load new data
        success: onLoadData
      });
    };
    
    /**
     * Receives data from the API, creates HTML for images and updates the layout
     */
    function onLoadData(data) {
      isLoading = false;
      $('#loaderCircle').hide();
      
      // Increment page index for future calls.
      page++;
      
      // Create HTML for the images.
      //var obj = jQuery.parseJSON(data);  
      //alert(data.length);
	  
      var html = '';
      var statuses = data.data.statuses;
      var i=0, length=statuses.length, image;
      for(; i<length; i++) {
        image = statuses[i];
        html += '<li class="last">';
        
        // Image tag (preview in Wookmark are 200px wide, so we calculate the height based on that).
        if('original_pic' in image){
        html += '<img src="'+image.original_pic+'" width="200" height="200">';
        }
        // Image title.
        html += '<p>'+image.text+'</p>';
        
        html += '</li>';
      }
      
      
      // Add image HTML to the page.
      //$('#tiles').append(html).children(':last').hide();
      $('#tiles').append(html);
      // Apply layout.
      applyLayout();
      $("li.last").hide().fadeIn(1000,function(){
        $("li.last").removeClass("last");
      });

    };
    $.fn.smartFloat = function() {
    	  var position = function(element) {
    	    var top = element.position().top, pos = element.css("position");
    	    $(window).scroll(function() {
    	      var scrolls = $(this).scrollTop();
    	      if (scrolls > top) {
    	        if (window.XMLHttpRequest) {
    	          element.css({
    	            position: "fixed",
    	            top: 0
    	          }); 
    	        } else {
    	          element.css({
    	            top: scrolls
    	          }); 
    	        }
    	      }else {
    	        element.css({
    	          position: pos,
    	          top: top
    	        }); 
    	      }
    	    });
    	};
    	  return $(this).each(function() {
    	    position($(this));             
    	  });
    	};
  
    $(document).ready(new function() {
      // Capture scroll event.
      $(document).bind('scroll', onScroll);
      $("#nav_left_layout").fadeIn(1000);
      $(".ajax").colorbox();
      $(".iframe").colorbox({iframe:true, width:"700px", height:"500px"});

      $("#nav_left_layout").smartFloat();
      // Load first data from the API.
      loadData();
 
    });
  </script>

</body>
</html>
