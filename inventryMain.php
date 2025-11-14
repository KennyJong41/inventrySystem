<?php
echo "<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
    }
    h2 {
        text-align: center;
        margin-top: 20px;
    
    

</style>";
session_start();
if (!isset($_SESSION['inventry'])) {
    header("Location: inventryLogin.php");
    exit();
}


?>


<h2>Welcome, <?php echo $_SESSION['inventry']; ?>!</h2>
<style>
.menu-bar {
  text-align: center;
  margin: 30px 0;
}
.menu-bar button {
  width: 180px;
  height: 45px;
  font-size: 16px;
  margin: 8px;
  cursor: pointer;
  background-color: #f0f0f0;
  border: 1px solid #ccc;
  border-radius: 5px;
}
.menu-bar button:hover {
  background-color: #e0e0e0;
}
</style>

<div class="menu-bar">
  <a href="inventryform.html"><button type="button">Create New Product</button></a>
  <a href="stockinform.html"><button type="button">Insert Stock In </button></a>
  <a href='salesform.html'><button type="button">Insert New Sale</button></a> |
  <a href="inventryView2.php"><button type="button">Manage Data</button></a>
  <a href="inventryView.php"><button type="button">Inventry Record</button></a>
  <a href="salesView.php"><button type="button">Sales Record</button></a>
  <a href="stockinView.php"><button type="button">Stock In Record</button></a>
  <a href="inventryLogout.php"><button type="button">Logout</button></a>
</div>