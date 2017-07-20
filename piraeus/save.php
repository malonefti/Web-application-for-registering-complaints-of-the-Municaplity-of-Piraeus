<?php
include_once ("db.php");
require_once('library/recaptchalib.php');
session_start();

if (isset($_POST['name'])){
	$name=$_POST['name'];
}
if (isset($_POST['lastname'])){
	$lastname=$_POST['lastname'];
}
if (isset($_POST['email'])){
	$mail=$_POST['email'];
}
if (isset($_POST['phone'])){
	$phone=$_POST['phone'];
	$phone_length = strlen((string)$phone);
}
if (isset($_POST['address'])){
	$address=$_POST['address'];		
}
if (isset($_POST['number'])){
	$number=$_POST['number'];
	$num_length = strlen((string)$number);
}
if (isset($_POST['postCode'])){
	$postCode=$_POST['postCode'];
	$postCode_length = strlen((string)$postCode);
}
if (isset($_POST['info'])){
	$info=$_POST['info'];
	$info_length = strlen((string)$info);
}
if(!isset($_SESSION["form"])){
	$_SESSION["form"] = array();
}
$formFields=array($name, $lastname, $mail,$phone,$address,$number,$postCode, $info);
array_push($_SESSION["form"], $formFields);
session_regenerate_id(true);
$validTags=array("name"=>"string", "lastname"=>"string", "email"=>"string","phone"=>"string","address"=>"string",
				"number"=>"integer","postCode"=>"integer", "info"=>"string","g-recaptcha-response"=>"recaptcha");
$validMethod = "POST";
$errMsg = "";
function isValidRequest() {
	global $validTags;
	global $validMethod;
	global $errMsg;
	switch($_SERVER['REQUEST_METHOD']) {
		case "GET" :
		{
		$cnt = count($_GET); 
		break;
		}
		case "POST" :
		{
		$cnt = count($_POST); 
		break;
		}
		default:
		$cnt = -1;
	}
	if($validMethod != $_SERVER['REQUEST_METHOD'])
	{
		$errMsg = "method";
		return false;
	}
	if($cnt != count($validTags)) {
		$errMsg = "blank";
		return false;
	}
	foreach($validTags as $key => $value) {
		if(isset($_REQUEST[$key])) {
			global ${$key};
			${$key} = $_REQUEST[$key];
			switch($value) {
				case "string" : {
					if(strlen(${$key}) < 1) {
						$errMsg = "blank";
						return false;
					}
				break;
				}
				case "integer" :
				{
					if(empty(${$key}) || (${$key} < 1))
					{
						$errMsg = "int";
						return false;
					}
				break;
				}
			}
		}
		else {
			$errMsg = "wrong";
			return false;
		}
	}
	return true;
}

if(!isValidRequest())
{
	//echo $errMsg;
	header("Location:index.php?error={$errMsg}");
}
else{
	$pattern = '/^[\x{0386}-\x{03CE}\x]{3,}+$/u';
	/*Έλεγχος Ονομάτων*/
		if((filter_var($name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>$pattern))) === false))
		{
			$error= "name";
			header("Location:index.php?error={$error}");	
		}
		else 
		{
			if((filter_var($lastname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>$pattern))) === false))
			{
				$error= "lastname";
				header("Location:index.php?error={$error}");	
			}
			else 
			{
				/*Έλεγχος Email*/	
				/*Ο έλεγχος δουλεύει όταν στο input του email αλλάξουμε το type από email σε text ή όταν το πεδίο είναι κενό*/
				if(filter_var($mail, FILTER_VALIDATE_EMAIL) === false){
					$error= "mail";
					header("Location:index.php?error={$error}");
				}
				else
				{
					/*Έλεγχος Τηλεφώνου*/
					if (($phone_length >10) ||(filter_var($phone, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^(69)+\\d{8}/"))) === false)){
						$error= "phone";
						header("Location:index.php?error={$error}");
					}
					else{	
						/*Έλεγχος Διεύθυνσης*/
						if(filter_var($address, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>$pattern))) === false){
							$error= "address";
							header("Location:index.php?error={$error}");
						}
						else if ((filter_var($number, FILTER_VALIDATE_INT)) && ($num_length >3)) {
								$error= "number";
								header("Location:index.php?error={$error}");
							}
							else if ((filter_var($postCode, FILTER_VALIDATE_INT)) && ($postCode_length>5) || (filter_var($postCode, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^(18)+\\d{3}/"))) === false)) {
								$error= "postCode";
								header("Location:index.php?error={$error}");
							}
							else{
								$pattern = '/^[\x{0386}-\x{03CE}\s\w\.\,\;\']{10,}+$/u';
								/*Έλεγχος Μηνύματος*/
								if ((filter_var($info, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>$pattern))) === false)){
									$error= "info";
									header("Location:index.php?error={$error}");
								}			
								else{
									/*Έλεγχος Capthca*/
									$publickey = "6LfldhETAAAAAHxdsXItlDGCbIOWX4YOUxSjwToT";
									$privatekey = "6LfldhETAAAAAEF2mluNEnejyIf8gGnahQriIzgq";
									$response = null;
									$reCaptcha = new ReCaptcha($privatekey);
									if ($_POST["g-recaptcha-response"]) {
										$response = $reCaptcha->verifyResponse(
											$_SERVER["REMOTE_ADDR"],
											$_POST["g-recaptcha-response"]
										);
									}
									if ($response != null && $response->success) {
										mysql_query("SET @name = " . "'" . mysql_real_escape_string($name) . "'");	
										mysql_query("SET @lastname = " . "'" . mysql_real_escape_string($lastname) . "'");	
										mysql_query("SET @email = " . "'" . mysql_real_escape_string($mail) . "'");	
										mysql_query("SET @phone = " . "'" . mysql_real_escape_string($phone) . "'");	
										mysql_query("SET @address = " . "'" . mysql_real_escape_string($address) . "'");	
										mysql_query("SET @number = " . "'" . mysql_real_escape_string($number) . "'");	
										mysql_query("SET @postCode = " . "'" . mysql_real_escape_string($postCode) . "'");	
										mysql_query("SET @message = " . "'" . mysql_real_escape_string($info) . "'");	
										$result = mysql_query("CALL isValidInput(@name,@lastname,@email,@phone,@address,@number,@postCode,@message);");		
										if ($result) 
										{
											$Message = "success";
											header("Location:index.php?Message={$Message}");
											unset($_SESSION["form"]);	
											mysql_close($dbcon);
										} 
										else 
										{
											$error= "db";
											/*echo mysql_errno($dbcon) . ": " . mysql_error($dbcon) . "\n";*/
											header("Location:index.php?error={$error}");
										}		
										
									} 
									else 
									{
										$error= "captcha";
										header("Location:index.php?error={$error}");
									}
								} 
							}
						}
					}
				}
			}
}		
?>

