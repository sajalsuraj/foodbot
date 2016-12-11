<?php
ini_set("display_errors", true);
	
	$servername = "localhost";
	$username = "root";
	$password = "password";
	$dbname = "foodbot";
	// Create connection
	$conn = mysqli_connect($servername, $username, $password);
	// Check connection
	if (!$conn) {
	    die("Connection failed: " . mysqli_connect_error());
	}
	//echo "Connected successfully";
	$db = mysqli_select_db($conn,$dbname);
	if (!$db) {
	    die ('Can\'t use foo : ' . mysqli_error());
	}
	$access_token = "EAAKesNV6Od0BALSZB0E1oTWi0u4rRP2XLEZAnPyddFAqqoiZCkK8bZC14C4btuDqgwmwFGZBGHE7rTYkQCSTGxi2CMpHZCeiofJlFYjErbcCczlRg6TYmYwEN1Dh3xEbazGtXNZB8G8ZAYdvMmlbgFmJiZBG7wzPLxoXSHBjv9ZCVapwZDZD";
	$lat = '';
	$long = '';
	$verify_token = 'foodbot';
	$hub_verify_token = null;
	if(isset($_REQUEST['hub_challenge'])) {
	 $challenge = $_REQUEST['hub_challenge'];
	 $hub_verify_token = $_REQUEST['hub_verify_token'];
	}
	if ($hub_verify_token === $verify_token) {
	  echo $challenge;
	}
	$input = json_decode(file_get_contents("php://input"), true);
	$sender = $input['entry'][0]['messaging'][0]['sender']['id'];
	$message = $input['entry'][0]['messaging'][0]['message']['text'];
	
