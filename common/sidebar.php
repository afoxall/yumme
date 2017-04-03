
<head>

    <link rel="stylesheet" href="yummeStyle.css" type="text/css" />
</head>
    <div id="viewsidebar" style="vertical-align: top">
        <div id="following">
            <label style="vertical-align: top">People I'm Following:</label><br/>
            <?php
            include_once "inc/class.follows.inc.php";
            try {
                $folObj = new FollowManager();
                $res = $folObj->getFollowers()[0];

                foreach ($res as &$value) {
                    echo "<div><label>$value</label></div>";
                }

                unset($value);
            } catch(Exception $e) {
                echo 'Message: ' .$e->getMessage();
            }
            ?>
            <br/>
        </div>
        <div id="followers">
            <label style="vertical-align: top">People Following Me:</label><br/>
            <?php
            include_once "inc/class.follows.inc.php";
            try {
                $folObj = new FollowManager();
                $res = $folObj->getFollows()[0];

                foreach ($res as &$value) {
                    echo "<div><label>$value</label></div>";
                }

                unset($value);
            } catch(Exception $e) {
                echo 'Message: ' .$e->getMessage();
            }
            ?>
            <br/>
        </div>
    </div>




<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script>

</script>