<?php
$id=$_GET['id'];
require("condb.php");

$sql="select userName from users where userName='$id'";
$result=mysqli_query($link,$sql);
$row=mysqli_fetch_assoc($result);

if(isset($row["userName"])){
    echo 1;
}else if(!isset($row["userName"])&& $id!=" "){
    echo 2;
}else {
    echo 0;
}

?>