<!doctype html>
<head>
    <title>
        LEAVE RECORD
    </title>
    <link rel="stylesheet" href="pop.css" >
</head>
<script>
    function openpop() {
        document.getElementById("pop").style.display = "block";
    }
    function closepop() {
         document.getElementById("pop").style.display = "none";
     }

     function submitform()
  {
    document.filterform.submit();
  }
  function submitform1()
  {
    document.filterform1.submit();
  }
</script> 
<body ><center>
 <h1>Leave Management</h1>

<?php

$host="localhost";
$dbusername="root";
$dbpassword="";
$dbname="leave";
$dbconn =mysqli_connect($host,$dbusername,$dbpassword,$dbname);
session_start();

if(isset($_POST["logout"]))
{
  session_destroy();
  echo "<script> window.location=\"home.php\"</script>";
}


if(isset($_POST["adminlogin"]))
{ 
$adminid=$_POST['adminid'];
$adminpass=$_POST['adminpass'];

$sql1=mysqli_query($dbconn,"SELECT password from admin where admin_ID= '".$adminid."'"); 
$dummy=0;
    while($row=mysqli_fetch_assoc($sql1))
  {$dummy=1;
    if($adminpass==$row['password'])
    {
        $_SESSION['adminid']=$adminid;
      echo "<script>alert(\"Logged in successfully\")</script>";
      echo "<script> window.location=\"home.php\"</script>";
    }
    else 
    {
         echo "<script>alert(\"Enter valid credentials\")</script>";
      echo "<script> window.location=\"home.php\"</script>";
    }
  }
if($dummy==0)
  {
      echo "<script>alert(\"Enter valid credentials\")</script>";
      
      echo "<script> window.location=\"home.php\"</script>";
  }

}

