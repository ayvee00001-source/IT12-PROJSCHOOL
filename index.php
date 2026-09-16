<?php
session_start();
include 'db.php';
$msg = "";

if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $pass = md5($_POST['password']);
    
    $q = mysqli_query($conn,"SELECT * FROM users WHERE email='$email' AND password='$pass' AND role='student'");
    
    if(mysqli_num_rows($q) == 1){
        $u = mysqli_fetch_assoc($q);
        $_SESSION['id'] = $u['id'];
        $_SESSION['name'] = $u['name'];
        $_SESSION['role'] = $u['role'];
        header("Location: student.php");
        exit();
    } else {
        $msg = "Invalid Student Login!";
    }
}

if(isset($_POST['register'])){
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $pass = md5($_POST['password']);
    $role = 'student';

    $check = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($check) > 0){
        $msg = "Email already exists!";
    } else {
        mysqli_query($conn,"INSERT INTO users(name,email,password,role) VALUES('$name','$email','$pass','$role')");
        $msg = "Registered! Login now.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Student Portal</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Arial;}
body{
    background: linear-gradient(135deg, #800000 0%, #500000 100%);
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
}
.portal-box{
    background:white;
    padding:35px 30px;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,0.3);
    width:100%;
    max-width:400px;
}
.portal-box h1{
    color:#800000;
    text-align:center;
    margin-bottom:5px;
    font-size:26px;
}
.portal-box h2{
    color:#800000;
    text-align:center;
    margin:20px 0 15px 0;
    font-size:20px;
}
.portal-box input{
    width:100%;
    padding:12px;
    margin:8px 0;
    border:2px solid #ddd;
    border-radius:8px;
    font-size:15px;
}
.portal-box input:focus{
    border-color:#800000;
    outline:none;
}
.portal-box button{
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
.portal-box button:hover{
    background:#500000;
}
.divider{
    border-top:2px solid #eee;
    margin:25px 0;
}
.msg{
    text-align:center;
    color:#800000;
    margin-bottom:15px;
    font-weight:bold;
}
.portal-box a{
    display:block;
    text-align:center;
    color:#800000;
    text-decoration:none;
    font-weight:bold;
    margin-top:15px;
}
.portal-box a:hover{text-decoration:underline;}
</style>
</head>
<body>
<div class="portal-box">
    <h1>Student Portal</h1>
    <p class="msg"><?php echo $msg; ?></p>
    
    <h2>Login</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button name="login">Login</button>
    </form>
    
    <div class="divider"></div>
    
    <h2>Register Student</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button name="register">Register</button>
    </form>
    
    <a href="admin_login.php">Admin Login Here</a>
</div>
</body>
</html>
