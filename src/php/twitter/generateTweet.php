<?php
session_start();

require_once ('../helper/logger.php');
require_once ('twitteroauth/twitteroauth.php');	

function tweet_post($tweet) {
	if (strlen ( $tweet ) > 140)
		logToFile('Teet is to long'); 
	$hasPost = true; 
	$tweetOauth = new
	TwitterOAuth ( $_SESSION['tw_consumer_key'], $_SESSION['tw_consumer_secret'], $_SESSION['tw_access_token'], $_SESSION['tw_access_token_secret'] ); // connect with the OAuth
	
	$tweetOauth->oAuthRequest ( 'https://api.twitter.com/1.1/statuses/update.json', 'POST', array (
			'status' => $tweet 
	) ); // request tweet post with the OAuth connection 
	if($tweetOauth->http_code != 200 ) { // Check if has some error 
	$hasPost = false;
	logToFile('Received errorcode: '.$tweetOauth->http_code);
	} else {
		logToFile('Everythings fine with the received errorcode.');
	}
	return $hasPost;
} 
function getEmailsFromUser($user){
	// TODO: DB here
	return array('email1@email.com', 'email2@email.com');
}
if(isset($_SESSION['user'])){
	$message = $_SESSION['user']." is watching ".$_SESSION['tv_show'].". If you want to join visit: http://localhost/php/twitter/twitterRedirect.php?tvsid=".$_SESSION['tv_session_id'];
	tweet_post( $message ); 
	$emails = getEmailsFromUser($_SESSION['user']);
	foreach ($emails as $email){
		// TODO: Mail here
		// mail($email, $_SESSION['tv_show'], $message);
		logToFile("Mail to: ".$email."\n"); 
	}
	$_SESSION['notificated'] = true;
	session_commit();
} else {
	logToFile('For Session '.session_id().', user is not set.');
}
header('Location: http://localhost/php/index.php');	
?>