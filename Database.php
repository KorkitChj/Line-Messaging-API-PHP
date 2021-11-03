<?php 
date_default_timezone_set('asia/bangkok');
try {
  $conn = new PDO("sqlsrv:Server=DESKTOP-H5I9I6I\SQLEXPRESS;Database=Straumann", "testapi", "P@ssw0rd");
}catch(Exception $e) {
  exit('Unable to connect to database.' + $e);
}

?>
