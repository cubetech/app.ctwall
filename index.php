<?php

	require 'lib/twitteroauth/autoload.php';
	require 'lib/helper.php';
	require 'config/index.php';
	use Abraham\TwitterOAuth\TwitterOAuth;

?>

<html>
	
	<head>
		<title>cubetech Twitterwall</title>
		<meta http-equiv="refresh" content="<?php echo $config['refreshtime']; ?>">
		<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
		<script type="text/javascript" src="http://fast.fonts.net/jsapi/41eab4a5-26c6-4358-9023-123b85ce0c66.js"></script>
		<script type="text/javascript">
			$(document).ready(function () {
			    var allBoxes = $("div.boxes").children("div");
			    transitionBox(null, allBoxes.first());
			});
			
			function transitionBox(from, to) {
			    function next() {
			        var nextTo;
			        if (to.is(":last-child")) {
			            nextTo = to.closest(".boxes").children("div").first();
			        } else {
			            nextTo = to.next();
			        }
			        to.fadeIn(800, function () {
			            setTimeout(function () {
			                transitionBox(to, nextTo);
			            }, 5000);
			        });
			    }
			    
			    if (from) {
			        from.fadeOut(1000, next);
			    } else {
			        next();
			    }
			}
		</script>
		<link rel="stylesheet" href="https://www.cubetech.ch/assets/css/main.min.css">
		<link rel="stylesheet" href="/assets/css/main.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

	</head>
	<body>
<?php

	$toa = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
	 
	$query = array(
	  "q" => implode(' OR ', $config['keywords']),
	);

	session_start();
	
	if(isset($_SESSION['twitter']) && $_SESSION['twitter']['timestamp'] > (time() - 120) && !isset($_GET['nocache'])) {
		$results = $_SESSION['twitter']['data'];
	} else {
		$results = $toa->get('search/tweets', $query);
		if ($toa->getLastHttpCode() != 200) {
			// API not successful
			$results = array();
		}
		$_SESSION['twitter']['data'] = $results;
		$_SESSION['twitter']['timestamp'] = time();
	}
	
	if(isset($_GET['dump'])) var_dump($result);

	echo '<div class="boxes">';

	$count = 0;
	foreach ($results->statuses as $result) {

		if( contains($result->text, $config['blacklist']) == false && contains($result->user->screen_name, $config['blacklist']) == false ):

			echo '<div class="box">
					<div class="author"><img class="authorimage" src="' . str_replace('_normal', '', $result->user->profile_image_url) . '"></div>
					<div class="authorname">@' . $result->user->screen_name . '</div><div class="timeago">' . timeAgo($result->created_at) . '</div>
					<div class="message"><div class="spicku">&nbsp;</div>' . $result->text . '</div>
				</div>';
			$count++;
	
			if($count >= $config['maxcount'])
				break;
				
		endif;
		
	}
	
	echo '</div>';

?>

	<h1 class="footer">Grüsse auch DU uns! <span class="hashtag">#CTWALL</span></h1>

	</body>
	
</html>
