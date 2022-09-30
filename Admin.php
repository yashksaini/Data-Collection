<?php 
session_start();
include('config.php'); 
$email = $_SESSION['email'];
$password = $_SESSION['password'];

/* Verfying the user */

$sql = "SELECT * FROM users WHERE email='$email' && password='$password' && type='Admin'";
$re = mysqli_query($con,$sql);
$number = mysqli_num_rows($re);

if($number!=1)
{
  header('location:index.php');
}
/* Veryfying Ends */
/* Logout Start */
if(array_key_exists('logout', $_POST)) 
{ 
    session_destroy();
    header('location:index.php');
} 
/* Logout End */

$alreay_err = false;
$create_success = false;


/* For Creating New User By Admin Starts */

if(isset($_POST['c_id']))
{
	$c_id = $_POST['c_id'];
	$c_password = $_POST['c_password'];


	$sql3 = "SELECT * FROM teachers_detail WHERE Teacher_ID = '$c_id'";
	$res3 = mysqli_query($con,$sql3);
	$number3 = mysqli_num_rows($res3);
	if($number3==1)
	{
		$row3 =  $res3->fetch_assoc();
		$c_email = $row3["email"];

		$sql1 = "SELECT * FROM users WHERE teacher_id='$c_id'";
		$re1 = mysqli_query($con,$sql1);
		$number1 = mysqli_num_rows($re1);
		if($number1==1)
		{
			$alreay_err = "This user already created.";
		}
		else
		{
			$reg = "INSERT INTO users(email,password,teacher_id,type)
			VALUES ('$c_email','$c_password','$c_id','User')";
			mysqli_query($con,$reg);

			$create_success = "User Created Successfully";
		}
	}
	else
	{
		$alreay_err = "User ID invalid!";
	}
	


	
}

/* Creating New User Ends */

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
          
    <script src="jquery.tabledit.min.js"></script>
    <link rel="stylesheet" type="text/css" href="Styles/index.css">
    <link rel="stylesheet" type="text/css" href="Styles/Admin.css">

    <title>Data C</title>
</head>

<body>

<header>
  <nav class="navbar fixed-top navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><span style="color: green;">Admin Control</span></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="Admin.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Update_status.php">Update</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="change_password_admin.php">Change Password</a>
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

<div class="container" style="margin-top: 70px;">
	<div class="row">
		<!-- Create User Starts-->

		<div class="col-md-6 col-admin-home">
			<div class="add-user-div">
				<h1 style="text-align: center;">Create User</h1>
				<form method="post">

		            <input type="number" name="c_id" placeholder="Enter User ID.." required class="form-control form-input form-input1 mb-3 mt-3">

		            <input type="text" name="c_password" placeholder="Create Password ....." required class="form-control form-input form-input1 mb-3 mt-3">

		            <button type="submit" class="btn btn-success login-btn">Create</button>

				</form>
				<p style="text-align: center;margin-top: 5px;">
		            <?php 
		              if(isset($_POST['c_id'])&&$alreay_err)
		              {
		                echo "<span style='color: red;'>".$alreay_err."</span>";
		                $already_err=false;

		              }
		              else if(isset($_POST['c_id'])&&$create_success)
		              {
		              	echo "<span style='color: green;'>".$create_success."</span>";
		              	$create_success = false;
		              }
		            ?>
          		</p>
			</div><hr>
		</div>

		<!-- Create User Ends -->

		<!-- Update User Starts-->

		<div class="col-md-6 col-admin-home mt-3" style="text-align: center;">
			<table class="table-responsive table table-bordered">
				<tr>
					<th>Table Name</th>
					<th>Download</th>
				</tr>
				<tr>
					<td><p style="text-align: center;">Exam Marks</p></td>
					<td><form method="post" action="download.php">
						<input type="text" name="table_name" value="exam_marks" style="display: none">
						<button class="btn btn-primary mb-2 ">Download Table</button>
						</form>
					</td>
				</tr>
				<tr>
					<td><p style="text-align: center;">Student Details</p></td>
					<td><form method="post" action="download.php">
						<input type="text" name="table_name" value="student_details" style="display: none">
						<button class="btn btn-primary mb-2 ">Download Table</button>
						</form>
					</td>
				</tr>
				<tr>
					<td><p style="text-align: center;">Teacher Details</p></td>
					<td><form method="post" action="download.php">
						<input type="text" name="table_name" value="teachers_detail" style="display: none">
						<button class="btn btn-primary mb-2 ">Download Table</button>
						</form>
					</td>
				</tr>
				<tr>
					<td><p style="text-align: center;">Exam Details</p></td>
					<td><form method="post" action="download.php">
						<input type="text" name="table_name" value="exam_details" style="display: none">
						<button class="btn btn-primary mb-2 ">Download Table</button>
						</form>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>

