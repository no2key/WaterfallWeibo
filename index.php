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

		$ms  = $c->query_busline('79','0755'); // done
    //print_r($ms);
		$uid = $uid_get['uid'];
		$user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
		
  	  

	}
}
class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('db/Mfile.db');
    }
}
$db = new MyDB();
/*
CREATE TABLE [BusLineTable] (
  [_id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
 
  [Air] BOOLEAN,
 
  [Auto] BOOLEAN,
 
  [BasicPrice] FLOAT,
 
  [CommunicationTicket] BOOLEAN,
 
  [Company] TEXT,
 
  [DataSource] INT,
 
  [Description] TEXT,
 
  [EndTime] TEXT,
 
  [FrontName] TEXT,
 
  [FrontSpell] TEXT,
 
  [KeyName] TEXT,
 
  [Length] FLOAT,
 
  [LineId] TEXT,
 
  [Name] TEXT,
 
  [Speed] FLOAT,
 
  [StartTime] TEXT,
 
  [Status] INT,
 
  [TerminalName] TEXT,
 
  [TerminalSpell] TEXT,
 
  [TotalPrice] FLOAT,
 
  [Type] INT,
 
  [IsDoubleDeck] BOOLEAN,
 
  [IsExpressWay] BOOLEAN,
 
  [IsIcCard] BOOLEAN,
 
  [IsLoop] BOOLEAN)
  
  coordinates
  
  "air": "0",
  			"auto": "0",
  			"basic_price": "2",
  			"city": "0755",
  			"commutation_ticket": "0",
  			"company": "深圳巴士集团股份有限公司鹏翔客运公司",
			"description": "深圳巴士集团股份有限公司鹏翔客运",
						"double_deck": "0",
						"end_time": "21:30",
						"express_way": "0",
						"front_name": "清水河总站",
						"front_spell": "\"qing shui he zong zhan\"",
						"ic_card": "0",
						"key_name": "79路",
						"length": "30.582",
						"name": "79路(清水河总站--东角头总站)",
						"service_period": "",
						"start_time": "06:30",
						"status": "1",
									"terminal_name": "东角头总站",
									"terminal_spell": "\"dong jiao tou zong zhan\"",
									"time_desc": "",
									"total_price": "7",
									"type": "1"
  */
//$db->exec('CREATE TABLE foo (bar STRING)');
foreach ($ms['lines'] as $item) {
   
	//$db->exec("INSERT INTO BusLineTable (Air,Auto,BasicPrice,CommunicationTicket,Company,DataSource,Description,StartTime,EndTime,FrontName,FrontSpell,KeyName,Length,Name,Status,TerminalName,TerminalSpell,TotalPrice,Type,IsDoubleDeck,IsExpressWay,IsIcCard,Coordinates) VALUES ({$item['air']},{$item['auto']},{$item['basic_price']}),{$item['commutation_ticket']},{$item['company']},'sina_Weibo',{$item['description']},{$item['start_time']},{$item['end_time']},{$item['front_name']},{$item['front_spell']},{$item['key_name']},{$item['length']},{$item['name']},{$item['status']},{$item['terminal_name']},{$item['terminal_spell']},{$item['total_price']},{$item['type']},{$item['double_deck']},{$item['express_way']},{$item['ic_card']},{$item['coordinates']})");
	echo "INSERT INTO BusLineTable (Air) VALUES ({$item['air']})";
	$db->exec("INSERT INTO BusLineTable (Air) VALUES ({$item['air']})");
	echo $db->lastInsertRowID().'</p>';	
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<!-- include jQuery -->
<script src="js/jquery-1.7.1.min.js"></script>
<!-- Include the plug-in -->
<script src="js/jquery.wookmark.js"></script>
<script src="js/jquery.colorbox.js"></script>
<script src="js/jquery.imagesloaded.min.js"></script>

<style type="text/css">
#nav_left_layout {
	position: absolute;
	height: 50px;
	width: 100%;
	background: #dedede;
	z-index: 9;
}
</style>
<script>
			$(document).ready(function(){
				$(".ajax").colorbox();
			    $(".iframe").colorbox({iframe:true, width:"700px", height:"500px"});
			   
			});
</script>
</head>

