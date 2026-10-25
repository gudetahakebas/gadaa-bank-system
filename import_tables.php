<?php
require_once 'db_config.php';

try {
    // Read the SQL file
    $sql = file_get_contents('setup_db.sql');
    
    // Execute the SQL queries
    $pdo->exec($sql);
    
    echo "<h2 style='color:green'>Success! All database tables have been created.</h2>";
    echo "<p>The 'users', 'candidates', 'voters', 'officers', 'votes', and 'system_settings' tables are now ready.</p>";
    echo "<p>Default Admin Account Created:</p>";
    echo "<ul><li><b>Username:</b> admin</li><li><b>Password:</b> admin123</li></ul>";
    echo "<br><a href='admin-login.html' style='padding:10px; background:#2ecc71; color:white; text-decoration:none; border-radius:5px;'>Go to Admin Login</a>";

} catch (PDOException $e) {
    echo "<h2 style='color:red'>Error creating tables:</h2> " . $e->getMessage();
} catch (Exception $e) {
    echo "<h2 style='color:red'>General Error:</h2> " . $e->getMessage();
}
?>
