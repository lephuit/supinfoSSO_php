<?php
require('class.ssoResponse.php');
require('class.ssoQuery.php');

if ($_POST['openid_action'] == "login"){ // Get identity from user and redirect browser to OpenID Server
	$openid = new ssoQuery;
	$openid->SetIdentity($_POST['openid_url']);
	$openid->SetApprovedURL('http://' . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"]);
	$openid->SetTrustRoot('http://' . $_SERVER["HTTP_HOST"]);
	$openid->Redirect(); 
	exit;
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>SUPINFO SSO Example</title>
	<meta charset='utf-8'>
	<style>
	#openid{
		border: 1px solid gray;
		display: inline;
	}
	#openid, #openid INPUT{
		font-family: "Trebuchet MS";
		font-size: 12px;
	}
	#openid LEGEND{
		1.2em;
		font-weight: bold;
		color: black;
		padding-left: 5px;
		padding-right: 5px;
	}
	#openid INPUT.openid_login{
	   background-color: #fff;
	   background-position: 0 50%;
	   color: #000;
	   width: 220px;
	   margin-right: 10px;
	}
	#openid A{
	color: silver;
	}
	#openid A:hover{
		color: #5e5e5e;
	}
</style>
</head>

<body>

<div>
	<?php
	var_dump($_GET);
	if(isset($_GET['openid_identity']))
	{
		$response = new ssoResponse;
		$response->setGetData($_GET);
		if($response->isInCampus('60383','SUPINFO Rhône-Alpes - Lyon'))
		{
			echo('Vous êtes bien au campus de lyon !<br/>');
		}
		if($response->isInCursus(2))
		{
			echo('Vous êtes bien en B2 !<br/>');
		}
		if($response->isRole('Student'))
		{
			echo('Vous êtes bien un Eleve !<br/>');
		}
		if($response->isRank('Advanced'))
		{
			echo('Vous êtes bien Advanced Member !<br/>');
		}
	}
	else
	{
	?>
	<fieldset id="openid">
		<legend>CampusID</legend>
		<form action="<?php echo 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"]; ?>" method="post">
		<input type="hidden" name="openid_action" value="login">
			<div>
				<input type="text" name="openid_url" class="openid_login">
				<input type="submit" name="login" value="login &gt;&gt;">
			</div>
		</form>
	</fieldset>
	<?php
	}
	?>
</div>

</body>
</html>