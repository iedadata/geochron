<?PHP
/**
 * inviteusers.php
 *
 * longdesc
 *
 * LICENSE: This source file is subject to version 4.0 of the Creative Commons
 * license that is available through the world-wide-web at the following URI:
 * https://creativecommons.org/licenses/by/4.0/
 *
 * @category   Geochronology
 * @package    Geochron Portal
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  IEDA (http://www.iedadata.org/)
 * @license    https://creativecommons.org/licenses/by/4.0/  Creative Commons License 4.0
 * @version    GitHub: $
 * @link       http://www.geochron.org
 * @see        Geochron, Geochronology
 */

$chars=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","0","1","2","3","4","5","6","7","8","9");

function check_email_address($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if
(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
      {
         // domain not found in DNS
         $isValid = false;
      }
   }
   return $isValid;
}

session_start();

include("db.php");

include("logincheck.php");

$username=$db->get_var("select email from users where users_pkey=$userpkey");

include("includes/geochron-secondary-header.htm");

include("db.php");



//look for POSTed 'groupname' and add it if it exists
if($_POST['groupname']!=""){
	$p=$_POST['p'];
	$groupname=$_POST['groupname'];
	
	//first, let's look to see if this group already exists
	$groupcount=$db->get_var("select count(*) from groups where groupname='$groupname' and users_pkey=$userpkey");
	if($groupcount>0){
		//error here
		?>
		<h1>Error!</h2>
		The group '<?=$groupname?>' already exists in the database.<br><br>
		
		Please go <a href="javascript:history.go(-1)">back</a> and try again.
		<br><br><br><br><br>
		<br><br><br><br><br>
		<br><br><br><br><br>
		<br><br><br><br><br>
		<br><br><br><br><br>
		<br><br><br><br><br>
		<br><br><br><br><br>

		

		
		
		<?
		
		include("includes/geochron-secondary-footer.htm");
		exit();
	}else{
		//group doesn't exist, so let's put it in here
		$group_pkey=$db->get_var("select nextval('groups_seq')");
		$db->query("insert into groups (group_pkey,users_pkey,groupname)values($group_pkey,$userpkey,'$groupname')");
	}
	
}elseif($_GET['group_pkey']!=""){

	$group_pkey=$_GET['group_pkey'];
	$p=$_GET['p'];
	$groupname=$db->get_var("select groupname from groups where group_pkey=$group_pkey");
	if($groupname==""){
		echo "<h1>Error!</h1><br>";
		echo "Group not found!";
		include("includes/geochron-secondary-footer.htm");
		exit();
	}
	
}elseif($_POST['group_pkey']!=""){

	$group_pkey=$_POST['group_pkey'];
	$p=$_POST['p'];
	$groupname=$db->get_var("select groupname from groups where group_pkey=$group_pkey");
	if($groupname==""){
		echo "<h1>Error!</h1><br>";
		echo "Group not found!";
		include("includes/geochron-secondary-footer.htm");
		exit();
	}
	

}else{
	echo "<h1>Error!</h1><br>";
	echo "Group not found!";
	include("includes/geochron-secondary-footer.htm");
	exit();
}



