<?php
require "Database.php";
try{
    $tt = "aaaa";
    $conn->prepare("INSERT INTO Test (Name) VALUES ('$tt')")->execute();

}catch(Exception $ex){
 echo $ex;
}

?>
