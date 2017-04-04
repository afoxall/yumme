<?php
include_once "common/base.php";
$name = $_SESSION['UNAME'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Yumme!</title>
    <link rel="stylesheet" href="yummeStyle.css" type="text/css" />
</head>

<body>

<div id="facebook-Bar">
    <div id="facebook-Frame">
        <div id="logo"> <img src="logo.png" style="margin:auto; width:140px; height:70px; display:block" ></> </div>
        <div id="header-main-centre">
            <form>
            <a class="smallbutton" href="/newrecipe.php">Add a Recipe!</>
            </form>
            <form>
                <a class="smallbutton" href="/logout.php">Logout</a>
            </form>
        </div>
        <div id="header-main-right">
            <div id="header-main-right-nav">

                <table border="0" style="border:none">
                    <tr>
                        <?php
                            $try = "<a class=\"userLabel\" href=\"/home.php\">".$name."</a>";
                            echo $try;
                        ?>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
</body>