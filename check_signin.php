<?php
$servername="localhost";//ip address of the db server, it is localhost here since both the web server and the db server are on the same system
$username="id1983173_iot";//wip";
$password="iot123456";//   Password";
$dbname="id1983173_iot";

$conn=new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error){
	die("Connection failed:".$conn->connect_error);
}
extract($_POST);
$sql = "SELECT * FROM login_details WHERE name='$name' AND password='$password'";
 
$res = mysqli_query($con,$sql);
 
$check = mysqli_fetch_array($res);
 
if(isset($check)){
echo 1;
}else{
echo 0;
}

$conn->close();
?>