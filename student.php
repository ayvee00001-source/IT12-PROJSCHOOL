<?php 
session_start(); 
include 'db.php'; 
if(!isset($_SESSION['id']) || $_SESSION['role'] != 'student'){ 
header("Location: index.php"); 
exit(); 
} 
$msg = ""; 
$user_id = $_SESSION['id'];

if(isset($_POST['getqueue'])){ 
$service_name = mysqli_real_escape_string($conn,$_POST['service']); 
$s = mysqli_query($conn,"SELECT id FROM services WHERE name='$service_name'"); 
$s_row = mysqli_fetch_assoc($s); 
$service_id = $s_row['id']; 

$qnum = mysqli_query($conn,"SELECT MAX(queue_number) as max FROM queue WHERE DATE(created_at)=CURDATE()"); 
$q_row = mysqli_fetch_assoc($qnum); 
$queue_number = $q_row['max'] + 1; 
if(empty($q_row['max'])) $queue_number = 1; 

$check = mysqli_query($conn,"SELECT * FROM queue WHERE user_id='$user_id' AND status IN ('waiting','in_progress')"); 
if(mysqli_num_rows($check) > 0){ 
$msg = "You already have a pending queue!"; 
} else { 
mysqli_query($conn,"INSERT INTO queue(user_id,student_id,service_id,queue_number,status) 
VALUES('$user_id','$user_id','$service_id','$queue_number','waiting')"); 
$msg = "Queue #$queue_number Added! Wait for your number."; 
} 
} 

$my_queue = mysqli_fetch_assoc(mysqli_query($conn,"SELECT q.*, s.name as service_name FROM queue q JOIN services s ON q.service_id=s.id WHERE q.user_id='$user_id' AND q.status IN ('waiting','in_progress') ORDER BY q.id DESC LIMIT 1"));

$now_serving = mysqli_fetch_assoc(mysqli_query($conn,"SELECT q.*, s.name as service_name FROM queue q JOIN services s ON q.service_id=s.id WHERE q.status='in_progress' ORDER BY q.id ASC LIMIT 1"));
?> 
<!DOCTYPE html> 
<html> 
<head> 
<title>Student Dashboard</title> 
<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
<style> 
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',Arial;} 
body{background: linear-gradient(135deg, #800000 0%, #500000 100%); min-height:100vh; padding:15px;}
.container{max-width:450px;margin:0 auto;} 
.header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;color:white;} 
.header h2{font-size:20px;} 
.header a{background:white;color:#800000;padding:8px 15px;border-radius:8px;text-decoration:none;font-weight:bold;} 
.card{background:white;color:black;padding:25px;border-radius:15px;margin-bottom:15px;box-shadow:0 4px 10px rgba(0,0,0,0.3);} 
.card h3{color:#800000;text-align:center;margin-bottom:15px;font-size:18px;} 
.card select, .card button{width:100%;padding:12px;margin:10px 0;border-radius:8px;border:1px solid #ccc;font-size:16px;} 
.card select:focus{border-color:#800000;outline:none;} 
.card button{background:linear-gradient(135deg, #800000, #A52A2A);color:white;border:none;font-weight:bold;cursor:pointer;} 
.card button:hover{background:#500000;} 
.msg{text-align:center;color:#800000;font-weight:bold;margin-bottom:10px;}
.now-serving{background:#FFF0E6;border:3px solid #800000;text-align:center;} 
.now-serving .label{color:#A0522D;font-size:12px;letter-spacing:1px;font-weight:bold;}
.now-serving h1{font-size:70px;color:#800000;margin:5px 0;font-weight:bold;}
.now-serving p{color:#3E2723;margin:5px 0;}
.my-queue{text-align:center;color:#2E7D32;font-weight:bold;font-size:16px;}
</style> 
</head> 
<body> 
<div class="container"> 
<div class="header"> 
<h2>Hi, <?php echo $_SESSION['name']; ?></h2> 
<a href="logout.php">Logout</a> 
</div> 

<?php if($now_serving){ ?>
<div class="card now-serving">
<div class="label">NOW SERVING</div>
<h1><?php echo $now_serving['queue_number']; ?></h1>
<p><?php echo $now_serving['service_name']; ?></p>
</div>
<?php } ?>

<div class="card"> 
<h3>Get Your Queue Number</h3> 
<p class="msg"><?php echo $msg; ?></p> 

<?php if($my_queue){ ?>
<p class="my-queue">Your Queue: #<?php echo $my_queue['queue_number']; ?></p>
<p style="text-align:center;color:#A0522D;">Service: <?php echo $my_queue['service_name']; ?></p>
<p style="text-align:center;color:#800000;">Status: <b><?php echo strtoupper($my_queue['status']); ?></b></p>
<?php if($now_serving && $my_queue['queue_number'] == $now_serving['queue_number']){ ?>
<p style="text-align:center;color:green;font-weight:bold;">IT'S YOUR TURN! PUMUNTA KA NA SA CASHIER</p>
<?php } ?>
<?php } else { ?>
<form method="POST"> 
<label><b>Select Service:</b></label> 
<select name="service" required> 
<option value="">-- Choose Service --</option> 
<option value="Enrollment">Enrollment</option> 
<option value="Downpayment">Downpayment</option> 
<option value="Tuition Payment">Tuition Payment</option>
<option value="Library Fee">Library Fee</option> 
<option value="Laboratory Fee">Laboratory Fee</option>
<option value="ID Release">ID Release</option> 
<option value="Miscellaneous">Miscellaneous</option> 
</select> 
<button name="getqueue">Get Queue</button> 
</form> 
<?php } ?>

</div> 
</div> 
</body> 
</html>
