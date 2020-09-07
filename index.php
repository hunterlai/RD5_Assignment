<?php
require "condb.php";
if(isset($_GET["logout"])){
    session_start();
    session_unset();
    session_destroy();
    header("location: index.php");
    exit();
}

if(isset($_POST["okbtn"])){
    $num=rand(100000000,999999999);
    $first_num=strval($num).strval(0);
    $password=$_POST["npassword"];
    $checkpd=$_POST["checkpd"];
    $birth=$_POST["birth"];
    $email=$_POST["email"];
    $userName=$_POST["nusername"];
    $phone=$_POST["phone"];
    if(trim($userName)!="" && trim($password)!="" && trim($email)!="" && trim($phone)!=""){
        if($checkpd==$password){
            $options=[
                'cost' =>12
            ];
            $hash = password_hash($password, PASSWORD_DEFAULT,$options);
            $sql="insert into users (email,userName,phone,passwd,num,birth)
            values ('$email','$userName','$phone','$hash','$first_num','$birth')";
            // echo $sql;
            $result=mysqli_query($link,$sql);
            $search="select userId,num from users where userName='$userName'";
            $result_new=@mysqli_query($link,$search);
            $row_user=@mysqli_fetch_assoc($result_new);
            $newId=$row_user["userId"];
            $newnum=$row_user["num"];
            $sql2="insert into user_account (userId,accountName,accountNum,sta,balance,showb)
            values ($newId,'$userName','$newnum','0',1000,1)";
            // echo $sql2;
            $result_account=mysqli_query($link,$sql2);
            echo '<script>
                alert("註冊成功");
            </script>';
            header("location:index.php");
        }else{
            echo '<script>
                    alert("密碼與確認密碼不相同");
                </script>';
        }    
    }else{
        echo '<script>
                alert("尚有資料未填寫");
            </script>';
    }
    
}