//now check for POSTed userslist and add users as necessary
if($_POST['userlist']!=""){

	$p=$_POST['p'];
	
	$errormessage="";
	$errormessagedelim="";

	$userlist=$_POST['userlist'];
	
	//OK, now check each line and verify that it is an email address and 
	// that it exists in the user table.
	$users=explode("\r",$userlist);
	
	$linenum=1;
	
	
	//roll over users once to check for valid email addresses
	foreach($users as $user){
		$user=str_replace(" ","",$user);
		$user=str_replace("\n","",$user);

		
		//check email
		
		if($user!=""){
    	
			if(check_email_address($user)){
	
				//OK, email is valid format, now check to see if it exists in the database
				
			}else{
				$errormessage.=$errormessagedelim."Invalid email address on line $linenum *$user*";
				$errormessagedelim="<br>";
			}
		
		}
		
		
		$linenum++;
	}




	//roll over users to check for duplicates
	
	$userarray=array();
	
	foreach($users as $user){
		$user=str_replace(" ","",$user);
		$user=str_replace("\n","",$user);


		if($user!=""){
    	
			//check for email in array
			if(in_array($user, $userarray)){
			
				$errormessage.=$errormessagedelim."Duplicate entries for $user in list. Please try again.";
				$errormessagedelim="<br>";
			
			}
			
			$userarray[]=$user;
		
		}
		
		
		$linenum++;
	}


	if($errormessage==""){
		//roll over users to see if valid users
		$linenum=1;
		
		$userarray=array();
		
		foreach($users as $user){
			$user=str_replace(" ","",$user);
			$user=str_replace("\n","",$user);
	
	
			if($user!=""){
			
				//check for user in users table
				$usercount=$db->get_var("select count(*) from users where email='$user'");
				
				if($usercount==0){
				
					$errormessage.=$errormessagedelim."Error (line $linenum) $user is not a valid Geochron user. Please try again.";
					$errormessagedelim="<br>";
				
				}
			
			}
			
			
			$linenum++;
		}
	}



	//if no errors, let's roll over them again to check if they already exist in the database
	if($errormessage==""){
	
		$linenum=1;

		foreach($users as $user){
			$user=str_replace(" ","",$user);
			$user=str_replace("\n","",$user);
	
			
			//check email
			
			if($user!=""){
			
				$count=$db->get_var("select count(*) from grouprelate gr, users u
									 where gr.users_pkey=u.users_pkey and gr.group_pkey=$group_pkey and u.email='$user';");
				
				if($count > 0){
				
					$errormessage.=$errormessagedelim."User $user (line $linenum) is already a member of this group. Please try again.";
					$errormessagedelim="<br>";
				
				}
			
			}
			
			
			$linenum++;
		}
	
	}
	
	//if no errors, let's roll over them again to check if they are inviting themselves
	if($errormessage==""){
	
		$linenum=1;

		foreach($users as $user){
			$user=str_replace(" ","",$user);
			$user=str_replace("\n","",$user);
	
			
			//check if user = $username
			
			if($user!=""){
				
				if($username == $user){
				
					$errormessage.=$errormessagedelim."Error (line $linenum) You cannot invite yourself to be in a group. Please try again.";
					$errormessagedelim="<br>";
				
				}
			
			}
			
			
			$linenum++;
		}
	
	}
	
	//if no errors, let's roll over them again to check if any names were actually provided
	if($errormessage==""){
	
		$linenum=1;
		
		$empty="yes";

		foreach($users as $user){
			$user=str_replace(" ","",$user);
			$user=str_replace("\n","",$user);
	
			
			//check if user = $username
			
			if($user!=""){
				
				$empty="no";
			
			}
			
			
			$linenum++;
		}
		
		if($empty=="yes"){
		
			$errormessage.=$errormessagedelim."No users provided. Please try again.";
			$errormessagedelim="<br>";
			$userlist="";
		
		}
	
	}

	//if no errors, let's roll over them and put them in the database
	//also send emails to invite them to the group
	if($errormessage==""){
	

















		require_once "Mail.php";
		
		include_once "includes/config.inc.php";
	
		$from     = "Geochron Portal <geochronportal@gmail.com>";
		$subject="Geochron Group Invitation";
	
		$host     = "ssl://smtp.gmail.com";
		$port     = "465";
	
		$headers = array(
			'From'    => $from,
			'Subject' => $subject,
			'Content-Type' => "text/html; charset=iso-8859-1"
		);

	
		$linenum=1;

		foreach($users as $user){
		
			$thisuserpkey=$db->get_var("select users_pkey from users where email='$user'");
		
			$user=str_replace(" ","",$user);
			$user=str_replace("\n","",$user);
	
			$randstring="";
			for($x=0;$x<55;$x++){
				$randstring.=$chars[rand(0,61)];
			}
			
			//echo "user: $user";exit();
			
			if($user!=""){
			
				$db->query("insert into grouprelate values (
															nextval('grouprelate_seq'),
															$group_pkey,
															$thisuserpkey,
															false,
															'$randstring')
															
							");
				
				//check email
				
				if($user!=""){
	
					//put in user
					$message= "<html><body>
								<h2>Geochron Group Invitation</h2>
								You have been invited to the group '$groupname' by '$username'<br><br>
								Membership in this group will allow you to see the samples it contains.<br><br>
								Please click on the link below to confirm your membership.<br><br>
								
								<a href=\"http://www.geochron.org/validategroup/$randstring\">http://www.geochron.org/validategroup/$randstring</a><br><br>
								
								<br><br>
								Thanks,<br><br>
								The Geochron Team
								<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
								</body></html>";
								
					$smtp = Mail::factory('smtp', array(
						'host'     => $host,
						'port'     => $port,
						'auth'     => true,
						'username' => $emailusername,
						'password' => $emailpassword
					));
				
					$to = $user;
					
					//$headers['To']=$user;
					
					$mail = $smtp->send($to, $headers, $message);
					
					$showusers.=$user."<br>";
				
				}
				
			}
			
			$linenum++;
		}
		
		//OK, users have been put in, so show success Message.
		
		if($p=="md"){
		
			$url="managedata.php";
		
		}elseif($p=="vg"){
		
			$url="viewgroup.php";
		
		}else{
		
			$url="managegroupsamples.php";
		
		}
		
		?>
		
		<h1>Success!</h1><br>
		The following users have been invited to the group <?=$groupname?>:<br><br>
		<?=$showusers?><br>
		
		<INPUT TYPE="button" value="Continue" onClick="parent.location='<?=$url?>?group_pkey=<?=$group_pkey?>'">
		
		<br><br><br><br><br>
		<br><br><br><br><br>
		<br><br><br><br><br>
		<br><br><br><br><br>
		<br><br><br><br><br>
		
		<?
		
		include("includes/geochron-secondary-footer.htm");
		exit();
	
	}


}


if($errormessage!=""){

	$errormessage="<font color=\"red\">$errormessage</font><br><br>";
	
}




//show existing users
$users=$db->get_results("select
						grouprelate_pkey,
						confirmed,
						(select email from users where users_pkey=grouprelate.users_pkey) as email
						from grouprelate where group_pkey=$group_pkey");

if(count($users)>0){

	?>
	
	<h1>Existing Users in Group: <?=$groupname?></h1>

	<table class="aliquot" style="margin-top:15px;border-width:1px 1px 1px 1px;border-style:solid">
		<tr style="vertical-align:middle">
			<th style="vertical-align:middle;border-width:0 0 1px 0;width:25px;">email</th>
			<th style="vertical-align:middle;border-width:0 0 1px 0;width:25px;">confirmed</th>
			<th style="vertical-align:middle;border-width:0 0 1px 0">&nbsp;</th>
		</tr>
	
	<?
	foreach($users as $row){
	?>

		<tr>
			<td><?=$row->email?></td>
			<td><? if($row->confirmed=="t"){echo "YES";}else{echo "NO";}?></td>
			<td><a href="deleteuser.php?pkey=<?=$row->grouprelate_pkey?>&pp=<?=$p?>&p=iu&g=<?=$group_pkey?>" OnClick="return confirm('Are you sure you want to delete <?=$row->email?>?')">DELETE</a></td>
		</tr>

	<?
	}
	?>

	</table><br><br>
	<hr><br>
	<?
	
}




?>

<script Language="JavaScript">
<!-- 
function Blank_TextField_Validator()
{
// Check the value of the element named text_name from the form named text_form
if (text_form.userlist.value == "")
{
  // If null display and alert box
   alert("Please provide a list of users.");
  // Place the cursor on the field for revision
   text_form.userlist.focus();
  // return false to stop further processing
   return (false);
}
// If text_name is not null continue processing
return (true);
}
-->
</script>


<h1>Invite Users to Group: <?=$groupname?></h1><br>

<?=$errormessage?>

<form name="text_form" method="POST" action="inviteusers.php" onsubmit="return Blank_TextField_Validator()">
	User List:<br><textarea name="userlist" rows="15" cols="50"><?=$userlist?></textarea><br><br>
	Please enter the Geopass email addresses of users you would like to invite to<br> this group, one address per line.
	<br><br><br>
	<input type="hidden" name="group_pkey" value="<?=$group_pkey?>">
	<input type="hidden" name="p" value="<?=$p?>">
	<input type="submit" name="submit" value="Submit">
</form>




<br><br><br><br><br>
<br><br><br><br><br>
<br><br><br><br><br>
<br><br><br><br><br>
<br><br><br><br><br>




















<?

include("includes/geochron-secondary-footer.htm");
?>