<?

/*
This function accepts a $username/$password pair and attempts to authenticate against 
the GeoPass server. If successful, it returns an array containing all user properties.
If unsuccessful, it sets the global $geopassloginerror variable with the reason for
failure.
Author: Jason Ash 08/12/2015
*/

define("JOSSOSERVER",     "https://geopass.iedadata.org");

function geopasslogin($username,$password){

	global $geopassloginerror;

	if($username == "" || $password == ""){
		$geopassloginerror = "Username/Password cannot be blank.";
		return false;
	}
	
	$sc = new Soapclient(JOSSOSERVER .'/josso/services/SSOIdentityProviderSoap?wsdl', array('trace' => 1));

	// First,  assertIdentityWithSimpleAuthentication. This returns an assertionId if successful.
	try{ 
		$vars=array('securityDomain'=>'josso','username'=>$username,'password'=>$password);
		$req = $sc->assertIdentityWithSimpleAuthentication($vars); 
		$assertionid = $req->assertionId; 
	} catch(Exception $e){ 
		$geopassloginerror = "Invalid Username/Password.";
		return false;
	} 

	// Next,  resolveAuthenticationAssertion with assertionId. This returns a sessionid if successful.
	if($assertionid!=""){
		try{ 
			$vars=array('assertionId'=>$assertionid);
			$req = $sc->resolveAuthenticationAssertion($vars); 
			$sessionid=$req->ssoSessionId;
		} catch(Exception $e){ 
			$geopassloginerror = "Error resolving authentication assertion.";
			return false;
		} 
	}else{
		$geopassloginerror = "Error getting AssertionId.";
		return false;
	}

	// Now, if sessionId is acquired, it can be used to gather user details.
	$sc = new Soapclient(JOSSOSERVER .'/josso/services/SSOIdentityManagerSoap?wsdl', array('trace' => 1));

	if($sessionid!=""){
		try{ 
			$vars=array('ssoSessionId'=>$sessionid);
			$req = $sc-> findUserInSession($vars); 
			$rows = $req->SSOUser->properties;

			//put $rows into a named array for ease of use.
			$userdata=array();
			foreach($rows as $row){
				eval("\$userdata[\"".strtolower($row->name)."\"]=\$row->value;");
			}
			return $userdata;
		} catch(Exception $e){ 
			$geopassloginerror = "Error gathering user details.";
			return false;
		} 
	}else{
		$geopassloginerror = "Error getting SessionId.";
		return false;
	}

}

?>