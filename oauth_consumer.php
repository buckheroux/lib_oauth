<?php
// Oauth Consumer
// Written by Buck Heroux
/*
	//request token step
	$consumer = new Oauth_Consumer(CONSUMER_KEY, CONSUMER_SECRET);
	$url = $consumer->getSignedAuthURL(REQUEST_URL, AUTHORIZATION_URL, CALLBACK_URL);
	header('Location: '.$url);
	exit;

	//on callback
	$consumer = new Oauth_Consumer(CONSUMER_KEY, CONSUMER_SECRET);
	$consumer->getAccessToken($_GET['oauth_token'], $_GET['oauth_verifier'], ACCESS_URL);
	$response = $consumer->signRequest('http://timecube.com/some_protected_method');
	
	//or if you already have the access token/secret saved
	$consumer = new Oauth_Consumer(CONSUMER_KEY, CONSUMER_SECRET);
	$consumer->setAccessToken(ACCESS_TOKEN, ACCESS_SECRET);
	$response = $consumer->signRequest('http://timecube.com/some_protected_method');
		
*/
// using the adapter pattern around oauth_lib 


require(APPPATH.'libraries/oauth'.EXT);


class Oauth_Consumer {

	var $consumer_key = '';
	var $consumer_secret = '';
	var $access_token = '';
	var $access_secret = '';
	
    function __construct($key, $secret) { 
    	$this->consumer_key = $key;
    	$this->consumer_secret = $secret;
    } 

    function getAuthURL($req_url, $auth_url, $callback) {
    	$keys = array(
			'oauth_key'			=> $this->consumer_key,
			'oauth_secret'		=> $this->consumer_secret,
			'oauth_callback'	=> $callback
		);

		$ok = oauth_get_auth_token($keys, $req_url);

		if ($ok){
			return oauth_get_auth_url($keys, $auth_url);
		} else {
			throw new Exception('Request Token Failed');
		}
    }
    
    function getAccessToken($oauth_token, $oauth_secret, $access_url) {
		$keys = array(
			'oauth_key'			=> $this->consumer_key,
			'oauth_secret'		=> $this->consumer_secret,
			'request_key'		=> 	$oauth_token,
			'request_secret'	=>	$oauth_secret
		);	
		$ok = oauth_get_access_token($keys, $access_url);
		if ($ok){
			$this->access_token = $keys['user_key'];
			$this->access_secret =  $keys['user_secret'];
			$return = array(
				'access_token' => $keys['user_key'],
				'access_secret' => $keys['user_secret']	
			);
			return $return;
		} else {
			throw new Exception('Access Token Failed');
		}
    }
    
    function setAccessToken($token, $secret) {
    	$this->access_token = $token;
		$this->access_secret =  $secret;
    }
    
    function signRequest($url) {
    	$keys = 
    	array(
			'oauth_key'		=> $this->consumer_key,
			'oauth_secret'	=> $this->consumer_secret,
			'user_key'		=> $this->access_token,
			'user_secret'	=> $this->access_secret
		);
    	return oauth_request($keys, $url);
    }

}

class Oauth_Signer {

	var $access_token = '';
	var $access_secret = '';
	
	function __construct($key, $secret) { 
    	$this->access_token = $key;
    	$this->access_secret = $secret;
    } 

}
?>
