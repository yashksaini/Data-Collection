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
$max_mark = "0";
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

    <title>Update/View Marks</title>
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
          <a class="nav-link active" href="update_marks.php">View/Updated Marks</a>
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
<div class="container-fluid" style="padding-top: 80px; ">
  <h3 style="text-align: center; margin-bottom: 10px;">All Filled Marks</h3>
   
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
      <div class="d-flex justify-content-center align-items-center mt-3 mb-5">
          <input type="text" id="myInput1" onkeyup="myFunction()" placeholder="Search for Test..." title="Type in Here..">
          <button class="btn-primary btn s_btn1"><i class="fas fa-search"></i></button>
    </div>

  <div class="table-responsive" style="height: 300px;overflow-y: scroll;margin-bottom: 20px;min-height: cover;"  id="myUL">

          <table  class="table-bordered table table-striped" style="text-align: center;">
            <thead style="background-color: #717171;color: white;">
            <tr>
              <th scope="col">Class</th>
              <th scope="col">Subject</th>
              <th scope="col">Test Name</th>
              <th scope="col">View/Update</th>
            </tr>
          </thead>
          <?php
                $sql2 = "SELECT  DISTINCT  Test_Name,Sub_Name,Class_Section FROM exam_marks WHERE Teacher_ID='$teacher_id'";
                $result2 = $con->query($sql2);

                if ($result2->num_rows > 0) {

                    // output data of each row

                    while($row = $result2->fetch_assoc()) {

                       echo"<tr>
                                <td>".$row["Class_Section"]."</td>
                                <td>".$row["Sub_Name"]."</td>
                                <td>".$row["Test_Name"]."</td>
                                <td>
                                <form method='post'>
                                  <input type='text' name='u_class' value='".$row["Class_Section"]."' style='display:none'>
                                  <input type='text' name='u_sub' value='".$row["Sub_Name"]."' style='display:none'>
                                  <input type='text' name='u_test' value='".$row["Test_Name"]."' style='display:none'>
                                  <button class='btn btn-success rounded-0' type='submit'>View</button>
                                </form></td>
                            </tr>";
                    }
                  }
                  else 
                  {
                    echo "<tr><td>No Marks Filled Yet</td></tr>";
                  }
              ?>
              
          </table>
        </div>
      </div>
    </div>
