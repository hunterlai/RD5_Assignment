<?php

session_start();
if(isset($_SESSION["name"])){
    $sta=$_GET["sta"];
    $id=$_SESSION["id"];
    $user=$_SESSION["name"];
    require "condb.php";
    $sql="select accountNum,accountName,balance,showb,act,passwd from user_account ua 
    join users u on u.userId=ua.userId where u.userId= $id and sta='$sta'";
    // echo $sql;
    $result=mysqli_query($link,$sql);
    $row=mysqli_fetch_assoc($result);
}else{
    header("location: index.php");
    exit();
}

// if(isset($_POST["day"])){
//     echo "OK";
//     $date=explode("-",$_POST["pardate"]);
//     $sql_date="update user_account set act='每個月$date[2]自動撥款' where userId=$id and sta=$sta";
//     echo $sql_date;
// }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set bank</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="./jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(test);
        var testn=0;
        function test(){
            $("#cash").on("keyup",function(){
                if($("#cash").val()>10000){
                    $("#cash").val(10000);
                }
            })
            $("#month").on("keyup",function(){
                if($("#month").val()>12){
                    $("#month").val(12);
                }
            })
            $("#day").on("keyup",function(){
                if($("#day").val()>=31){
                    $("#day").val(31);
                    $("#show").css("display","inline");
                    $("#show").css("color","red");
                    $("#toast").val("該月有31號時,才會自動轉帳");
                    $("#toast").css("color","red");
                }else{
                    $("#toast").val("");
                    $("#show").css("display","none");
                }
            })
            $("#user").on("keyup",function(){
                $("#user").prop("disabled",true);
            })
            $("#monthbtn").on("click",function(){
                var sta=<?=$sta?>;
                var pwd=$("#password").val();
                var id=<?=$id?>;
                var cash=$("#cash").val();
                var month=$("#month").val();
                var day=$("#day").val();

                $.getJSON("pass.php",{password:pwd,id:id},function(result){
                    if(result.status!='pass'){
                        $("#result").val("密碼錯誤");
                        $("#result").css("color","red");
                    }else{
                        $("#result").val("");
                        $.getJSON("cashmonth.php",{cash:cash,month:month,id:id,day:day,sta:sta},function(ans){
                            if(ans.status=='success'){
                                $("#result").val("交易成功");
                                // $.getJSON("tranfcash.php",{day:day,id:id},function(){
                                // });
                            }else if(ans.status=='fail'){
                                if(ans.only==0){
                                    $("#result").val("餘額不足");
                                    $("#result").css("color","red");
                                }else{
                                    $("#result").val("總餘額不足,只能轉"+ans.only+"個月");
                                    $("#result").css("color","red");
                                }
                            }else{
                                $("#result").val("沒有資料");
                            }
                        });
                    }
                });
            })
        }
    </script>
    <style>
    .hidden{
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
    <h2>Bank-會員設定 
    <span style="float:right;"><a href="index.php?logout=1" type="button" class="btn btn-danger">登出</a></span>
    <span style="float:right; margin-right:5px;"><a href="account.php" type="button" class="btn btn-info">返回主畫面</a></span>    
    </h2>
    
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="#tab1" data-toggle="tab" class="nav-link active">設定</a>
        </li>

    </ul>
    <div class="tab-content ">
        <div id="tab1" class="container tab-pane active">
        <p>
        <div class="form-group col-md-6">
            <label for="user">使用者</label>
            <div class="row">
                <div class="form-group col-md-6">
                    <input type="text" class="form-control"  disabled="disabled" value="編號:<?=$row["accountNum"]?>&nbsp;" >
                    <input type="text" class="form-control"  disabled="disabled" value="名稱:<?=$row["accountName"]?>" >
                </div>
            </div>
        </div>
        <form method="post" >
        <div class="form-group col-md-4">
        <p>
            <label for="cash">每個月轉款金額(上限1萬)</label>
            <input type="number" class="form-control" value="0" step="100" id="cash" name="cash" min="0" max="10000" pattern="\d+" title="請輸入阿拉伯數字">
        </p>
        </div>

        <div class="form-group col-md-4">
                <label for="month">設定幾個月(上限12個月)</label>
                <input type="number" class="form-control" id="month" name="month" value="0" min="0" max="12" pattern="\d+" title="請輸入阿拉伯數字">
                <br>
                <label for="day" style="width:400px;">每個月幾號<svg id="show" style="margin-bottom:3px;" display="none" width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-exclamation-circle-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                      </svg><input type="text" id="toast"  disabled="disabled" style="width:250px; border:0; background:white;"></label>
                <input type="number" class="form-control" id="day" name="day" value="0"  min="0" max="31">
        </div>

        <div class="form-group col-md-4">
            <label for="password" style="width:400px;">使用者密碼<input type="text" id="result" disabled="disabled" style="width:300px; border:0; background:white;"></label>
            <input type="password" class="form-control" id="password" name="password" placeholder="password"/>
            <br>
            <a href="account.php" class="btn btn-secondary" >返回</a>
            <button type="button" class="btn btn-primary" id="monthbtn" name="monthbtn">送出</button>
            
        </div>
        </form>
        </p>
        </div>
        
             
    </div>


</body>
</html>