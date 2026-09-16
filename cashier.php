<?php
session_start();
include 'db.php';
if(!isset($_SESSION['id']) || $_SESSION['role'] != 'cashier'){
    header("Location: admin_login.php");
    exit();
}


if(isset($_GET['call'])){
    $next = mysqli_fetch_assoc(mysqli_query($conn,"SELECT id FROM queue WHERE status='waiting' ORDER BY queue_number ASC LIMIT 1"));
    if($next){
        mysqli_query($conn,"UPDATE queue SET status='in_progress' WHERE id=".$next['id']);
    }
    header("Location: cashier.php");
    exit();
}


if(isset($_GET['serve'])){
    mysqli_query($conn,"UPDATE queue SET status='served' WHERE id=".$_GET['serve']);
    header("Location: cashier.php");
    exit();
}


$waiting = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM queue WHERE status='waiting'"));
$progress = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM queue WHERE status='in_progress'"));
$completed = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM queue WHERE status='served' AND DATE(created_at)=CURDATE()"));


$current = mysqli_fetch_assoc(mysqli_query($conn,"SELECT q.id, q.queue_number, u.name FROM queue q JOIN users u ON q.user_id=u.id WHERE q.status='in_progress' LIMIT 1"));


$next_queue = mysqli_fetch_assoc(mysqli_query($conn,"SELECT q.id, q.queue_number, u.name FROM queue q JOIN users u ON q.user_id=u.id WHERE q.status='waiting' ORDER BY q.queue_number ASC LIMIT 1"));
?>
<!DOCTYPE html>
<html>
<head>
<title>Cashier Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body{font-family:'Segoe UI',Arial;background:#FFF8F0;margin:0;padding:15px;color:#3E2723;}
.header{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:20px;padding-bottom:10px;border-bottom:2px solid #800000;}
.header h1{font-size:26px;color:#800000;font-weight:bold;margin:0;}
.header .user{font-size:14px;color:#800000;background:#FFF0E6;padding:8px 12px;border-radius:20px;}
.card{background:#FFFFFF;padding:20px;border-radius:15px;margin-bottom:15px;box-shadow:0 3px 8px rgba(128,0,0,0.1);border-left:4px solid #800000;}
.card h3{margin:0;font-size:12px;color:#A0522D;text-transform:uppercase;letter-spacing:1px;}
.card h2{font-size:48px;margin:10px 0;color:#800000;font-weight:bold;}
.label{color:#6D4C41;font-size:14px;}
.btn-call{background:linear-gradient(135deg, #800000, #A52A2A);color:white;padding:12px 25px;border:none;border-radius:10px;font-size:16px;cursor:pointer;float:right;font-weight:bold;box-shadow:0 3px 6px rgba(128,0,0,0.3);text-decoration:none;display:inline-block;}
.btn-call:hover{background:linear-gradient(135deg, #A52A2A, #800000);}
.btn-done{background:#2E7D32;color:white;padding:10px 20px;border:none;border-radius:10px;font-weight:bold;cursor:pointer;text-decoration:none;display:inline-block;}
.btn-done:hover{background:#1B5E20;}
.queue-box{text-align:center;padding:30px;background:#FFF0E6;border-radius:10px;margin-top:15px;}
.queue-box h3{color:#800000;font-size:22px;margin:10px 0;}
hr{border:none;border-top:1px solid #D7CCC8;margin:15px 0;}
.logout{color:#800000;text-decoration:none;font-size:14px;}
</style>
</head>
<body>
<div class="header">
<h1>Cashier Dashboard</h1>
<div>
<span class="user"><?php echo $_SESSION['name']; ?></span>
<a href="logout.php" class="logout">Logout</a>
</div>
</div>

<div class="card">
<h3>WAITING</h3>
<h2><?php echo $waiting; ?></h2>
<div class="label">students in line</div>
</div>

<div class="card">
<h3 style="color:#A52A2A;">IN PROGRESS</h3>
<h2><?php echo $progress; ?></h2>
<div class="label">being helped now</div>
</div>

<div class="card">
<h3 style="color:#2E7D32;">COMPLETED</h3>
<h2><?php echo $completed; ?></h2>
<div class="label">closed today</div>
</div>

<div class="card">
<div style="display:flex;justify-content:space-between;align-items:center;">
<div>
<h2 style="font-size:20px;margin:0;">Cashier queue</h2>
<div class="label">Call the next student in order.</div>
</div>
<a href="cashier.php?call=1" class="btn-call">Call next</a>
</div>
<hr>
<?php if($current){ ?>
<div class="queue-box">
<h3>Now Serving: <?php echo $current['queue_number']; ?></h3>
<p><?php echo $current['name']; ?></p>
<a href="cashier.php?serve=<?php echo $current['id']; ?>" class="btn-done">DONE</a>
</div>
<?php } else if($next_queue){ ?>
<div class="queue-box">
<h3>Next: <?php echo $next_queue['queue_number']; ?></h3>
<p><?php echo $next_queue['name']; ?></p>
<p class="label">Click "Call next" to serve</p>
</div>
<?php } else { ?>
<div class="queue-box">
<h3>The cashier queue is clear</h3>
<p class="label">New student tickets will appear here.</p>
</div>
<?php } ?>
</div>

<div class="card">
<h3 style="color:#A52A2A;">IN PROGRESS</h3>
<?php if($current){ echo "<p><b>".$current['queue_number']."</b> - ".$current['name']."</p>"; } else { echo "<p class='label'>No active cashier calls right now.</p>"; } ?>
</div>

</body>
</html>
