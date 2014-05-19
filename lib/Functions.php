<?php

use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookSession;
use Facebook\FacebookRequestException;
use Facebook\FacebookJavaScriptLoginHelper;
use Facebook\FacebookCanvasLoginHelper;
use Facebook\FacebookSDKException;

class PromotionsFacebook_Functions
{
  protected $id;
  protected $secret;
  protected $tab;
  protected $user;
  protected $session;
  protected $signedRequest;
  
  public function init( $id, $secret )
  {
    $this->id = $id;
    $this->secret = $secret;
    FacebookSession::setDefaultApplication($id, $secret);
  }
  
  public function get_session()
  {
    if( isset( $this->session ) ){
      return $this->session;
    }
    if( isset( $_REQUEST['signed_request'] ) ){
      $helper = new FacebookCanvasLoginHelper();
    }
    else{
      $helper = new FacebookJavaScriptLoginHelper();
    }
    try {
      $this->session = $helper->getSession();
    } catch(FacebookRequestException $ex) {
      // When Facebook returns an error
      $this->session = false;
    } catch(\Exception $ex) {
      // When validation fails or other local issues
      $this->session = false;
    }
    return $this->session;
  }
  
  public function get_user()
  {
    if( isset( $this->user ) ) return $this->user;
    
    $session = $this->get_session();
    if( !$session ){
      $this->user = false;
      return $this->user;
    }
    try {
      
      $this->user = (new FacebookRequest(
        $session, 'GET', '/me'
      ))->execute()->getGraphObject(GraphUser::className());
      
      return $this->user;
    }
    catch( FacebookRequestException $e ){
      return false;
    }
    
  }
  
  public function is_tab()
  {
    if( isset( $this->tab ) ) return $this->tab;
    $this->tab = false;
    $sr = $this->get_signed_request();
    if( !$sr ) return false;
    if( !isset( $sr['page'] ) ) return false;
    return true;
  }
  
  public function is_liked()
  {
    if( isset( $this->liked ) ) return $this->liked;
    $this->liked = false;
    $sr = $this->get_signed_request();
    if( !$sr ) return false;
    if( !isset( $sr['page'] ) ) return false;
    $this->liked = $sr['page']['liked'];
    return $this->liked;
  }
  
  public function get_signed_request()
  {
    if( isset( $this->signedRequest ) ) return $this->signedRequest;
    $this->signedRequest = false;
    $session = $this->get_session();
    if( !$session ) return false;
    if( !($sr = $_REQUEST['signed_request']) ) return false;
    $this->signedRequest = $this->parseSignedRequest( $sr );
    return $this->signedRequest;
  }
  
  /**
   * Parses a signed request.
   *
   * @param string $signedRequest
   * @param string $state
   *
   * @return array
   *
   * @throws FacebookSDKException
   */
  public function parseSignedRequest($signedRequest, $state)
  {
    if (strpos($signedRequest, '.') !== false) {
      list($encodedSig, $encodedData) = explode('.', $signedRequest, 2);
      $sig = self::_base64UrlDecode($encodedSig);
      $data = json_decode(self::_base64UrlDecode($encodedData), true);
      if (isset($data['algorithm']) && $data['algorithm'] === 'HMAC-SHA256') {
        $expectedSig = hash_hmac(
          'sha256', $encodedData, $this->secret, true
        );
        if (strlen($sig) !== strlen($expectedSig)) {
          throw new FacebookSDKException(
            'Invalid signature on signed request.', 602
          );
        }
        $validate = 0;
        for ($i = 0; $i < strlen($sig); $i++) {
          $validate |= ord($expectedSig[$i]) ^ ord($sig[$i]);
        }
        if ($validate !== 0) {
          throw new FacebookSDKException(
            'Invalid signature on signed request.', 602
          );
        }
        if (!isset($data['oauth_token']) && !isset($data['code'])) {
          throw new FacebookSDKException(
            'Invalid signed request, missing OAuth data.', 603
          );
        }
        if ($state && (!isset($data['state']) || $data['state'] != $state)) {
          throw new FacebookSDKException(
            'Signed request did not pass CSRF validation.', 604
          );
        }
        return $data;
      } else {
        throw new FacebookSDKException(
          'Invalid signed request, using wrong algorithm.', 605
        );
      }
    } else {
      throw new FacebookSDKException(
        'Malformed signed request.', 606
      );
    }
  }
  
  /**
   * Base64 decoding which replaces characters:
   *   + instead of -
   *   / instead of _
   * @link http://en.wikipedia.org/wiki/Base64#URL_applications
   *
   * @param string $input base64 url encoded input
   *
   * @return string The decoded string
   */
  public static function _base64UrlDecode($input) {
    return base64_decode(strtr($input, '-_', '+/'));
  }
  
}