</div>
<div class="container">
  <?php
    if(isset($_POST["u_class"]))
    {
      $u_class = $_POST['u_class'];
      $u_test = $_POST['u_test'];
      $u_sub = $_POST['u_sub'];

      $sql4 = "SELECT * FROM update_status WHERE Test_Name ='$u_test'&& class_section='$u_class'";
      $result4 = mysqli_query($con,$sql4);
      $up = $result4->fetch_assoc();
      $table_id = "e_table";

      $test_status = $up["Status"];
      /* Displaying Message to user about the status of data (editable or not) */
      if($test_status=='Restrict')
      {
        echo "<p style='text-align:center;color:red;'>Persmission not given by admin to EDIT marks for this test. Ask 'Admin' to allow to EDIT marks for this test.</p>";
        $table_id1 = "5";
      }
      else
      {
        $table_id1 = "";
        echo "<h5 style='text-align:center;color:green;'>You can EDIT marks of students.</h5>";
      }
      /* Table According to Grade or Marks */
      $sql = "SELECT  Max_Marks FROM exam_details WHERE Test_Name ='$u_test' && Class_Section = '$u_class' && Sub_Name='$u_sub'";
      $result = mysqli_query($con,$sql);
      $show= $result->fetch_assoc();
      $max_mark = $show["Max_Marks"];
      if($max_mark=="")
      {
            $table_id="e_table1".$table_id1;
            $max_mark = "Grades";
            echo"<table class='table-responsive table-bordered table'>
                  <tr>
                    <td>Class: <b> $u_class </b></td>
                    <td>Subject:<b> $u_sub</b></td>
                    <td>Maximum Marks:<b> $max_mark</b></td>
                    <td>Exam Type:<b> $u_test</b></td>
                  </tr>
                 </table>";
            echo"
            <div class='table-responsive mt-5 mb-5'>
              <table  class='table-bordered table' id='$table_id' style='text-align: center;'>
                <thead style='background-color: #717171;color: white;'>
                  <tr>
                    <th scope='col'>S.No</th>
                    <th scope='col' style='display:none;'>ID</th>
                    <th scope='col'>Student Name</th>
                    <th scope='col'>Roll Number</th>
                    <th scope='col'>Grade</th>
                  </tr>
                </thead>";
            $sql1 = "SELECT * FROM exam_marks
            WHERE Class_Section='$u_class' && Sub_Name ='$u_sub' && Test_Name='$u_test'";
            $result1 = $con->query($sql1);

            if ($result1->num_rows > 0) 
            {
              // output data of each row
              $i =0;
              while($row = $result1->fetch_assoc()) 
              {
                $i++;
                echo"<tr><td>$i .</td>
                        <td style='display:none;'>".$row["ID"]."</td>
                       <td>".$row["Student_Name"]."</td>
                       <td>".$row["Roll_No"]."</td>
                       <td>".$row["Grade"]."</td>
                     </tr>";
              }

              echo "</table></div>";
              echo "<script type='text/javascript'>
  $(document).ready(function(){
    $(\"#e_table1\").Tabledit({
      url:\"update_grade.php\",
      deleteButton: false,
      buttons: {
          edit: {
              class: 'btn btn-sm btn-primary rounded-0',
              html: '<i class=\"fas fa-edit\"></i>',
              action: 'edit'
          },
          delete: {
              class: 'btn btn-sm btn-danger  ms-4 rounded-0',
              html: '<i class=\"far fa-trash-alt\"></i>',
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
        identifier:[1,\"ID\"],
        editable:[[4,'Grade', '{ \" \": \" \",\"A\": \"A\", \"B\": \"B\", \"C\": \"C\", \"D\": \"D\"}']]
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
</script>";

            }
      }
      else
      {

            $table_id="e_table".$table_id1;
           echo"<table class='table-responsive table-bordered table'>
                  <tr>
                    <td>Class: <b>$u_class </b></td>
                    <td>Subject:<b> $u_sub</b></td>
                    <td>Maximum Marks: <b>$max_mark</b></td>
                    <td>Exam Type: <b>$u_test</b></td>
                  </tr>
                 </table>";
            echo"
            <div class='table-responsive mt-5 mb-5' >
              <table  class='table-bordered table' style='text-align: center;' id='$table_id'>
                <thead style='background-color: #717171;color: white;'>
                  <tr>
                    <th scope='col'>S.No</th>
                    <th scope='col' style='display:none;'>ID</th>
                    <th scope='col'>Student Name</th>
                    <th scope='col'>Roll Number</th>
                    <th scope='col'>Obt Marks</th>
                  </tr>
                </thead>";
            $sql1 = "SELECT * FROM exam_marks
            WHERE Class_Section='$u_class' && Sub_Name ='$u_sub' && Test_Name='$u_test'";
            $result1 = $con->query($sql1);

            if ($result1->num_rows > 0) 
            {
              // output data of each row
              $i =0;
              while($row = $result1->fetch_assoc()) 
              {
                $i++;
                echo"<tr><td>$i .</td>
                        <td style='display:none;'>".$row["ID"]."</td>
                       <td>".$row["Student_Name"]."</td>
                       <td>".$row["Roll_No"]."</td>
                       <td>".$row["Obt_Marks"]."</td>
                     </tr>";
              }
              echo "</table></div>";
              echo "<script type='text/javascript'>
  $(document).ready(function(){
    $('#e_table').Tabledit({
      url:'update_mark.php',
      deleteButton: false,
      buttons: {
          edit: {
              class: 'btn btn-sm btn-primary rounded-0',
              html: '<i class=\"fas fa-edit\"></i>',
              action: 'edit'
          },
          delete: {
              class: 'btn btn-sm btn-danger  ms-4 rounded-0',
              html: '<i class=\"far fa-trash-alt\"></i>',
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
        identifier:[1,\"ID\"],
        editable:[[4,'Obt_Marks']]
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
</script>";

            }
      }
    }
    ?>
</div>



<script type='text/javascript'>
	if ( window.history.replaceState ) 
		{
  			window.history.replaceState( null, null, window.location.href );
		}
</script>
<script type="text/javascript">
  /*!
 * Tabledit v1.2.3 (https://github.com/markcell/jQuery-Tabledit)
 * Copyright (c) 2015 Celso Marques
 * Licensed under MIT (https://github.com/markcell/jQuery-Tabledit/blob/master/LICENSE)
 */
if ("undefined" == typeof jQuery) throw new Error("Tabledit requires jQuery library.");
! function(t) {
    "use strict";
    t.fn.Tabledit = function(e) {
        function n(e) {
            var n = i.find(".tabledit-input").serialize() + "&action=" + e,
                a = d.onAjax(e, n);
            if (a === !1) return !1;
            var l = t.post(d.url, n, function(t, n, a) { e === d.buttons.edit.action && (s.removeClass(d.dangerClass).addClass(d.warningClass), setTimeout(function() { i.find("tr." + d.warningClass).removeClass(d.warningClass) }, 1400)), d.onSuccess(t, n, a) }, "json");
            return l.fail(function(t, n, i) { e === d.buttons["delete"].action ? (o.removeClass(d.mutedClass).addClass(d.dangerClass), o.find(".tabledit-toolbar button").attr("disabled", !1), o.find(".tabledit-toolbar .tabledit-restore-button").hide()) : e === d.buttons.edit.action && s.addClass(d.dangerClass), d.onFail(t, n, i) }), l.always(function() { d.onAlways() }), l
        }
        if (!this.is("table")) throw new Error("Tabledit only works when applied to a table.");
        var i = this,
            a = { url: window.location.href, inputClass: "form-control input-sm", toolbarClass: "btn-toolbar", groupClass: "btn-group btn-group-sm", dangerClass: "danger", warningClass: "warning", mutedClass: "text-muted", eventType: "click", rowIdentifier: "id", hideIdentifier: !1, autoFocus: !0, editButton: !0, deleteButton: !0, saveButton: !0, restoreButton: !0, buttons: { edit: { "class": "btn btn-sm btn-default", html: '<span class="glyphicon glyphicon-pencil"></span>', action: "edit" }, "delete": { "class": "btn btn-sm btn-default", html: '<span class="glyphicon glyphicon-trash"></span>', action: "delete" }, save: { "class": "btn btn-sm btn-success", html: "Save" }, restore: { "class": "btn btn-sm btn-warning", html: "Restore", action: "restore" }, confirm: { "class": "btn btn-sm btn-danger", html: "Confirm" } }, onDraw: function() {}, onSuccess: function() {}, onFail: function() {}, onAlways: function() {}, onAjax: function() {} },
            d = t.extend(!0, a, e),
            s = "undefined",
            o = "undefined",
            l = "undefined",
            r = {
                columns: {
                    identifier: function() {
                        d.hideIdentifier && i.find("th:nth-child(" + parseInt(d.columns.identifier[0]) + "1), tbody td:nth-child(" + parseInt(d.columns.identifier[0]) + "1)").hide();
                        var e = i.find("tbody td:nth-child(" + (parseInt(d.columns.identifier[0]) + 1) + ")");
                        e.each(function() {
                            var e = '<span class="tabledit-span tabledit-identifier">' + t(this).text() + "</span>",
                                n = '<input class="tabledit-input tabledit-identifier" type="hidden" name="' + d.columns.identifier[1] + '" value="' + t(this).text() + '" disabled>';
                            t(this).html(e + n), t(this).parent("tr").attr(d.rowIdentifier, t(this).text())
                        })
                    },
                    editable: function() {
                        var k = "<?php echo"$max_mark"?>";
                        if(k!="0")
                        {
                        for (var e = 0; e < d.columns.editable.length; e++) {
                            var n = i.find("tbody td:nth-child(" + (parseInt(d.columns.editable[e][0]) + 1) + ")");
                            n.each(function() {
                                var n = t(this).text();
                                d.editButton || t(this).css("cursor", "pointer");
                                var i = '<span class="tabledit-span">' + n + "</span>";
                                if ("undefined" != typeof d.columns.editable[e][2]) {
                                    var a = '<select class="tabledit-input ' + d.inputClass + '" name="' + d.columns.editable[e][1] + '" style="display: none;" disabled>';
                                    t.each(jQuery.parseJSON(d.columns.editable[e][2]), function(t, e) { a += n === e ? '<option value="' + t + '" selected>' + e + "</option>" : '<option value="' + t + '">' + e + "</option>" }), a += "</select>"
                                } else var a = '<input class="tabledit-input ' + d.inputClass + '" type="number" onkeyup="if(this.value > <?php echo"$max_mark"?>) this.value =null;" max="<?php echo"$max_mark"?>" min="0" name="' + d.columns.editable[e][1] + '" value="' + t(this).text() + '" style="display: none;" disabled>';
                                t(this).html(i + a), t(this).addClass("tabledit-view-mode")
                            })
                        }
                    }
                    },
                    toolbar: function() {
                        if (d.editButton || d.deleteButton) {
                            var t = "",
                                e = "",
                                n = "",
                                a = "",
                                s = "";
                            0 === i.find("th.tabledit-toolbar-column").length && i.find("tr:first").append('<th class="tabledit-toolbar-column"></th>'), d.editButton && (t = '<button type="button" class="tabledit-edit-button ' + d.buttons.edit["class"] + '" style="float: none;">' + d.buttons.edit.html + "</button>"), d.deleteButton && (e = '<button type="button" class="tabledit-delete-button ' + d.buttons["delete"]["class"] + '" style="float: none;">' + d.buttons["delete"].html + "</button>", s = '<button type="button" class="tabledit-confirm-button ' + d.buttons.confirm["class"] + '" style="display: none; float: none;">' + d.buttons.confirm.html + "</button>"), d.editButton && d.saveButton && (n = '<button type="button" class="tabledit-save-button ' + d.buttons.save["class"] + '" style="display: none; float: none;">' + d.buttons.save.html + "</button>"), d.deleteButton && d.restoreButton && (a = '<button type="button" class="tabledit-restore-button ' + d.buttons.restore["class"] + '" style="display: none; float: none;">' + d.buttons.restore.html + "</button>");
                            var o = '<div class="tabledit-toolbar ' + d.toolbarClass + '" style="text-align: left;">\n                                           <div class="' + d.groupClass + '" style="float: none;">' + t + e + "</div>\n                                           " + n + "\n                                           " + s + "\n                                           " + a + "\n                                       </div></div>";
                            i.find("tr:gt(0)").append('<td style="white-space: nowrap; width: 1%;">' + o + "</td>")
                        }
                    }
                }
            },
            u = {
                view: function(e) {
                    var n = t(e).parent("tr");
                    t(e).parent("tr").find(".tabledit-input.tabledit-identifier").prop("disabled", !0), t(e).find(".tabledit-input").blur().hide().prop("disabled", !0), t(e).find(".tabledit-span").show(), t(e).addClass("tabledit-view-mode").removeClass("tabledit-edit-mode"), d.editButton && (n.find("button.tabledit-save-button").hide(), n.find("button.tabledit-edit-button").removeClass("active").blur())
                },
                edit: function(e) {
                    c.reset(e);
                    var n = t(e).parent("tr");
                    n.find(".tabledit-input.tabledit-identifier").prop("disabled", !1), t(e).find(".tabledit-span").hide();
                    var i = t(e).find(".tabledit-input");
                    i.prop("disabled", !1).show(), d.autoFocus && i.focus(), t(e).addClass("tabledit-edit-mode").removeClass("tabledit-view-mode"), d.editButton && (n.find("button.tabledit-edit-button").addClass("active"), n.find("button.tabledit-save-button").show())
                }
            },
            b = {
                reset: function(e) {
                    t(e).each(function() {
                        var e = t(this).find(".tabledit-input"),
                            n = t(this).find(".tabledit-span").text();
                        e.is("select") ? e.find("option").filter(function() { return t.trim(t(this).text()) === n }).attr("selected", !0) : e.val(n), u.view(this)
                    })
                },
                submit: function(e) {
                    var i = n(d.buttons.edit.action);
                    i !== !1 && (t(e).each(function() {
                        var e = t(this).find(".tabledit-input");
                        t(this).find(".tabledit-span").text(e.is("select") ? e.find("option:selected").text() : e.val()), u.view(this)
                    }), s = t(e).parent("tr"))
                }
            },
            c = {
                reset: function(t) { i.find(".tabledit-confirm-button").hide(), i.find(".tabledit-delete-button").removeClass("active").blur() },
                submit: function(e) {
                    c.reset(e), t(e).parent("tr").find("input.tabledit-identifier").attr("disabled", !1);
                    var i = n(d.buttons["delete"].action);
                    t(e).parents("tr").find("input.tabledit-identifier").attr("disabled", !0), i !== !1 && (t(e).parent("tr").addClass("tabledit-deleted-row"), t(e).parent("tr").addClass(d.mutedClass).find(".tabledit-toolbar button:not(.tabledit-restore-button)").attr("disabled", !0), t(e).find(".tabledit-restore-button").show(), o = t(e).parent("tr"))
                },
                confirm: function(e) { i.find("td.tabledit-edit-mode").each(function() { b.reset(this) }), t(e).find(".tabledit-delete-button").addClass("active"), t(e).find(".tabledit-confirm-button").show() },
                restore: function(e) {
                    t(e).parent("tr").find("input.tabledit-identifier").attr("disabled", !1);
                    var i = n(d.buttons.restore.action);
                    t(e).parents("tr").find("input.tabledit-identifier").attr("disabled", !0), i !== !1 && (t(e).parent("tr").removeClass("tabledit-deleted-row"), t(e).parent("tr").removeClass(d.mutedClass).find(".tabledit-toolbar button").attr("disabled", !1), t(e).find(".tabledit-restore-button").hide(), l = t(e).parent("tr"))
                }
            };
        return r.columns.identifier(), r.columns.editable(), r.columns.toolbar(), d.onDraw(), d.deleteButton && (i.on("click", "button.tabledit-delete-button", function(e) {
            if (e.handled !== !0) {
                e.preventDefault();
                var n = t(this).hasClass("active"),
                    i = t(this).parents("td");
                c.reset(i), n || c.confirm(i), e.handled = !0
            }
        }), i.on("click", "button.tabledit-confirm-button", function(e) {
            if (e.handled !== !0) {
                e.preventDefault();
                var n = t(this).parents("td");
                c.submit(n), e.handled = !0
            }
        })), d.restoreButton && i.on("click", "button.tabledit-restore-button", function(e) { e.handled !== !0 && (e.preventDefault(), c.restore(t(this).parents("td")), e.handled = !0) }), d.editButton ? (i.on("click", "button.tabledit-edit-button", function(e) {
            if (e.handled !== !0) {
                e.preventDefault();
                var n = t(this),
                    a = n.hasClass("active");
                b.reset(i.find("td.tabledit-edit-mode")), a || t(n.parents("tr").find("td.tabledit-view-mode").get().reverse()).each(function() { u.edit(this) }), e.handled = !0
            }
        }), i.on("click", "button.tabledit-save-button", function(e) { e.handled !== !0 && (e.preventDefault(), b.submit(t(this).parents("tr").find("td.tabledit-edit-mode")), e.handled = !0) })) : (i.on(d.eventType, "tr:not(.tabledit-deleted-row) td.tabledit-view-mode", function(t) { t.handled !== !0 && (t.preventDefault(), b.reset(i.find("td.tabledit-edit-mode")), u.edit(this), t.handled = !0) }), i.on("change", "select.tabledit-input:visible", function() { event.handled !== !0 && (b.submit(t(this).parent("td")), event.handled = !0) }), t(document).on("click", function(t) {
            var e = i.find(".tabledit-edit-mode");
            e.is(t.target) || 0 !== e.has(t.target).length || b.reset(i.find(".tabledit-input:visible").parent("td"))
        })), t(document).on("keyup", function(t) {
            var e = i.find(".tabledit-input:visible"),
                n = i.find(".tabledit-confirm-button");
            if (e.length > 0) var a = e.parents("td");
            else { if (!(n.length > 0)) return; var a = n.parents("td") }
            switch (t.keyCode) {
                case 9:
                    d.editButton || (b.submit(a), u.edit(a.closest("td").next()));
                    break;
                case 13:
                    b.submit(a);
                    break;
                case 27:
                    b.reset(a), c.reset(a)
            }
        }), this
    }
}(jQuery);
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
    <script type="text/javascript">
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