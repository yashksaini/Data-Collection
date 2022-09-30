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

$display_table = "none";
$display_type ="none";

/* Marks Filling form */

if (isset($_POST["exam_type"])) 
   	{
        $display_table = "block";
        $display_type ="none";
    }
if(isset($_POST["class_subject"]))
{
	 $display_type ="block";
}
                  		

/* Marks Filling form End */
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

    <title>Home</title>
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
          <a class="nav-link active" aria-current="page" href="Home.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="update_marks.php">View/Updated Marks</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="change_password.php">Change Password</a>
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
		<div class="col-md-4 mt-5">
			<div class="table-responsive">
          <table  class="table-bordered table table-striped" style="text-align: center;">
            <thead style="background-color: #717171;color: white;">
            <tr>
              <th scope="col">Class</th>
              <th scope="col">Subject</th>
            </tr>
          </thead>
          <?php
                $sql2 = "SELECT  DISTINCT  Class_Section,Sub_Name FROM exam_details WHERE Teacher_ID='$teacher_id'";
                $result2 = $con->query($sql2);

                if ($result2->num_rows > 0) {

                    // output data of each row

                    while($row = $result2->fetch_assoc()) {

                       echo"<tr><td>".$row["Class_Section"]."</td>
        						<td>".$row["Sub_Name"]."</td>
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
	<div class="col-md-4" style="padding: 20px;">
		<h4 style="text-align: center;margin-bottom: 5px;">Enter Exam Marks</h4>
		<form method="post">
			<div class="form-group">
                <label for="class_subject" class="mt-4 ">Select Class </label>
                  <select name="class_subject" id="user" class="form-control form-input" name="type">
                  	<?php 


                  	$sql2 = "SELECT  DISTINCT  Class_Section,Sub_Name FROM exam_details WHERE Teacher_ID='$teacher_id'";
                	$result2 = $con->query($sql2);

                	if ($result2->num_rows > 0) {

                    // output data of each row

                    while($row = $result2->fetch_assoc()) {

                       echo"<option value='".$row["Class_Section"].",".$row["Sub_Name"]."' >".$row["Class_Section"]." (".$row["Sub_Name"].")</option>";
                    	}
                  	}
                  	else 
	                  {
	                  	echo "<tr><td>No Classes</td></tr>";
	                  }
	                 ?>

                  </select>
            </div>

            <button type="submit" class="btn btn-success login-btn">Next</button>
        </form>
	</div>

		<div class="col-md-4 mb-5" style="display:<?php echo "$display_type";?>;padding: 20px;">

	<form method="post">
		<?php 
		if (isset($_POST["class_subject"])) 
                  	{
                  		$class_subject = $_POST["class_subject"];

					list($class_section, $subject) = explode(',', $class_subject);
					echo"<h4 style='text-align: center;margin-bottom: 5px;'>$class_section ($subject)</h4>";

				echo "<input type='text' name='class' value='$class_section' style='display:none;'>
				<input type='text' name='subject' value='$subject' style='display:none;'>";
			}
		?>
	            <div class="form-group ">
                <label for="class_subject" class="mt-4">Select Exam Type </label>
                  <select name="exam_type" id="user" class="form-control form-input" name="type">
                  	<?php 
                  	if (isset($_POST["class_subject"])) 
                  	{
                  		$class_subject = $_POST["class_subject"];

					list($class_section, $subject) = explode(',', $class_subject);

                  		$sql2 = "SELECT  DISTINCT Test_Name FROM exam_details 
                  				WHERE Class_Section = '$class_section' && Sub_Name='$subject' && Teacher_ID='$teacher_id'";
                	$result2 = $con->query($sql2);
                	

                	if ($result2->num_rows > 0) {

                    // output data of each row

                    while($row = $result2->fetch_assoc()) {

                       echo"<option value='".$row["Test_Name"]."'>".$row["Test_Name"]."</option>";
                    	}
                  	}
                  	else 
	                  {
	                  	echo "<tr><td>No Exams</td></tr>";
	                  }
                  	}

                  	
	                 ?>

                  </select>
            </div>
            <button class="btn btn-warning login-btn">Fill Marks</button>
        </form>
    </div>
    <div class="col-md-4 mt-4" style="text-align:left;padding: 20px;">
    	<?php
    	if (isset($_POST["exam_type"])) 
                  	{
                  		$exam_type = $_POST["exam_type"];
					$subject = $_POST["subject"];
					$class= $_POST["class"];

					$sql = "SELECT  Max_Marks FROM exam_details WHERE Test_Name ='$exam_type' && Class_Section = '$class' && Teacher_ID='$teacher_id' && Sub_Name='$subject'";
					$result = mysqli_query($con,$sql);

					$show= $result->fetch_assoc();

					$max_mark = $show["Max_Marks"];

					if($max_mark=="")
					{
						$max_mark = "Grades";
					}

                  		
					echo "<h4>Class: $class</h4>
    						<h4>Subject: $subject</h4>
    						<h4>Maximum Marks: $max_mark</h4>
    						<h4>Exam Type: $exam_type</h4>";
    				}
		?>

    	
    </div>
</div>
	<div class="table-responsive mt-5 mb-5" style="display: <?php echo "$display_table";?>">
          <table  class="table-bordered table" style="text-align: center;">
            
<?php
if (isset($_POST["exam_type"])) 
{
  $exam_type = $_POST["exam_type"];
	$subject = $_POST["subject"];
	$class= $_POST["class"];

	$sql = "SELECT  Max_Marks FROM exam_details WHERE Test_Name ='$exam_type' && Class_Section = '$class' && Teacher_ID='$teacher_id' && Sub_Name='$subject'";

	$result = mysqli_query($con,$sql);
	$show= $result->fetch_assoc();
  $max_mark = $show["Max_Marks"];
                
  $sql4 = "SELECT * FROM update_status WHERE Test_Name ='$exam_type'&& class_section='$class'";
  $result4 = mysqli_query($con,$sql4);
  $up = $result4->fetch_assoc();

  $test_status = $up["Status"];

  if($test_status=='Restrict')
  {
    echo "<tr><td>Persmission not given by admin to add marks for this test. Ask 'Admin' to allow to add marks for this test.</td></tr></table>";
  }
else
{
  if($max_mark=="")
  {
    echo "
      <form method='post' action='grades_add.php'>
      <thead style='background-color: #717171;color: white;'>
        <tr>
          <th scope='col'>S.No</th>
          <th scope='col'>Student Name</th>
          <th scope='col'>Roll Number</th>
          <th scope='col'>Grade</th>
        </tr>
     </thead>";
    $sql2 = "SELECT Student_Name,Roll_No FROM student_details
    WHERE Class_Section='$class' && Sub_Name ='$subject'";
    $result2 = $con->query($sql2);

    $sql3 = "SELECT DISTINCT Test_Name,Sub_Name,Class_Section
    FROM exam_marks WHERE Test_Name='$exam_type' && Sub_Name='$subject' && Class_Section='$class'";

    $result3 = mysqli_query($con,$sql3);
    $num3 = mysqli_num_rows($result3);
      if($num3)
      {
        echo "<tr><td>Data Already Filled</td></tr></table>";
      }
      else
      {
        if ($result2->num_rows > 0) 
        {
          // output data of each row
          $i =0;
          while($row = $result2->fetch_assoc()) 
          {
            $i++;
            echo"<tr><td>$i .</td>
                   <td>".$row["Student_Name"]."</td>
                   <td>".$row["Roll_No"]."</td>
                   <td>
                    <select name='grade[".$row["Roll_No"]."]' class='form-control form-input'>
                      <option value=''></option>
                      <option value='A'>A</option>
                      <option value='B'>B</option>
                      <option value='C'>C</option>
                    </select>

                    <input type='text' value='$subject' name='Sub_Name'style='display:none;'>
                    <input type='text' value='$teacher_id' name='teacher_id'style='display:none;'>
                    <input type='text' value='$exam_type' name='Test_Name'style='display:none;'></td>
                    <input type='text' value='$max_mark' name='Max_Marks'style='display:none;'>
                    <input type='text' value='$class' name='Class_Section'style='display:none;'>
                    <input type='text' value='".$row["Student_Name"]."' name='Student[".$row["Roll_No"]."]'style='display:none;'>
                    <input type='text' value='".$row["Roll_No"]."' name='Roll_No[".$row["Roll_No"]."]'style='display:none;'>
                        </td>
                      </tr>";
          }
          echo "</table><a class='btn btn-info login-btn' data-bs-toggle='modal' data-bs-target='#exampleModal1'>Submit</a>
          <!-- Modal -->
<div class='modal fade' id='exampleModal1' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header'>
        <h5 class='modal-title' id='exampleModalLabel'>Submit Marks</h5>
        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
      </div>
      <div class='modal-body'>
        Do you really want to submit grades?
      </div>
      <div class='modal-footer'>
        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
        <button type='submit' class='btn btn-primary login-btn1' type='submit'>Submit</button>
      </div>
    </div>
  </div>
</div></form>";
        }
        else 
        {
          echo "<tr><td>Empty Table</td></tr></table>";
        }
    }
  }
  else
  {
    /* Obtained Marks Data Form */
      echo "
      <form method='post' action='marks_add.php'>
      <thead style='background-color: #717171;color: white;'>
        <tr>
          <th scope='col'>S.No</th>
          <th scope='col'>Student Name</th>
          <th scope='col'>Roll Number</th>
          <th scope='col'>Obtained Marks</th>
        </tr>
      </thead>";

      $sql2 = "SELECT Student_Name,Roll_No FROM student_details
      WHERE Class_Section='$class' && Sub_Name ='$subject'";
      $result2 = $con->query($sql2);

      $sql3 = "SELECT DISTINCT Test_Name,Sub_Name,Class_Section
      FROM exam_marks WHERE Test_Name='$exam_type' && Sub_Name='$subject' && Class_Section='$class'";

      $result3 = mysqli_query($con,$sql3);
      $num3 = mysqli_num_rows($result3);

      if($num3)
      {
        echo "<tr><td>Data Already Filled</td></tr></table>";
      }
      else
      {
        if ($result2->num_rows > 0) 
        {
          // output data of each row
          $i =0;
          while($row = $result2->fetch_assoc()) 
          {
            $i++;
            echo"<tr><td>$i .</td>
                     <td>".$row["Student_Name"]."</td>
                     <td>".$row["Roll_No"]."</td>
                     <td><input type='number' name='obt_marks[".$row["Roll_No"]."]' class='form-marks' min='0' max='$max_mark' onkeyup=\"if(this.value > $max_mark) this.value ='' \">

                    <input type='text' value='$subject' name='Sub_Name'style='display:none;'>
                    <input type='text' value='$teacher_id' name='teacher_id'style='display:none;'>
                    <input type='text' value='$exam_type' name='Test_Name'style='display:none;'></td>
                    <input type='text' value='$max_mark' name='Max_Marks'style='display:none;'>
                    <input type='text' value='$class' name='Class_Section'style='display:none;'>
                    <input type='text' value='".$row["Student_Name"]."' name='Student[".$row["Roll_No"]."]'style='display:none;'>
                    <input type='text' value='".$row["Roll_No"]."' name='Roll_No[".$row["Roll_No"]."]'style='display:none;'>
                </tr>";
          }
          echo "</table><a class='btn btn-info login-btn' data-bs-toggle='modal' data-bs-target='#marks'>Submit</a>
          <!-- Modal -->
<div class='modal fade' id='marks' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header'>
        <h5 class='modal-title' id='exampleModalLabel'>Submit Marks</h5>
        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
      </div>
      <div class='modal-body'>
       Do you really want to submit marks?
      </div>
      <div class='modal-footer'>
        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
        <button type='submit' class='btn btn-primary '>Submit</button>
      </div>
    </div>
  </div>
</div>";
        }
        else 
        {
          echo "<tr><td>Empty Table</td></tr></table>";
        }
      }
    }
  }
}        
?>
        </div>
</div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type='text/javascript'>
	if ( window.history.replaceState ) 
		{
  			window.history.replaceState( null, null, window.location.href );
		}
</script>
<script>
  $(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});
</script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>

</body>
</html>