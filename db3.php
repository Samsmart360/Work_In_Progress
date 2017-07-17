<?php
$servername="localhost";//ip address of the db server, it is localhost here since both the web server and the db server are on the same system
$username="id1983173_iot";//wip";
$password="iot123456";//   Password";
$dbname="id1983173_iot";

$conn=new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error){
	die("Connection failed:".$conn->connect_error);
}
extract($_GET);
//$sql="INSERT INTO sample (Name,Domain) VALUES ('$name','$domain')";
$sql="UPDATE water_level SET Status='$status' WHERE Road_No='$roadno'";
/*if($conn->query($sql)===TRUE){
	echo 'record added';
}*/
if($conn->query($sql)===TRUE){
	echo 'record modified';
}
else
{
	echo 'error';
}

$conn->close();
?>

