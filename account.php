<?php
session_start();
if(isset($_SESSION["name"])){
    $user=$_SESSION["name"];
}else{
    header("location: index.php");
    exit();
}

if(isset($_POST["okbtn_out"])){
    $cashout=$_POST["cashout"];
    $pwd_out=$_POST["pwd_out"];
    require "condb.php";
    $pass="select passwd,balance from users where userName = '$user'";
    // echo $pass;
    // echo $cashout;
    $result_pass=mysqli_query($link,$pass);
    $row_pass=mysqli_fetch_assoc($result_pass);
    if($row_pass["balance"]>=$cashout){
        if(password_verify($pwd_out,$row_pass["passwd"])){
            echo "交易進行中";
            $out_pass=$row_pass["balance"]-$cashout;
            $out_true="update users set balance=$out_pass where userName = '$user' ";
            $result_out_true=mysqli_query($link,$out_true);
            header("refresh:2;url= account.php");
        }else{echo "密碼錯誤";}
    }else{echo "餘額不足";}
}
if(isset($_POST["okbtn_in"])){
    $max=9999999999999;
    $cashin=$_POST["cashin"];
    $pwd_in=$_POST["pwd_in"];
    require "condb.php";
    $pass_in="select passwd,balance from users where userName = '$user'";
    // echo $pass_in;
    // echo $cashout;
    // echo $max,$cashin;
    $result_pass=mysqli_query($link,$pass_in);
    $row_in_pass=mysqli_fetch_assoc($result_pass);
    // echo $row_in_pass["balance"],$cashin."<br>".$max;
    if($row_in_pass["balance"]+$cashin <= $max){
        if(password_verify($pwd_in,$row_in_pass["passwd"])){
            echo "交易進行中";
            $in_pass=$row_in_pass["balance"]+$cashin;
            // echo $in_pass;
            $in_true="update users set balance=$in_pass where userName = '$user' ";
            $result_in_true=mysqli_query($link,$in_true);
            header("refresh:2;url= account.php");
        }else{echo "密碼錯誤";}
    }else{
        echo "操作有誤,請洽客服";
        header("refresh:2;url= account.php");
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
    <script type="text/javascript" src="./jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(test);
        function test(){
            $("#fastselect_out").on("change",function(){
                if($("#fastselect_out").val()=="0"){
                    $("#cashout").val("");
                    $("#cashout").prop("readonly",false);
                }else{
                    $("#cashout").val($("#fastselect_out").val());
                    $("#cashout").prop("readonly",true);
                }
            })
            $("#fastselect_in").on("change",function(){
                if($("#fastselect_in").val()=="0"){
                    $("#cashin").val("");
                    $("#cashin").prop("readonly",false);
                }else{
                    $("#cashin").val($("#fastselect_in").val());
                    $("#cashin").prop("readonly",true);
                }
            })
        }


    </script>
</head>
<body>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>

    <div class="container">
    <h2>Bank <span style="float:right"><a href="index.php?logout=1" type="button" class="btn btn-danger">登出</a></span>
    </h2>
    
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="#tab1" data-toggle="tab" class="nav-link active">會員:<?=$user?></a>
        </li>
        <li class="nav-item">
            <a href="#tab2" data-toggle="tab" class="nav-link ">提款</a>
        </li>
        <li class="nav-item">
            <a href="#tab3" data-toggle="tab" class="nav-link ">存款</a>
        </li>
        <li class="nav-item">
            <a href="#tab4" data-toggle="tab" class="nav-link ">查詢明細</a>
        </li>
    </ul>
    <div class="tab-content ">
        <div id="tab1" class="container tab-pane active">
        <p><span style="float:right"><a href="index.php" type="button" class="badge badge-info">申請帳戶</a></span>
        
        <table class="table tabel-striped">
            <thead>
                <tr>
                    <th>帳戶</th>
                    <th>信箱</th>
                    <th>手機</th>
                    <th>餘額</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                </tr>
            </tbody>
        </table>        
        </p>
        </div>
        <div id="tab2" class="container tab-pane fade">
        <p>
        <form method="post" >
        <label for="select">一般輸入/快速選擇</label>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="cashout">提款金額</label>
                <input type="text" class="form-control" id="cashout" name="cashout" value="">
            </div>
            <div class="form-group col-md-3">
                <label for="fastselect_out">快速選擇</label>
                <select class="custom-select" id="fastselect_out" name="fastselect_out"  >
                    <option selected disabled="disabled">金額</option>
                    <option value="100">100</option>
                    <option value="500">500</option>
                    <option value="1000">1000</option>
                    <option value="2000">2000</option>
                    <option value="3000">3000</option>
                    <option value="5000">5000</option>
                    <option value="0">取消</option>
                </select>
            </div>
        </div>
        <div class="form-row">
        <div class="form-group col-md-6">
            <label for="pwd_out">password</label>
            <input type="password" class="form-control" id="pwd_out" name="pwd_out" placeholder="password"/>
        </div>
        </div>
        <button type="submit" class="btn btn-primary" name="okbtn_out">送出</button>
        </form>
        </p>
        </div>
        <div id="tab3" class="container tab-pane fade">
        <p>
            <form method="post">
            <label for="select">一般輸入/快速選擇</label>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="cashin">存款金額</label>
                    <input type="text" class="form-control" id="cashin" name="cashin" >
                </div>
                <div class="form-group col-md-3">
                    <label for="fastselect_in">快速選擇</label>
                    <select class="custom-select" id="fastselect_in">
                        <option selected disabled="disabled">金額</option>
                        <option value="100">100</option>
                        <option value="500">500</option>
                        <option value="1000">1000</option>
                        <option value="2000">2000</option>
                        <option value="3000">3000</option>
                        <option value="5000">5000</option>
                        <option value="0">取消</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
            <div class="form-group col-md-6">
                <label for="pwd_in">password</label>
                <input type="password" class="form-control" id="pwd_in" name="pwd_in" placeholder="password"/>
            </div>
            </div>
            <button type="submit" class="btn btn-primary" name="okbtn_in">送出</button>
            </form>
        </p>
        </div>
        <div id="tab4" class="container tab-pane fade">
        <p>test
            <table class="table tabel-striped">
                <thead>
                    <tr>
                        <th>編號</th>
                        <th>存/提</th>
                        <th>金額</th>
                        <th>手續費</th>
                        <th>交易時間</th>
                        <th>交易方式</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>0</th>
                        <th>4</th>
                        <th>ATM/銀行分行</th>
                    </tr>
                </tbody>
            </table>
        </p>
        </div>       
    </div>
</body>
</html>