/*
	function send_message($msg, $token){
			echo $msg;
			//API Url
			$url = 'https://graph.facebook.com/v2.6/me/messages?access_token='.$token;
			//Initiate cURL.
			$ch = curl_init($url);
			//The JSON data.
			$jsonData = '{
			 "recipient":{
			 "id":"'.$sender.'"
			 },
			 "message":{
			 "text":"'.$msg.'"
			 }
			}';
			//Encode the array into JSON.
			$jsonDataEncoded = $jsonData;
			//Tell cURL that we want to send a POST request.
			curl_setopt($ch, CURLOPT_POST, 1);
			//Attach our encoded JSON string to the POST fields.
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
			//Set the content type to application/json
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(‘Content-Type: application/json’));
			//curl_setopt($ch, CURLOPT_HTTPHEADER, array(‘Content-Type: application/x-www-form-urlencoded’));
			//Execute the request
			if(!empty($input['entry'][0]['messaging'][0]['message'])){
			 	$result = curl_exec($ch);
			}
	}
*/
	
	if($message == ""){
		$coordinates = $input['entry'][0]['messaging'][0]['message']['attachments'][0]['payload']['coordinates'];
		if($coordinates == NULL){
		}
		else{
		
			 $lat =  $coordinates['lat'];
			 $long = $coordinates['long'];
	
			 
			
			if($lat != NULL){
				$sql = "INSERT INTO coords ( lat, long) VALUES ( '".$lat."', '".$long."' )";
                
                $a = mysqli_query( $conn, $sql );
                print_r($a);
				$msg = "Which kind of cuisine you would like to have ?";
				$url = "https://graph.facebook.com/v2.6/me/messages?access_token=".$access_token;
					//Initiate cURL.
					
				$ch = curl_init($url);
				
				// //The JSON data.
				 $jsonData = '{ "recipient":{ "id":"'.$sender.'" }, "message":{ "text":"'.$msg.'",  "quick_replies":[{"content_type":"text", "title":"North Indian", "payload":"hello"}, 
				                      {"content_type":"text", "title":"Chinese", "payload":"hello"}, 
				                      {"content_type":"text", "title":"Fast Food", "payload":"hello"}, 
				                      {"content_type":"text", "title":"Cafe", "payload":"hello"}, 
				                      {"content_type":"text", "title":"Continental", "payload":"hello"} 
				                      ]}}';
				 // print_r($jsonData);
				// //Encode the array into JSON.
				 $jsonDataEncoded = $jsonData;
				// //Tell cURL that we want to send a POST request.
				 curl_setopt($ch, CURLOPT_POST, 1);
				// //Attach our encoded JSON string to the POST fields.
				 curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
				// //Set the content type to application/json
				 curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				// //Execute the request
			 	$result = curl_exec($ch);
			}
		}
	}
	elseif(preg_match('[hello|hey|hi]', strtolower($message))){
		
		$msg = "Hey, Seems you are hungry. Please share your location so that we can suggest you the best restaurant around you. Thanks";
		
		$url = "https://graph.facebook.com/v2.6/me/messages?access_token=".$access_token;
			//Initiate cURL.
			
		$ch = curl_init($url);
		
		// //The JSON data.
		 $jsonData = '{ "recipient":{ "id":"'.$sender.'" }, "message":{ "text":"'.$msg.'", "quick_replies":[{"content_type":"location"}]}}';
		// //Encode the array into JSON.
		 $jsonDataEncoded = $jsonData;
		// //Tell cURL that we want to send a POST request.
		 curl_setopt($ch, CURLOPT_POST, 1);
		// //Attach our encoded JSON string to the POST fields.
		 curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
		// //Set the content type to application/json
		 curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		// //Execute the request
	 	$result = curl_exec($ch);
	}
	elseif((strpos($message, 'North Indian') !== false) || (strpos($message, 'Chinese') !== false) || (strpos($message, 'Cafe') !== false) || (strpos($message, 'Fast Food') !== false) || (strpos($message, 'Continental') !== false)){
				$lat = 12.933145;
				$lng = 77.611979;
				$preference = ["".$message];
				// Get cURL resource
				$ch = curl_init();
				$url = 'https://developers.zomato.com/api/v2.1/geocode?lat='.$lat.'&lon='.$lng;
				// print_r($url);
				curl_setopt($ch,CURLOPT_URL,$url);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
				curl_setopt($ch,CURLOPT_HTTPHEADER,array("user-key: e04c5f6b85e4647beb82165436d3823f"));
				// Send the request & save response to $resp
				// var_dump($ch);
				$resp = curl_exec($ch);
				// echo $resp;
				$json = json_decode($resp, true);
				//print_r($resp);
				//die();
				//print_r($json['nearby_restaurants'][0]['restaurant']);
				$msg = "Here we have awesome suggestions for you";
				
							                
			    $jsonData = '{
							  "recipient":{
							    "id":"'.$sender.'"
							  },
							  "message":{
							    "attachment":{
							      "type":"template",
							      "payload":{
							        "template_type":"generic",
							        "elements":[';                
							            
				for ($i=0;$i<sizeof($json['nearby_restaurants']);$i++){
					$cuisines =explode(',',$json['nearby_restaurants'][$i]['restaurant']['cuisines']);
					$result = array_intersect($preference, $cuisines);
					if($result!=null){
						$name = $json['nearby_restaurants'][$i]['restaurant']['name'];
						$url = $json['nearby_restaurants'][$i]['restaurant']['url'];
						$featuredimg = $json['nearby_restaurants'][$i]['restaurant']['featured_image'];
						// restaurant according to preference
						
						$jsonData .= '{
							            "title":"'.$name.'",
							            "item_url":"'.$url.'",
							            "image_url":"'.$featuredimg.'",
							            "subtitle":"Weve got the right hat for everyone.",
							            "buttons":[
							              {
							                "type":"web_url",
							                "url":"'.$url.'",
							                "title":"View Website"
							              },
							              {
							                "type":"postback",
							                "title":"Start Chatting",
							                "payload":"Hello"
							              }              
							            ]
							          },';
						
					}
				}
				// Close request to clear up some resources
				
				curl_close($ch);
				
				$jsonData .= ']
							      }
							    }
							  }}';

		
				$url = "https://graph.facebook.com/v2.6/me/messages?access_token=".$access_token;
					//Initiate cURL.
					
				$ch = curl_init($url);
				
				// //The JSON data.
				// //Encode the array into JSON.
				 $jsonDataEncoded = $jsonData;
				// //Tell cURL that we want to send a POST request.
				 curl_setopt($ch, CURLOPT_POST, 1);
				// //Attach our encoded JSON string to the POST fields.
				 curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
				// //Set the content type to application/json
				 curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				// //Execute the request
			 	$result = curl_exec($ch);
	
	}
 ?>
