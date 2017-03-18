<?php
 
session_start();
 
include_once "../inc/constants.inc.php";
include_once "../inc/class.users.inc.php";

$userObj = new UserManager();
 
if(!empty($_POST['action'])
&& isset($_SESSION['LoggedIn'])
&& $_SESSION['LoggedIn']==1)
{
    switch($_POST['action'])
    {
        case 'changeemail':
            $status = $userObj->updateEmail() ? "changed" : "failed";
            header("Location: /yumme/account.php?email=$status");
            break;
        case 'changepassword':
            $status = $userObj->updateInfo() ? "changed" : "nomatch";
            header("Location: /yumme/account.php?password=$status");
            break;
        case 'deleteaccount':
            $userObj->deleteAccount();
            break;
			
		case 'deleteuser':
			$userObj->deleteUser();
			break;
        default:
            header("Location: /yumme/index.php");
            break;
    }
}
elseif($_POST['action']=="resetpassword")
{
    if($resp=$userObj->resetPassword()===TRUE)
    {
        header("Location: /yumme/resetpending.php");
    }
    else
    {
        echo $resp;
    }
    exit;
}
else
{
    header("Location: /");
    exit;
}
 
?>