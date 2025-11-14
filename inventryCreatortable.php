<?php


$host   = "ep-long-surf-a8qwqt6y-pooler.eastus2.azure.neon.tech";
$dbname = "neondb";
$user   = "neondb_owner";
$pass   = "npg_wnPkGaNDKW46";

try {
    // 连接 Neon 数据库（必须启用 SSL）
    $pdo = new PDO("pgsql:host=$host;port=5432;dbname=$dbname;sslmode=require", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h3>✅ Connected to PostgreSQL (Neon) successfully.</h3>";

    // 建表 SQL （已整合）
    $sql = <<<SQL
    CREATE TABLE IF NOT EXISTS inventry (
        no SERIAL PRIMARY KEY,
        productid VARCHAR(50) UNIQUE NOT NULL,
        name VARCHAR(255) NOT NULL,
        image VARCHAR(255),
        cost NUMERIC(10,2),
        price NUMERIC(10,2),
        quantity BIGINT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS sales (
        saleid SERIAL PRIMARY KEY,
        productid VARCHAR(50) REFERENCES inventry(productid) ON DELETE CASCADE,
        quantity INT NOT NULL,
        total NUMERIC(10,2) NOT NULL,
        saledate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS stockin (
        stockinid SERIAL PRIMARY KEY,
        productid VARCHAR(50) REFERENCES inventry(productid) ON DELETE CASCADE,
        quantity INT NOT NULL,
        stockindate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS users (
        userid SERIAL PRIMARY KEY,
        username VARCHAR(255) UNIQUE NOT NULL,
        password TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
SQL;

    // 执行创建
    $pdo->exec($sql);
    echo "<p>✅ All tables created or already exist.</p>";

    // 插入管理员账户（如不存在）
    $check = $pdo->prepare("SELECT 1 FROM users WHERE username = 'admin'");
    $check->execute();

    if ($check->rowCount() === 0) {
        $password = password_hash("admin123", PASSWORD_DEFAULT);
        $insert = $pdo->prepare("INSERT INTO users (username, password) VALUES (:u, :p)");
        $insert->execute([':u' => 'admin', ':p' => $password]);
        echo "<p>✅ Default admin account created. (username: admin / password: admin123)</p>";
    } else {
        echo "<p>ℹ️ Admin account already exists, skipped insertion.</p>";
    }

    echo "<h3>🎉 Database setup completed successfully!</h3>";

} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
