<?php 
  session_start();
  include('config.php');

  if(isset($_POST['email']))
  {
      $_SESSION['email']=$_POST['email'];
      $_SESSION['password'] = $_POST['password'];

      $email = $_SESSION ['email'];
      $password = $_SESSION ['password'];

      $error = false;

      $sql = "SELECT * FROM users WHERE email='$email' && password='$password'";
      $re = mysqli_query($con,$sql);
      $number = mysqli_num_rows($re);

      $rs = $con->query($sql);
      $user = $rs->fetch_assoc();

      $user_type = $user["type"];

      if($number==1)
      {
          if($user_type == "Admin")
          {
            header('location:Admin.php');
          }
          else if($user_type == "User")
          {
            header('location:Home.php');
          }
      }
      else
      {
          $error = "Email or Password is incorrect.";
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
    <link rel="stylesheet" type="text/css" href="Styles/index.css">

    <title>Data C</title>
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-lg-3"></div>
      <div class="col-lg-6 bg-white mt-5 login-form" style="text-align: center;">
        <h1>Data Collection</h1>
          <h1>Log In</h1>
          <form method="post" id="login">
            <input type="text" name="email" placeholder="Enter Email ..... " required class="form-control form-input form-input1  mb-3 mt-3">

            <input type="password" name="password" placeholder="Enter Password ....." required class="form-control form-input form-input1 mb-3 mt-3">
            <button type="submit" class="btn btn-primary login-btn">LOGIN</button>

          </form>
          <p style="color: red;text-align: center;margin-top: 5px;">
            <?php 
              if(isset($_POST['email'])&&$error)
              {
                echo "$error";
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>