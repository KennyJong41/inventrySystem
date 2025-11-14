<?php
// ✅ Neon PostgreSQL Connection (SSL Enabled)
$host   = "ep-long-surf-a8qwqt6y-pooler.eastus2.azure.neon.tech";
$dbname = "neondb";
$user   = "neondb_owner";
$pass   = "npg_wnPkGaNDKW46";

try {
    // 🔒 强制 SSL + channel binding
    $dsn = "pgsql:host=$host;port=5432;dbname=$dbname;sslmode=require;channel_binding=require";

    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ Connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