else if(isset($_POST['userlogin']))
{
$username=$_POST['username'];
$userpass=$_POST['userpass'];

$sql1=mysqli_query($dbconn,"SELECT password FROM user WHERE user_ID= '".$username."'"); 
$dummy=0;
    while($row=mysqli_fetch_assoc($sql1))
  {$dummy=1;
    if($userpass==$row['password'])
    {
       
$_SESSION['username']=$username;
      echo "<script>alert(\"Logged in successfully\")</script>";
      echo "<script> window.location=\"home.php\"</script>";

    }
    else 
    {
      echo "<script>alert(\"Enter valid credentials\")</script>";
      echo "<script> window.location=\"home.php\"</script>";
    }
  }
if($dummy==0)
  {
      echo "<script>alert(\"Enter valid credentials\")</script>";
      echo "<script> window.location=\"home.php\"</script>";
  }
}
else if(isset($_SESSION["username"]) )
{
  $username=$_SESSION['username']; ?> 
  <c><form action="?" method="POST">
  <table>
  <tr>
          <td><button type="submit" name="my_leaves" >My Leaves</button></td></form>
          <form action="?" method="POST">   
          <td><button type="submit" name="Apply" >Apply for leave</button></td>
          <td> <button type="submit" name="logout"> Logout</button></td>
  </tr>
</form>
</table></c>
<?php
if(isset($_POST['Apply']))
{
  ?>
  <table>
  <form action="?" method="POST">
  <tr > <td >User_ID</td> <td ><?php echo $username;?></td></tr>
   <tr><td >Date</td> 
   <td><input required type="date" name="applydate" data-date-inline-picker min="<?php echo date('Y-m-d');?>" required></td></tr>
   <tr><td >Type</td><td> <input type="text" name="applytype" required ></td></tr>
  <tr> <td >Reason</td><td><input type="text" name="applyreason" required> </td></tr>
  <tr> <td><button type="submit" name="applyform" >Apply</button></td> </tr>
  </form> </table>
   <?php                                     
}
else
{ ?>
    <table>
    <form action="" method="POST" name="filterform">
    <tr >
    <td >DATE</td>
    <td ><input type="date" name="mydate" data-date-inline-picker onchange="submitform()"/></td></tr></form>
    
    <form action="" method="POST" name="filterform1">
    <tr><td>STATUS</td><td>
     <select name="status" onchange="submitform1()">
      <option value="ok">--Select--</option>
      <option value="all_leaves">All leaves</option>
      <option value="approved">Approved</option>
      <option value="Disapproved">Disapproved</option>
      <option value="waiting for approval">Waiting for approval</option>
      </select> 
      </td></tr></form></table>

<?php
  
  if(isset($_POST['status']))
  {
      $status=$_POST['status'];
      if($status!="ok"&& $status!="all_leaves")
      {$result=mysqli_query($dbconn,"SELECT * FROM leaves WHERE user_ID = '".$username."'and status='".$status."'");}
      else
      {
          $result=mysqli_query($dbconn,"SELECT * FROM leaves WHERE user_ID = '".$username."'");
      }
  }
  else if(isset($_POST['mydate']))
  {
    $mydate=$_POST['mydate']; 
    $result=mysqli_query($dbconn,"SELECT * FROM leaves WHERE user_ID = '".$username."'and date='".$mydate."'");
  }
  else
  {    
  $result=mysqli_query($dbconn,"SELECT * FROM leaves WHERE user_ID = '".$username."'");
  } 
  echo "<br><br><center><table><tr><td><u>DATE</u></td><td><u>TYPE</u></td><td><u>STATUS</u></td><td><u>REMARKS</u></td><td><u>REASON</u></td></tr>";
   while($row=mysqli_fetch_assoc($result))
   {
      echo "<tr>";
      echo "<td> " . $row["date"]. "</td><td>" . $row["type"]. "</td><td>" . $row["status"]. "</td><td>" . $row["remarks"]. "</td><td>" . $row["reason"]. "</td><td>";
      if($row["status"]=="waiting for approval")
      {
      ?>
      <form method="POST" action="?">
      <input type="hidden" name="canceldate" value="<?php echo $row["date"]; ?>">
      <input type="hidden" name="canceltype" value="<?php echo $row["type"]; ?>">
      <input type="hidden" name="cancelreason" value="<?php echo $row["reason"]; ?>">
      <button type="submit" name="Cancellation" >Cancel</button>
       </form>
      <?php          
       }
       echo "</td></tr>";
    }
    echo "</table>";
   }
   
if(isset($_POST['Cancellation']))
{
 // echo "hi";
$canceldate=$_POST['canceldate'];
$sqlk="delete from leaves where user_ID='".$username."' and date ='".$canceldate."'";
$resultk=mysqli_query($dbconn,$sqlk);
echo "<script>alert(\"cancelled Successfully\")</script>";   
echo "<script> window.location=\"home.php\"</script>";
}
if(isset($_POST['applyform']))
   {
    $applyreason=$_POST['applyreason'];
    $applydate=$_POST['applydate'];
    $applytype=$_POST['applytype'];   
    $sqlll="SELECT * FROM LEAVES where date='".$applydate."' and user_ID='".$username."'";
    $sqllll="SELECT * FROM LEAVES where date= ? and user_ID= ? ";
    $stmt=$dbconn->prepare($sqllll);
    $stmt->bind_param("ss",$applydate,$username);
    $stmt->execute();
    $stmt->store_result();
    $rnum=$stmt->num_rows;
    if($rnum==0)
    {
      $applystatus="waiting for approval";
      $sqlm="INSERT INTO leaves(date, user_ID, type, status, reason) VALUES ('".$applydate."','".$username."','".$applytype."','".$applystatus."','".$applyreason."')";
      $result=mysqli_query($dbconn,$sqlm);
      echo "<script>alert(\"Applied Successfully\")</script>";
   }
    else
    {
      echo "<script>alert(\"Already applied\")</script>";
     }
   }   
//code after userlogin

}
else if(isset($_SESSION["adminid"]) )
{
  $adminid=$_SESSION['adminid']; 

  
  echo "<form method=\"post\" action=\"?\"><button type=\"submit\" name=\"logout\"> Logout</button><br></form>";

if((isset($_POST['Approve']))||(isset($_POST['Disapprove'])))
  {
  $test=isset($_POST['Approve']);
  $test1=isset($_POST['Disapprove']);
  $remarks=$_POST["remarks"];
  if(isset ($_SESSION["changedate"]))
  {
    $changedate=$_SESSION['changedate'];
  }
  if(isset ($_SESSION["changeuserID"]))
  {
    $changeuserID=$_SESSION['changeuserID'];
  }



 if($test)
        $changestatus="Approved";
        else 
        $changestatus="Disapproved";

        $sql="UPDATE leaves SET status = '".$changestatus."',remarks='".$remarks."' WHERE date='".$changedate."' and user_ID='".$changeuserID."'";
    $result=mysqli_query($dbconn,$sql);
    echo "<script>alert(\"Successfully ".$changestatus."\")</script>";
  }

if(isset($_POST["change"]))
  { 
  //echo '<script>openapprove()</script>';
    $changedate=$_POST['date'];
    $changeuserID=$_POST['user_ID'];
    $changetype=$_POST['type'];   
    $changereason=$_POST['reason'];
    $_SESSION['changedate']=$changedate;
    $_SESSION['changeuserID']=$changeuserID;
    $_SESSION['changetype']=$changetype;
    $_SESSION['changereason']=$changereason; ?>
  <div id="popapprove" class="box" >
  <div>
      <button class="close" onclick="closeapprove()">X</button>
      <form action="?" method="post">   

    <table>
    <tr><td>Date: </td><td><?php echo $changedate ?></td></tr>
    <tr><td>User-ID: </td><td><?php echo $changeuserID ?></td></tr>
    <tr><td>Type: </td><td><?php echo $changetype ?></td></tr>
    <tr><td>Reason: </td><td><?php echo $changereason ?></td></tr>
    <tr><td>Remarks</td><td><input type ="text" required  name="remarks"></td></tr>
    <tr><td><button type="submit" name="Approve" >Approve</button></td><td><button type="submit" name="Disapprove" >Disapprove</button></td>
    </tr></table>
  
       </form>
   </div>
  </div>


  <?php
}

  ?>
  <div id="pop" class="box" hidden >
  <div>
      <button class="close" onclick="closepop()">X</button>
      <form action="?" method="post">     
       DATE  <input type="date" name="date" ><br>
       User_ID  <input type="text" name="userID" ><br>
       <c><button type="submit" name="apply">Apply</button></c>
       </form>
   </div>
  </div>

    <br><button onclick="openpop()"> Filter </button> 
    </center> <table>
   <form action="" method="POST">
   <td>  STATUS:   </td>
  <tr><td> <button type="submit" name="all_leaves" >All leaves</button></td></tr>
  <tr><td><button type="submit" name="approved" >Approved</button></td></tr>
  <tr><td><button type="submit" name="disapproved" >Disapproved</button></td></tr>
  <tr> <td> <button type="submit" name="waiting" >Waiting</button></td></tr>                    
  </form>
  </table><center>
  <table>
      
<?php
$result=mysqli_query($dbconn,"SELECT * FROM leaves ");
if(isset ($_SESSION["status"]))
{
  $status=$_SESSION["status"];
}
if(isset($_POST['apply']))
{$userID=$_POST['userID'];
$date=$_POST['date'];
}

if(isset($_POST['approved']))
{
  $status='approved';
  $_SESSION["status"]=$status;
}
else if(isset($_POST['all_leaves']))
{
  $status='';
  $_SESSION["status"]=$status;
}
else if(isset($_POST['disapproved']))
{
  $status='disapproved';
  $_SESSION["status"]=$status;
}
else if(isset($_POST['waiting']))
{
  $status='waiting for approval';
  $_SESSION["status"]=$status;
}

//session_start();
//$_SESSION['userID']=$userID;


if(!empty($userID)&&!empty($status)&&!empty($date))
  $result=mysqli_query($dbconn,"SELECT * FROM leaves WHERE user_ID = '".$userID."'and status='".$status."'and date='".$date."'");
else if(!empty($userID)&&!empty($status)&&empty($date))
  $result=mysqli_query($dbconn,"SELECT * FROM leaves WHERE user_ID = '".$userID."'and status='".$status."'");
else if(!empty($userID)&&empty($status)&&!empty($date))
  $result=mysqli_query($dbconn,"SELECT * FROM leaves WHERE user_ID = '".$userID."'and date='".$date."'");
else if(!empty($userID)&&empty($status)&&empty($date))
  $result=mysqli_query($dbconn,"SELECT * FROM leaves WHERE user_ID = '".$userID."'");
else if(empty($userID)&&!empty($status)&&!empty($date))
  $result=mysqli_query($dbconn,"SELECT * FROM leaves WHERE status='".$status."'and date='".$date."'");
else if(empty($userID)&&!empty($status)&&empty($date))
  $result=mysqli_query($dbconn,"SELECT * FROM leaves WHERE status='".$status."'");
else if(empty($userID)&&empty($status)&&!empty($date))
  $result=mysqli_query($dbconn,"SELECT * FROM leaves WHERE date='".$date."'");

echo "<br><br><center><table><tr><td><u>DATE</u></td><td><u>USER ID</u></td><td><u>TYPE</u></td><td><u>STATUS</u></td><td><u>REMARKS</u></td><td><u>REASON</u></td></tr>";

while($row=mysqli_fetch_assoc($result))
{
echo "<tr>";
echo "<td> " . $row["date"]. "</td><td>" . $row["user_ID"]. "</td><td>" . $row["type"]. "</td><td>" . $row["status"]. "</td><td>" . $row["remarks"]. "</td><td>" . $row["reason"]. "</td><td>";
if($row["status"]=="waiting for approval")
{?>
  <form method="POST" action="?">
          <input type="hidden" name="date" value="<?php echo $row["date"]; ?>">
          <input type="hidden" name="user_ID" value="<?php echo $row["user_ID"]; ?>">
          <input type="hidden" name="type" value="<?php echo $row["type"]; ?>">
          <input type="hidden" name="reason" value="<?php echo $row["reason"]; ?>">
          <input type="hidden" name="remarks" value="<?php echo $row["remarks"]; ?>">
          <button type="submit" onclick="openapprove()" name="change" > Change </button>          
      </form>
      <?php  echo "</td></tr>";
}         
}
}
else{
?>
    
<form method="post" action="?">
<button type="submit" name="adminpop">adminlogin</button>
<!-- </form>
<form method="post" action="?"> -->
<button type="submit" name="userpop">userlogin</button>
</form>
<?php } 

if(isset($_POST['adminpop']))
{
?>
<form method="post" action="?">
  <br>
  <br>
AdminID <input type="text" name="adminid" required></input><br>
Password <input type="password" name="adminpass" required></input><br>
<button type="submit" name="adminlogin">Login</button>
</form>

<?php } ?>

<?php
if(isset($_POST['userpop']))
{
?>
<div id="userdetails">
<div>
<form method="post" action="?">
<br>
  <br>
username <input required type="text" name="username"></input><br>
Password <input required type="password" name="userpass"></input><br>
<button type="submit" name="userlogin">Login</button>
</form>
</div>
</div>  
<?php } ?> </center>   
</body>
</html>