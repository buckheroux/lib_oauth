<?php
// Oauth Consumer
// Written by Buck Heroux
// using the adapter pattern around oauth_lib 

require('lib_oauth'.EXT);

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
		'oauth_key'		=> $this->consumer_key,
		'oauth_secret'		=> $this->consumer_secret,
		'oauth_callback'	=> $callback
	);

	$ok = oauth_get_auth_token($keys, $req_url);

	if ($ok) {
		return oauth_get_auth_url($keys, $auth_url);
	} else {
		throw new Exception('Request Token Failed');
	}
    }
    
    function getAccessToken($oauth_token, $oauth_secret, $access_url) {
	$keys = array(
		'oauth_key'		=> $this->consumer_key,
		'oauth_secret'		=> $this->consumer_secret,
		'request_key'		=> 	$oauth_token,
		'request_secret'	=>	$oauth_secret
	);	
	$ok = oauth_get_access_token($keys, $access_url);
	if ($ok) {
		$this->access_token = $keys['user_key'];
		$this->access_secret =  $keys['user_secret'];
		$return = array(
			'access_token'	=> $keys['user_key'],
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
		'oauth_key'	=> $this->consumer_key,
		'oauth_secret'	=> $this->consumer_secret,
		'user_key'	=> $this->access_token,
		'user_secret'	=> $this->access_secret
	);
    	return oauth_request($keys, $url);
    }

}
?>