if(isset($_POST["homebtn"])){
    $suser=$_POST["username"];
    $upassword=$_POST["password"];
    $auth="select passwd,userId from users where userName= '$suser' or  email='$suser' ";
    // echo $auth;
    $result_auth=mysqli_query($link,$auth);
    $row=mysqli_fetch_assoc($result_auth);
    $id=$row["userId"];
    if(trim($suser)!="" && trim($upassword)!=""){
        if(password_verify($upassword,$row["passwd"])){
            session_start();
            $_SESSION["name"]=$suser;
            $_SESSION["id"]=$id;
            // echo "right";
            header("location: account.php");
        }else{
            echo '<script>
                alert("密碼錯誤");
            </script>';
        }
    }else{
        echo '<script>
                alert("帳號或密碼尚未填寫");
            </script>';
    }
    
}
session_start();
while(($authnum=rand()%100000)<10000);
$_SESSION['authnum']=$authnum;


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
    <script type="text/javascript" src="./jquery.min.js"></script>
    <script type="text/javascript" >
        $(document).ready(test);
        function test(){

            $("#auth").on("click",
            function(){ 
                if($("#authinput").val()==<?php echo $authnum;?>){
                    $("#YorN").val("驗證碼正確！");
                    $("#homebtn").prop("disabled",false);
                }else{
                    $("#YorN").val("驗證碼錯誤！");
                }
            });

            $("#nusername").on("keyup",function(){
                var t=$("#nusername").val();
                $.getJSON("same.php",{id:t},function(ans){
                    if(ans==1){
                        $("#nuser").val("名稱已被使用");
                        $("#nuser").css("color","red");
                        $("#okbtn").prop("disabled",true);
                        $("#sure").prop("disabled",true);
                    }else if(ans==2){
                        $("#nuser").val("名稱尚未被使用");
                        $("#nuser").css("color","green");
                        $("#sure").prop("disabled",false);
                    }else{
                        $("#nuser").val("請輸入值");
                        $("#nuser").css("color","blue");
                    }  
                });    
            });

            $("#email").on("keyup",function(){
                $("#nemail").val("");
                $("#okbtn").prop("disabled",true);
            });
            $("#phone").on("keyup",function(){
                $("#nphone").val("");
                $("#okbtn").prop("disabled",true);
            });
            $("#birth").on("change",function(){
                $("#nbirth").val("");
                $("#okbtn").prop("disabled",true);
            });
            $("#npassword").on("keyup",function(){
                $("#npwd").val("");
                $("#okbtn").prop("disabled",true);
            });
            $("#checkpd").on("keyup",function(){
                $("#okbtn").prop("disabled",true);
                if($("#checkpd").val()!=$("#npassword").val()){
                    $("#nckpwd").val("與密碼不符");
                    $("#nckpwd").css("color","red");
                    $("#sure").prop("disabled",true);
                    $("#okbtn").prop("disabled",true);
                }else{
                    $("#nckpwd").val("與密碼相同");
                    $("#nckpwd").css("color","green");
                    $("#sure").prop("disabled",false);
                }
            });
            $("#agreed").on("click",function(){
                if($("#agreed").prop('checked')==true){
                    $("#ckbox").val("");
                }else{
                    $("#okbtn").prop("disabled",true);
                }
            })
            $("#sure").on("click",function(){
                let x=$("#email").val();
                let y=$("#phone").val();
                let z=$("#nusername").val();
                let a=$("#npassword").val();
                if(x==""){
                    $("#nemail").val("尚未填寫");
                    $("#nemail").css("color","red");
                }
                if(y==""){
                    $("#nphone").val("尚未填寫");
                    $("#nphone").css("color","red");
                }
                if(z==""){
                    $("#nuser").val("尚未填寫");
                    $("#nuser").css("color","red");
                }
                if(a==""){
                    $("#npwd").val("尚未填寫");
                    $("#npwd").css("color","red");
                }
                if($("#agreed").prop('checked')!=true){
                    $("#ckbox").val("尚未勾選");
                    $("#ckbox").css("color","red");
                }
                if(x!="" && y!="" && z!="" && a!="" && $("#agreed").prop("checked")==true && $("#nuser").val()!='名稱已被使用'){
                    $("#okbtn").prop("disabled",false);
                }
                
            });
        }
        
    </script>
    <style>
    .input_text{
        border:0; 
        background-color:white;
    }
    </style>

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
            <input type="text" class="form-control" id="username" name="username" placeholder="username">

        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="password">
        </div>
            <table>
                <img src="authimg.php?authunm=<?echo $authnum?>"></img>
                輸入驗證碼：<input type="text" name="authinput" id="authinput">
                <input type="button" name="auth" id="auth" value="提交"><br>
                <input type="text" style="border:0;" id="YorN" disabled="disabled">
                
            </table>
        <button type="submit" class="btn btn-primary" disabled="disabled" name="homebtn" id="homebtn">登入</button>
        </form>
        </p>
        
        </div>
        <div id="tab2" class="container tab-pane fade">
        <p>
        <form method="post">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="email">Email<input type="text"  id="nemail" disabled="disabled" class="input_text"></label>
                <input type="email" class="form-control" pattern="\w+([.-]\w+)*@\w+([.-]\w+)+" id="email" name="email" placeholder="emaple@ex.com" value="">
            </div>
            <div class="form-group col-md-6">
                <label for="phone">phone<input type="text"  id="nphone" disabled="disabled" class="input_text"></label>
                <input type="text" class="form-control" pattern="\d{10}" id="phone" name="phone" placeholder="0912345678" value="">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="nusername">user<input type="text"  id="nuser" disabled="disabled" class="input_text"></label>
                <input type="text" class="form-control" pattern="\w+" title="p.s開頭請輸入英文或數字" id="nusername" name="nusername" placeholder="ur big name">
                
            </div>
            <div class="form-group col-md-6">
                <label for="birth">birth<input type="text"  id="nbirth" disabled="disabled" class="input_text"></label>
                <input type="date" class="form-control" id="birth" name="birth" value="<?php echo date("Y-m-d");?>" min="<?php echo date("Y-m-d",strtotime("-100 year"));?>" max="<?php echo date("Y-m-d");?>"  >
            </div>
        </div>
        <div class="form-row">
        <div class="form-group col-md-6">
            <label for="npassword">password<input type="text"  id="npwd" disabled="disabled" class="input_text"></label>
            <input type="password" class="form-control" id="npassword" name="npassword" placeholder="password"/>
        </div>
        </div>
        <div class="form-row">
        <div class="form-group col-md-6">
            <label for="checkpd">check password<input type="text"  id="nckpwd" disabled="disabled" class="input_text"></label>
            <input type="password" class="form-control" id="checkpd" name="checkpd" placeholder="put password again">
        </div>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="agreed">
            <label class="form-check-label" for="agreed">agreed rule</label>
            <a href="rule">rule list</a><input type="text"  id="ckbox" disabled="disabled" class="input_text" >
        </div>
        <button type="button" class="btn btn-primary" name="sure" id="sure" >確認</button>
        <button type="submit" class="btn btn-primary" name="okbtn" id="okbtn" disabled="disabled">送出</button>
        </form>
        </p>
        </div>
    </div>
</body>
</html>