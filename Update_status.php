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
    <link rel="stylesheet" type="text/css" href="Styles/Update.css">
    <title>Update Status</title>
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
          <a class="nav-link" aria-current="page" href="Admin.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="Update_status.php">Update</a>
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
<div class="container-fluid" style="padding-top:75px;margin-bottom: 400px;text-align: center;">
  <h1 class="mt-2 mb-3">Update Permission</h1>
  <div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">
      <div class="d-flex justify-content-center align-items-center mt-3 mb-5">

          <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for Update." title="Type in Here..">
          <button class="btn-primary btn s_btn"><i class="fas fa-search"></i></button>
      </div>
      <div class="table-responsive" id="myUL">
          <table id="editable_table" class="table-bordered table table-striped" style="text-align: center;">
            <thead style="background-color: #717171;color: white;">
            <tr>
              <th scope="col">S.No</th>
              <th scope="col" style="display: none">ID</th>
              <th scope="col">Class Section</th>
              <th scope="col">Test Name</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <?php
                $insert = false;
                $sql_check1 = "SELECT * FROM update_status";
                $res3 = mysqli_query($con,$sql_check1);
                $num3 = mysqli_num_rows($res3);

                $sql_check2 = "SELECT DISTINCT Test_Name,Class_Section FROM exam_details";
                $res4 = mysqli_query($con,$sql_check2);
                $num4 = mysqli_num_rows($res4);

                if($num3!=$num4)
                {
                  $sql_del = "DELETE FROM update_status";
                  mysqli_query($con,$sql_del);

                  $sql3 = "SELECT DISTINCT Test_Name,Class_Section FROM exam_details WHERE Test_name!=''";
                  $result3 = mysqli_query($con,$sql3);
                  if ($result3->num_rows > 0) 
                  {
                    while($row1 = $result3->fetch_assoc())
                    {
                      $Class = $row1["Class_Section"];
                      $Test = $row1["Test_Name"];
                      $sql_insert = "INSERT INTO update_status (Test_Name,class_section,status)
                      VALUES ('$Test','$Class','Restrict')";                      
                      mysqli_query($con,$sql_insert);
                    }
                  }
                }

                $sql2 = "SELECT * FROM update_status";
                $result2 = $con->query($sql2);
                $i = 0;
                if ($result2->num_rows > 0) {
                  
                    // output data of each row

                    while($row = $result2->fetch_assoc()) {
                      $i++;
                      if($row["Status"]=="Allow"){
                        $s_color = "green";
                      }
                      else{
                        $s_color = "red";
                      }
                       echo"<tr><td>$i</td>
                                <td style='display:none'>".$row["ID"]."</td>
                                <td>".$row["class_section"]."</td>
                                <td>".$row["Test_Name"]."</td>
                                <td style='color:$s_color';>".$row["Status"]."</td>
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
      url:'updating_status.php',
      deleteButton: false,
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
        identifier:[1,"ID"],
        editable:[[4, 'Status', '{"Allow": "Allow", "Restrict": "Restrict"}']]
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
    input = document.getElementById("myInput");
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