<div class="container-fluid mt-5" style="margin-bottom: 400px;text-align: center;">
	<h1 class="mt-2 mb-3">All users list</h1>
	<div class="row">
		<div class="col-md-1"></div>
		<div class="col-md-10">

      <div class="d-flex justify-content-center align-items-center mt-3 mb-5">

          <input type="text" id="myInput1" onkeyup="myFunction()" placeholder="Search for User..." title="Type in Here..">
          <button class="btn-primary btn s_btn1"><i class="fas fa-search"></i></button>
      </div>
			<div class="table-responsive" id="myUL">
          <table id="editable_table" class="table-bordered table table-striped" style="text-align: center;">
            <thead style="background-color: #717171;color: white;">
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Email</th>
              <th scope="col">Name</th>
              <th scope="col">Password</th>
              <th scope="col">Teacher ID</th>
            </tr>
          </thead>
          <?php
                $sql2 = "SELECT * FROM users WHERE type='User'";
                $result2 = $con->query($sql2);

                if ($result2->num_rows > 0) {

                    // output data of each row

                    while($row = $result2->fetch_assoc()) {

                    	$t_id = $row["teacher_id"];
                    	$sql4 = "SELECT * FROM teachers_detail WHERE Teacher_ID='$t_id'";
                    	$res4 = mysqli_query($con,$sql4);
                    	$show = $res4->fetch_assoc();

                       echo"<tr><td>".$row["ID"]."</td>
        						<td>".$show["email"]."</td>
                       			<td>".$show["Teacher_Name"]."</td>
        						<td>".$row["password"]."</td>
        						<td>".$show["Teacher_ID"]."</td>
        					</tr>";
                    }
                  }
                  else 
                  {
                  	echo "<tr><td>Empty Table</td></tr>";
                  }
              ?>
              
          </table>
        </div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#editable_table').Tabledit({
			url:'update_users.php',
			buttons: {
			    edit: {
			        class: 'btn btn-sm btn-primary rounded-0',
			        html: '<i class="fas fa-edit"></i>',
			        action: 'edit'
			    },
			    delete: {
			        class: 'btn btn-sm btn-danger  ms-4 rounded-0',
			        html: '<i class="far fa-trash-alt"></i>',
			        action: 'delete'
			    },
			    save: {
			        class: 'btn btn-sm btn-success m-auto mt-2 w-100 rounded-0',
			        html: 'Save'
			    },
			    restore: {
			        class: 'btn btn-sm btn-warning m-auto mt-2  w-100 rounded-0',
			        html: 'Restore',
			        action: 'restore'
			    },
			    confirm: {
			        class: 'btn btn-sm btn-danger  m-auto mt-2  w-100 rounded-0',
			        html: 'Confirm'
			    }
			},
			columns:{
				identifier:[0,"ID"],
				editable:[[3,'password'],[1,'email'],[2,'Teacher_Name']]
			},
			restoreButton:false,
			onSuccess:function(data,textStatus,jqXHR)
			{
				if(data.action=='delete')
				{
					$('#'+data.ID).remove();
				}
			}
		});
	});
</script>

<script type='text/javascript'>
	if ( window.history.replaceState ) 
		{
  			window.history.replaceState( null, null, window.location.href );
		}
</script>
<script>
function myFunction() {
    var input, filter, ul, li, a, i, txtValue;
    input = document.getElementById("myInput1");
    filter = input.value.toUpperCase();
    x = document.getElementById("myUL");
    tr = x.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        a = tr[i];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
            }
             else {
            tr[i].style.display = "none";
        }
    }
  }
</script>

</body>
</html>