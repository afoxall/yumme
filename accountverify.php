<?php
    include_once "common/base.php";
    $pageTitle = "Verify Your Account";
    include_once "common/header.php";



    if(isset($_GET['v']) && isset($_GET['e']))
    {
        include_once "inc/class.users.inc.php";
        $users = new UserManager($db);
        $ret = $users->verifyAccount();
    }
    elseif(isset($_POST['v']))
    {
        include_once "inc/class.users.inc.php";

        $users = new UserManager($db);
        $ret = $users->updateInfo();

    }
    else
    {

        header("Location: /yumme/signup.php");
        exit;
    }
 
    if(isset($ret[0])):

        //echo isset($ret[1]) ? $ret[1]  : NULL;

        if($ret[0]<3):
?>
 
        <h2>Personal Information</h2>
 
        <form method="post" action="accountverify.php">
            <div>
                <label for="fn">First Name:</label>
                <input name="fn" id="fn" /><br />
				<label for="ln">Last Name:</label>
                <input name="ln" id="ln" /><br />
                <label for="p">Choose a Password:</label>
                <input type="password" name="p" id="p" /><br />
                <label for="r">Re-Type Password:</label>
                <input type="password" name="r" id="r" /><br />
                <input name="v" type="hidden" id="v" value="<?php echo $_GET['v'] ?>" />
                <input type="submit" name="verify" id="verify" value="Verify Your Account" />
            </div>
        </form>
 
<?php
        endif;
    else:

        header("Location: /yumme/index.php");

    //echo '<meta http-equiv="refresh" content="0;/">';
    endif;

    include_once 'common/close.php';
?>