<body>

	<div id="container">
		<header id="nav_left_layout">
			<h1>Waterfall WeiBo</h1>
			<p>
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
		<?php if( is_array( $ms['lines'] ) ): ?>
    <?php foreach( $ms['lines'] as $item ): ?>
    <div style="padding:10px;margin:5px;border:1px solid #ccc">
      <?=$item['name'];?>-全程<?=$item['length'];?>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
    <?php
    /**
     * Simple example of extending the SQLite3 class and changing the __construct
     * parameters, then using the open method to initialize the DB.
     */
	 /* 
	  * create a SQLite3 handle. 
	  * 
	  * Note: in-memory database are created by the magic keyword ":memory:" 
	  * 
	  */ 
	  
     
    // public function lastInsertId() {
    // $result = $dbh->query('SELECT last_insert_rowid() as last_insert_rowid')->fetch();
    // return $result['last_insert_rowid'];
    // }

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
      offset: 5, // Optional, the distance between grid items
      itemWidth: 310 ,// Optional, the width of a grid item
      resizeDelay: 1000
    };
    var flag=false;
    function DrawImage(ImgD,iwidth,iheight){
        //参数(图片,允许的宽度,允许的高度)
        var image=new Image();
        image.src=ImgD.src;
        if(image.width>0 && image.height>0){
        flag=true;
        if(image.width/image.height>= iwidth/iheight){
            if(image.width>iwidth){  
            ImgD.width=iwidth;
            ImgD.height=(image.height*iwidth)/image.width;
            }else{
            ImgD.width=image.width;  
            ImgD.height=image.height;
            }
            ImgD.alt=image.width+"×"+image.height;
            }
        else{
            if(image.height>iheight){  
            ImgD.height=iheight;
            ImgD.width=(image.width*iheight)/image.height;        
            }else{
            ImgD.width=image.width;  
            ImgD.height=image.height;
            }
            ImgD.alt=image.width+"×"+image.height;
            }
        }
    } 
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
      location.reload();
    }
    /**
     * Refreshes the layout.
     */
    function applyLayout() {
      // Clear our previous layout handler.
      if(handler) handler.wookmarkClear();
      /*$("li img").each(function(){
    	   if($(this).width() > $(this).parent().width()) {
    	    $(this).width("100%");
    	  }
    	});
    	*/
      // Create a new layout handler.
      var dfd = $('#tiles').imagesLoaded();
      dfd.always( function(){
    		  console.log( 'all images has finished with loading, do some stuff...' );
    		  handler = $('#tiles li');
    	      handler.wookmark(options);
    	      $(".pic").colorbox({rel:'pic'});
    	      $("li.last").hide().fadeIn(1000,function(){
    	          $("li.last").removeClass("last");
    	        });
    	      isLoading = false;
    	}); 
      
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
        data: {page: page,access_token: access_token,count:100}, // Page parameter to make sure we load new data
        success: onLoadData
      });
    };
    
    /**
     * Receives data from the API, creates HTML for images and updates the layout
     */
    function onLoadData(data) {
      
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
        //user name
        html += '<p class="screen_name">'+image.user.screen_name+'</p>';
        html += '<p class="label"></p>';
        html += '<p class="weibo_text">'+image.text+'</p>';
        if('retweeted_status' in image){
        html += '<p class="retweeted_text">'+image.retweeted_status.text+'</p>';
        }
        // Image tag (preview in Wookmark are 200px wide, so we calculate the height based on that).
        if('thumbnail_pic' in image){
        //html += '<img src="'+image.thumbnail_pic+'" onload="javascript:DrawImage(this,200,500)">';
        //<a class="group1" href="../content/ohoopee1.jpg" title="Me and my grandfather on the Ohoopee.">Grouped Photo 1</a>
        	html += '<a class="pic" href="'+image.original_pic+'"> <img src="'+image.thumbnail_pic+'"> </a>';
        }
        /*if('retweeted_status' in image && 'thumbnail_pic' in image.retweeted_status){
        	html += '<a class="pic" href="'+image.retweeted_status.original_pic+'"> <img src="'+image.retweeted_status.thumbnail_pic+'"> </a>';
        }*/
        // Image title.
        if('retweeted_status' in image && 'thumbnail_pic' in image.retweeted_status){
            	html += '<div width="100%" class="retweeted_pic"><a class="pic" href="'+image.retweeted_status.original_pic+'"> <img src="'+image.retweeted_status.thumbnail_pic+'"> </a></div>';
        }
        html += '</li>';
      }
      
      
      // Add image HTML to the page.
      //$('#tiles').append(html).children(':last').hide();
      $('#tiles').append(html);
      // Apply layout.
      applyLayout();
      

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
      //$(document).bind('scroll', onScroll);
      //$("#nav_left_layout").fadeIn(1000);
      
      //$("#nav_left_layout").smartFloat();
      // Load first data from the API.
      //loadData();
 
    });
  </script>

</body>
</html>
