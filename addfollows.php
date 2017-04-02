<?php
/**
 * Created by PhpStorm.
 * User: foxal
 * Date: 2017-04-02
 * Time: 3:20 PM
 */

include_once "common/base.php";

include_once "inc/constants.inc.php";
include_once "inc/class.recipes.inc.php";
include_once "inc/class.follows.inc.php";

$f = new followManager();
$f->addFollow();
header("Location: /yumme/index.php");

?>