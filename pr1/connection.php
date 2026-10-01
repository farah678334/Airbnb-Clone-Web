<?php
define('DBHOST','localhost');
define('DBNAME','apartment_db');
define('DBUSER','root');
define('DBPASS','');
define('DBCONNSTRING',"mysql:host=". DBHOST. ";dbname=". DBNAME);

try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
