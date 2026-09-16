<?php
session_start();
include 'db.php';
$msg = "";
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $pass = md5($_POST['password']);
    $q = mysqli_query($conn,"SELECT * FROM users WHERE email='$email' AND password='$pass' AND role='cashier'");
    if(mysqli_num_rows($q) == 1){
        $u = mysqli_fetch_assoc($q);
        $_SESSION['id'] = $u['id'];
        $_SESSION['name'] = $u['name'];
        $_SESSION['role'] = $u['role'];
        header("Location: cashier.php");
        exit();
    } else {
        $msg = "Login Failed! Wrong email or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Arial;}
body{
    background: linear-gradient(135deg, #800000 0%, #500000 100%);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}
.login-box{
    background:white;
    padding:40px 30px;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,0.3);
    width:90%;
    max-width:350px;
    text-align:center;
}
.login-box h2{
    color:#800000;
    margin-bottom:20px;
    font-size:24px;
}
.login-box input{
    width:100%;
    padding:12px;
    margin:10px 0;
    border:2px solid #ddd;
    border-radius:8px;
    font-size:16px;
}
.login-box input:focus{
    border-color:#800000;
    outline:none;
}
.login-box button{
    width:100%;
    padding:12px;
    background:#800000;
    color:white;
    border:none;
    border-radius:8px;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
    margin-top:10px;
}
.login-box button:hover{
    background:#500000;
}
.error{color:red;margin-bottom:10px;}
.login-box a{
    color:#800000;
    text-decoration:none;
    font-size:14px;
}
.login-box a:hover{text-decoration:underline;}
</style>
</head>
<body>
<div class="login-box">
    <h2>Admin Login</h2>
    <?php if($msg != "") echo "<p style='color:red;text-align:center;'>$msg</p>"; ?>  
  <p class="error"><?php echo $msg; ?></p>
    <form method="POST">
        <input type="email" name="email" placeholder="admin@test.com" required>
        <input type="password" name="password" placeholder="Password" required>
        <button name="login">LOGIN</button>
    </form>
    <br>
    <a href="index.php">← Back to Student Login</a>
</div>
</body>
</html>
