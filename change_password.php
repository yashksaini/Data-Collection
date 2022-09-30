<?php 
session_start();
include('config.php'); 
$email = $_SESSION['email'];
$password = $_SESSION['password'];

$sql = "SELECT * FROM users WHERE email='$email' && password='$password' && type='User'";
$re = mysqli_query($con,$sql);
$number = mysqli_num_rows($re);

if($number!=1)
{
  header('location:index.php');
}
/* User Check Ends */
/* Logout Start */
if(array_key_exists('logout', $_POST)) 
{ 
    session_destroy();
    header('location:index.php');
} 
/* Logout End */
$sql = "SELECT * FROM teachers_detail WHERE email = '$email'";
$res = mysqli_query($con,$sql);
$user = $res->fetch_assoc();

$user_name =  $user["Teacher_Name"];
$teacher_id = $user["Teacher_ID"];

$display_change = "none";
$display_change1 = "block";

$match_err = false;
$match_success = false;
$success_change = false;
$n_c_match_err = false;

if(isset($_POST["c_password"]))
{
  $c_password = $_POST["c_password"];

  $sql1 = "SELECT * FROM users WHERE teacher_id='$teacher_id'";
  $res1 = mysqli_query($con,$sql1);
  $row = $res1->fetch_assoc();

  $password = $row["password"];

  if($password==$c_password)
  {
    $match_success = "Current Password is correct";
    $display_change = "block";
    $display_change1 = "none";
  }
  else
  {
    $match_err = "Please type correct current password.";
  }
}
if(isset($_POST["n_password"]))
{
  $display_change = "block";
  $display_change1 = "none";

  $n_password = $_POST["n_password"];
  $con_password = $_POST["con_password"];

  if($n_password== $con_password)
  {
    $sql2= "UPDATE users SET password = '$n_password' 
    WHERE teacher_id = $teacher_id";
    $res2 = mysqli_query($con,$sql2);

    $success_change = "Password Changed Successfully";
    $_SESSION['password'] = $n_password;
  }
  else
  {
    $n_c_match_err = "Password's do not match";
  }
}
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


    <!-- Font Awesome -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
          
    <script src="jquery.tabledit.min.js"></script>
    <link rel="stylesheet" type="text/css" href="Styles/index.css">
    <link rel="stylesheet" type="text/css" href="Styles/Home.css">

    <title>Change Password</title>
</head>

<body>
<header>
  <nav class="navbar fixed-top navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><span style="color: green;"><?php echo "$user_name" ?></span></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link " aria-current="page" href="Home.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="update_marks.php">View/Updated Marks</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="change_password.php">Change Password</a>
        </li>
        <li class="nav-item">
          <form method="post" style="padding-left: 10px;"> 
              <input type="submit" name="logout"
                    class="btn btn-secondary rounded-0" value="Log Out" /> 
            </form>
        </li>
      </ul>
    </div>
  </div>
</nav>
</header>
<div class="container" style="margin-top: 100px;">
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6" style="padding: 20px;display: <?php echo "$display_change1";?>">
      <h1 style="text-align: center;padding-bottom: 25px;">Change Password</h1>
        <form method="post">
                <input type="password" name="c_password" placeholder="Current Password.." required class="form-control form-input form-input1 mb-3 mt-3">

                <button type="submit" class="btn btn-success login-btn">Next &#8250;&#8250;</button>

        </form>
        <p style="text-align: center;margin-top: 5px;">
                <?php 
                  if(isset($_POST['c_password'])&&$match_err)
                  {
                    echo "<span style='color: red;'>".$match_err."</span>";
                    $match_err=false;

                  }
                  else if(isset($_POST['c_password'])&&$match_success)
                  {
                    echo "<span style='color: green;'>".$match_success."</span>";
                    $match_success = false;
                  }
                ?>
              </p>
    </div>
    <div class="col-md-6" style="display: <?php echo "$display_change";?>">
      <h1 style="text-align: center;padding-bottom: 25px;">New Password</h1>
        <form method="post">
                <input type="password" name="n_password" placeholder="New Password.." required class="form-control form-input form-input1 mb-3 mt-3">

                <input type="password" name="con_password" placeholder="Confirm Password.." required class="form-control form-input form-input1 mb-3 mt-3">

                <button type="submit" class="btn btn-primary login-btn">Change Password &#8250;&#8250;</button>

        </form>
        <p style="text-align: center;margin-top: 5px;">
                <?php 
                  if(isset($_POST['n_password'])&&$n_c_match_err)
                  {
                    echo "<span style='color: red;'>".$n_c_match_err."</span>";
                    $n_c_match_err=false;

                  }
                  else if(isset($_POST['n_password'])&&$success_change)
                  {
                    echo "<span style='color: green;'>".$success_change."</span>";
                    $success_change = false;
                  }
                ?>
              </p>
    </div>
  </div>
</div>



<script type='text/javascript'>
	if ( window.history.replaceState ) 
		{
  			window.history.replaceState( null, null, window.location.href );
		}
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
  
</body>
</html>