#Oauth Consumer Doc

Written By Buck Heroux  

Object wrapper for lib_oauth by Cal Henderson

##Constructor Oauth_Consumer(consumer key, consumer secret)  
-----------------------------------------------------------
        $consumer = new Oauth_Consumer(CONSUMER_KEY, CONSUMER_SECRET);

###Description 
- Constructs a new Oauth_Consumer using a consumer key and secret  

###Parameters
- Consumer Key: Consumer Key given by the Oauth Provider  
- Consumer Secret: Consumer Secret given by the Oauth Provider  
 
###Return
- Oauth_Consumer object  

##getAuthUrl(request_url, authorization_url, callback_url)
--------------------------------------------------------

        $auth_url = $consumer->getAuthUrl(REQUEST_URL, AUTH_URL, CALLBACK_URL);

###Description
- Retrieves the url to be redirected to for the authorization process of the oauth handshake

###Parameters
- Request URL: URL given by the provider for request token exchange
- Authorization URL: Base URL given by the provider for the consumer to sign for Authorization
- Callback URL: URL to be redirected to after the authorization process

###Return
- Signed Authorization URL: The signed auth url to be redirected to for authorization

##getAccessToken(token, secret, access_url) 
-------------------------------------------

        $access = $consumer->getAccessToken(TOKEN, SECRET, ACCESS_URL);

###Description
- Retrieves access token from the service provider

###Parameters
- Token: The Oauth_Token from the provider on the callback
- Secret: Secret for the returned token
- Access URL: URL from the provider for the access token exchange 

###Return
- AccessToken/Secret: An associative array containing 'access_token' and 'access_token_secret'

##setAccessToken(access_token, access_secret)
---------------------------------------------

	$consumer->setAccessToken(ACCESS_TOKEN, ACCESS_TOKEN_SECRET);

###Description
- Sets the access token for the consumer to sign requests. Used if the access token has already been retrieved

###Parameters
- Access Token: Stored access token to sign requests with
- Access Token Secret: Secret string associated with token

###Return
- Void: Nothing returns, used to sign requests

##signRequest($url)
-------------------
###Description 

	$response = $consumer->signRequest('http://timecube.com/protected?answer=42');

- Signs the requests for the specified protected reqource of the provider. Used once the Oauth_Consumer has been assoicated with an Access Token via getAccessToken() or setAccessToken()

###Parameters
- URL: Protected resource from the provider to be signed

###Return
- Reponse: The reponse of the provider from the request

##Examples
###Full Oauth Handshake
	//request token step
	$consumer = new Oauth_Consumer(CONSUMER_KEY, CONSUMER_SECRET);
	$url = $consumer->getSignedAuthURL(REQUEST_URL, AUTHORIZATION_URL, CALLBACK_URL);
	header('Location: '.$url);
	exit;

	//on callback
	$consumer = new Oauth_Consumer(CONSUMER_KEY, CONSUMER_SECRET);
	$consumer->getAccessToken($_GET['oauth_token'], $_GET['oauth_verifier'], ACCESS_URL);
	$response = $consumer->signRequest('http://timecube.com/some_protected_method');

###Known Access Token/Secret
	//if you already have the access token/secret saved
	$consumer = new Oauth_Consumer(CONSUMER_KEY, CONSUMER_SECRET);
	$consumer->setAccessToken(ACCESS_TOKEN, ACCESS_SECRET);
	$response = $consumer->signRequest('http://timecube.com/some_protected_method');
