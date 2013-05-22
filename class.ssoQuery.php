<?php
/*
 * SSO_Supinfo_PHP V1.0 
 * Auteurs : HUYNH Eddy <Eddy.HUYNH@supinfo.com> et CHEVALIER Alexis <Alexis.CHEVALIER@supinfo.com>
 * Cette classe est insiprée de la classe suivante pour le fonctionnement d'openID :
 * http://www.phpclasses.org/package/3290-PHP-Authenticate-users-with-OpenID-single-sign-on.html
 * Cette classe sert à envoyer la requête OpenID au serveur de SUPINFO, avec l'url de redirection
*/
class ssoQuery{
	var $openid_url_identity;
	var $URLs = array('openid_server' => 'https://id.supinfo.com/Server.aspx');
	var $error = array();
	var $fields = array('required' => 'fullname');
	
	function SetOpenIDServer($a){
		$this->URLs['openid_server'] = $a;
	}
	function SetTrustRoot($a){
		$this->URLs['trust_root'] = $a;
	}
	function SetCancelURL($a){
		$this->URLs['cancel'] = $a;
	}
	function SetApprovedURL($a){
		$this->URLs['approved'] = $a;
	}
	/* Cette fonction permet de créer l'identité SSO en fonction du campusID fourni */
	function SetIdentity($a){ 	// Set Identity URL
		 	$a = 'https://id.supinfo.com/me/'.$a;
			$this->openid_url_identity = $a;
	}
	function GetIdentity(){ 	// Get Identity
		return $this->openid_url_identity;
	}
	
	function array2url($arr){ // converts associated array to URL Query String
		if (!is_array($arr)){
			return false;
		}
		foreach($arr as $key => $value){
			$query .= $key . "=" . $value . "&";
		}
		return $query;
	}
	function GetRedirectURL(){
		$params = array();
		$params['openid.return_to'] = urlencode($this->URLs['approved']);
		$params['openid.mode'] = 'checkid_setup';
		$params['openid.identity'] = urlencode($this->openid_url_identity);
		$params['openid.trust_root'] = urlencode($this->URLs['trust_root']);
		$params['openid.sreg.required'] = implode(',',$this->fields['required']);
		return $this->URLs['openid_server'] . "?". $this->array2url($params);
	}
	
	function Redirect(){
		$redirect_to = $this->GetRedirectURL();
		if (headers_sent()){ // Use JavaScript to redirect if content has been previously sent (not recommended, but safe)
			echo '<script language="JavaScript" type="text/javascript">window.location=\'';
			echo $redirect_to;
			echo '\';</script>';
		}else{	// Default Header Redirect
			header('Location: ' . $redirect_to);
		}
	}

}

?>