<?php
$id=$_GET['id'];
require("condb.php");

$sql="select userName from users where userName='$id'";
$result=mysqli_query($link,$sql);
$row=mysqli_fetch_assoc($result);

if($row["userName"]==$id){
    echo 1;
}else{
    echo 0;
}
?>