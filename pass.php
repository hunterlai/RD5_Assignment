<?php
$pwd=$_GET['password'];
$id=$_GET['id'];
require "condb.php";
$sql="select passwd from users where userId=$id";
$result=mysqli_query($link,$sql);
$row=mysqli_fetch_assoc($result);

if(password_verify($pwd,$row["passwd"])){
    echo json_encode(array('status'=>'pass'));
    exit();
}else{
    echo json_encode(array('status'=>'fail'));
    exit();
}

?>