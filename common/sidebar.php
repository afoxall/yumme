
<head>

    <link rel="stylesheet" href="yummeStyle.css" type="text/css" />
</head>
    <div id="viewsidebar" style="vertical-align: top">
        <br/>
        <div id="searchRecipe">
            <form action="recipes.php" method="post">
                <input type="text" id="recipe" name="recipe" value="find recipe">
                <input type="submit" value="search">
                <input type="hidden" name="action" id="action" value="searchrecipes">
            </form>
        </div>
        <br/>
        <div id="searchUsers">
            <form action="users.php" method="post">
                <input type="text" id="user" name="user" value="find user">
                <input type="submit" value="search">
                <input type="hidden" name="action" id="action" value="searchusers">
            </form>
        </div>
        <br/>
        <div id="followinfo">
            <div id="following">
                <label style="vertical-align: top; padding-left: 5px">Following:</label><br/>
                <?php
                include_once "inc/class.follows.inc.php";
                try {
                    $folObj = new FollowManager();
                    $usernames = $folObj->getFollows()[0];
                    $uids = $folObj->getFollows()[1];

                    for ($cnt = 0; $cnt < count($usernames); $cnt++) {
                        echo "<div style='padding-left: 10px'><a href='/yumme/userprofile.php?u=$uids[$cnt]&uname=$usernames[$cnt]'>$usernames[$cnt]</a></div>";
                    }

                    unset($value);
                } catch(Exception $e) {
                    echo 'Message: ' .$e->getMessage();
                }
                ?>
                <br/>
            </div>
            <div id="followers">
                <label style="vertical-align: top; padding-left: 5px">Followers:</label><br/>
                <?php
                include_once "inc/class.follows.inc.php";
                try {
                    $folObj = new FollowManager();
                    $usernames = $folObj->getFollowers()[0];
                    $uids = $folObj->getFollowers()[1];

                    for ($cnt = 0; $cnt < count($usernames); $cnt++) {
                        echo "<div style='padding-left: 10px'><a href='/yumme/userprofile.php?u=$uids[$cnt]&uname=$usernames[$cnt]'>$usernames[$cnt]</a></div>";
                    }

                    unset($value);
                } catch(Exception $e) {
                    echo 'Message: ' .$e->getMessage();
                }
                ?>
                <br/>
            </div>
        </div>

    </div>




<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script>

</script>