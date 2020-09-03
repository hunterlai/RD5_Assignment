<?php
require "condb.php";
// $out=$_GET["logout"];
if(isset($_GET["logout"])){
    session_start();
    session_unset();
    session_destroy();
    header("location: index.php");
    exit();
}
if(isset($_POST["okbtn"])){
    $password=$_POST["npassword"];
    $email=$_POST["email"];
    $userName=$_POST["nusername"];
    $phone=$_POST["phone"];
    $birth=$_POST["birth"];
    $options=[
        'cost' =>12
    ];
    $hash = password_hash($password, PASSWORD_DEFAULT,$options);
    $sql="insert into users (email,userName,birth,phone,passwd)
    values ('$email','$userName',$birth,'$phone','$hash')";
    // echo $sql;
    $result=mysqli_query($link,$sql);

}
if(isset($_POST["homebtn"])){
    $suser=$_POST["username"];
    $upassword=$_POST["password"];
    $auth="select passwd from users where userName= '$suser' or  email='$suser' ";
    // echo $auth;
    $result_auth=mysqli_query($link,$auth);
    $row=mysqli_fetch_assoc($result_auth);
    if(password_verify($upassword,$row["passwd"])){
        session_start();
        $_SESSION["name"]=$suser;
        // echo "right";
        header("location: account.php");
    }else{
        echo "wrong";
    }
    
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>online Bank!</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>

    <div class="container">
    <h2>Bank</h2>
    
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="#tab1" data-toggle="tab" class="nav-link active">登入</a>
        </li>
        <li class="nav-item">
            <a href="#tab2" data-toggle="tab" class="nav-link ">註冊</a>
        </li>
    </ul>
    <div class="tab-content ">
        <div id="tab1" class="container tab-pane active">
        <p>
        <form method="post">
        <div class="form-group">
            <label for="username">user</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="username or email">

        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="password">
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="rember" name="rember">
            <label class="form-check-label" for="remeber">rember me</label>
        </div>
        <button type="submit" class="btn btn-primary" name="homebtn">Submit</button>
        </form>
        </p>
        </div>
        <div id="tab2" class="container tab-pane fade">
        <p>
        <form method="post">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="examp@exampmail.com">
            </div>
            <div class="form-group col-md-6">
                <label for="nusername">user</label>
                <input type="text" class="form-control" id="nusername" name="nusername" placeholder="ur big name">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="birth">birthday</label>
                <input type="date" class="form-control" id="birth" name="birth" >
            </div>
            <div class="form-group col-md-6">
                <label for="phone">phone</label>
                <input type="text" class="form-control" id="phone" name="phone" placeholder="0912345678">
            </div>
        </div>
        <div class="form-row">
        <div class="form-group col-md-11">
            <label for="npassword">password</label>
            <input type="password" class="form-control" id="npassword" name="npassword" placeholder="password"/>
        </div>
        <div class="form-group col-md-1">
            <label for="eye"></label>
        </div>
        </div>
        <div class="form-group">
            <label for="checkpd">check password</label>
            <input type="password" class="form-control" id="checkpd" name="checkpd" placeholder="put password again">
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="agreed">
            <label class="form-check-label" for="agreed">agreed rule</label>
            <a href="rule">rule list</a>
        </div>
        <button type="submit" class="btn btn-primary" name="okbtn">送出</button>
        </form>
        </p>
        </div>
    </div>
</body>
</html>