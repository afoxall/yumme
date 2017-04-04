<?php
if(isset($_SERVER['RDS_DB_NAME'])) {
    define('DB_NAME', $_SERVER['RDS_DB_NAME']);
    define('DB_USER', $_SERVER['RDS_USERNAME']);
    define('DB_PASSWORD', $_SERVER['RDS_PASSWORD']);
    define('DB_HOST', $_SERVER['RDS_HOSTNAME']);
}
else{
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'yumme_db');
}
define('DB_PORT', '3306');

?>