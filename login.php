<?php
$servername="localhost";//ip address of the db server, it is localhost here since both the web server and the db server are on the same system
$username="root";//wip";
$password="";//   Password";
$dbname="project";

$conn=new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error){
	die("Connection failed:".$conn->connect_error);
}
 
extract($_POST);
echo $name;
//$name = $_POST["name"];
//$password = $_POST['password'];
 
$sql="INSERT INTO users (username) VALUES ('$name')";
//$sql="UPDATE water_level SET Status='$status' WHERE Road_No='$roadno'";
if($conn->query($sql)===TRUE){
	echo 'success';
}
/*if($conn->query($sql)===TRUE){
	echo 'record modified';
}*/
else
{
	echo 'error';
}

$conn->close();
?>