<?php
$cash=$_GET['cash'];
$month=$_GET['month'];
$id=$_GET['id'];
$day=$_GET['day'];
$sta=$_GET['sta'];
require "condb.php";

$sql="select balance from user_account where userId=$id and sta='0' ";
$result=mysqli_query($link,$sql);
$row=mysqli_fetch_assoc($result);

$total=$cash*$month;

if(isset($row["balance"])){
    $balance=$row["balance"];
    if($balance>$total){
        $update="update user_account set act='每個月:$day\日 轉帳:$cash\元 維持$month\個月' where userId=$id and sta='$sta'";
        $result2=mysqli_query($link,$update);
        echo json_encode(array('status'=>'success'));
    }else{
        $fin=$balance/$cash;
        echo json_encode(array('status'=>'fail','only'=>round($fin)));
    }
}else{
    echo json_encode(array('status'=>'none'));
}

?>