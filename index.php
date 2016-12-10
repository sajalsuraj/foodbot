<?php

	$access_token = “EAAKesNV6Od0BAGAfmDoi4MvaqO8NfxZAwxCQjrOWurajvGZAiO7BD0LhsABiefd2EQhNeRcvsnPsib2pYPR4QppnucXpswUS38BjpqQpbuiVjo9TABeEZBRvaEtHrwfZCe15tAwMa6rHiz6B854oV3bxG6OZAbOaOq4GnJb2fEQZDZD”;

	$verify_token = “foodbot”;
	$hub_verify_token = null;
	if(isset($_REQUEST[‘hub_challenge’])) {
	 $challenge = $_REQUEST[‘hub_challenge’];
	 $hub_verify_token = $_REQUEST[‘hub_verify_token’];
	}
	if ($hub_verify_token === $verify_token) {
	  echo $challenge;
	}


	$input = json_decode(file_get_contents("php://input"), true);
	$sender = $input['entry'][0]['messaging'][0]['sender']['id'];

	$message = $input['entry'][0]['messaging'][0]['message']['text'];
	print_r($message);

	

 ?>