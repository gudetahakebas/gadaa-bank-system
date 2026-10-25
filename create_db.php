<?php
// Simple script to create the missing database
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'gadaa_voting';

try {
    // Connect to MySQL server without selecting a database
    $pdo = new PDO("mysql:host=$db_host;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create Database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8 COLLATE utf8_general_ci");
    
    echo "<h2 style='color:green'>Success! Database '$db_name' has been created.</h2>";
    echo "<p>Next steps:</p>";
    echo "1. Go to <a href='http://localhost/phpmyadmin/'>phpMyAdmin</a>.<br>";
    echo "2. Click on <b>$db_name</b> in the left sidebar.<br>";
    echo "3. Click the <b>Import</b> tab.<br>";
    echo "4. Choose your <b>setup_db.sql</b> file and click <b>Import</b>.<br><br>";
    echo "<a href='debug_db.php' style='padding:10px; background:#3498db; color:white; text-decoration:none; border-radius:5px;'>Now check Database Tables</a>";

} catch (PDOException $e) {
    echo "<h2 style='color:red'>Error:</h2> " . $e->getMessage();
    echo "<p>Please make sure XAMPP (MySQL) is running and your password is correct.</p>";
}
?>
