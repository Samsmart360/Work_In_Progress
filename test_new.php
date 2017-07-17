<?php

$city = $_REQUEST['city'];
$servername = "localhost";
$username="root";
$password="";
$dbname="project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
} 

function decodePolylineToArray($encoded)
{
  $length = strlen($encoded);
  $index = 0;
  $points = array();
  $lat = 0;
  $lng = 0;

  while ($index < $length)
  {
    $b = 0;
    $shift = 0;
    $result = 0;
    do
    {
      $b = ord(substr($encoded, $index++)) - 63;
      $result |= ($b & 0x1f) << $shift;
      $shift += 5;
    }
    while ($b >= 0x20);
    $dlat = (($result & 1) ? ~($result >> 1) : ($result >> 1));
    $lat += $dlat;
    $shift = 0;
    $result = 0;
    do
    {
      $b = ord(substr($encoded, $index++)) - 63;
      $result |= ($b & 0x1f) << $shift;
      $shift += 5;
    }
    while ($b >= 0x20);

    $dlng = (($result & 1) ? ~($result >> 1) : ($result >> 1));
    $lng += $dlng;
    $points[] = array($lat * 1e-5, $lng * 1e-5);
  }

  return $points;
}

header('Content-Type: application/json');
$dis=array();
$time=array();
$score=array();
$color=array();
$locations=array();
$inter=array();
$origin = '12.9352367,77.5363268';
$destination = '12.9016028,77.7075121';
$response = file_get_contents('https://maps.googleapis.com/maps/api/directions/json?origin='.$origin.'&destination='.$destination.'&alternatives=true&avoid=tolls&key=AIzaSyDkfPkG972wc-SeoRJgFY3E4cEzxpYE3l4');
$count=0;
$response1=json_decode($response,true);
/*for($i = 0;$i < 3;$i++)
  {*/
  $temp =  $response1["routes"][0]["legs"][0]["steps"];
    $count=0;
    //echo count($temp);
    for($j = 0;$j < count($temp);$j++)//steps
    {
        $dis[$j]= $temp[$j]["distance"]["text"]; 
        $time[$j]= $temp[$j]["duration"]["text"];
        $inter[$j]=decodePolylineToArray($temp[$j]["polyline"]["points"]);
        for($l=0;$l<count($inter);$l++)  //polyline
        {
            $st_lat[$j]= $inter[$j][$l][0];
            $st_lng[$j]= $inter[$j][$l][1];
            $end_lat[$j]= $inter[$j][$l+1][0];
            $end_lng[$j]= $inter[$j][$l+1][1];
       
       
        $sql = "SELECT lati,longi FROM potholes";
        $result = $conn->query($sql);

          while($row = $result->fetch_assoc()) 
          {
              $k=(double)$row["lati"];
              $l=(double)$row["longi"];
              $x1=$st_lat[$j];
              $y1=$st_lng[$j];
              $x2=$end_lat[$j];
              $y2=$end_lng[$j];
              $m=($y2-$y1)/($x2-$x1);
              $y=$m+$y1-($m*$x1);
              $d = abs((((-1)*$m*$k)+$l+($m*$x1)-$y1)/sqrt(pow($m,2)+1));
              if($d<0.00006)
              {
                $count++;
              }
          }
        }
              $dist[$j] = filter_var($dis[$j], FILTER_SANITIZE_NUMBER_FLOAT);
              $tim[$j] = filter_var($time[$j], FILTER_SANITIZE_NUMBER_INT);
              $score[$j]=((float)$dis[$j]*0.5)+((float)$time[$j]*0.8)+($count/(float)$dis[$j]);
              if((0 < $score[$j]) && ($score[$j] <= 10))
                { 
                  $color[$j]="yellow";
                }
                elseif((11 < $score[$j])&& ($score[$j]<=25))
                  {
                    $color[$j]="green";
                  }
                  else
                    {
                      $color[$j]="red";
                    }
      }
  

  /*r( $m=0; $m<4; $m++)
  { $sql1="SELECT Lati,Longi,Status FROM water_level";
        $result1 = $conn->query($sql1);
        echo $result1;
       while($row = $result->fetch_assoc()) 
       {}}*/
  /*$obj->score=$score;
  $obj->color=$color;
  $obj->locations=$locations;
  echo $myobj=json_encode($obj);*/
  //$array=(array)$myobj;
  //echo gettype($array);
$stuff=array($color,$inter);
echo json_encode($stuff);
$conn->close();
?>