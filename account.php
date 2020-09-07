<?php

session_start();
if(isset($_SESSION["name"])){
    $id=$_SESSION["id"];
    $user=$_SESSION["name"];
    require "condb.php";
    $sql="select accountNum,accountName,balance,showb from user_account where userId= $id and sta='0'";
    $result=mysqli_query($link,$sql);
    $result_meau=mysqli_query($link,$sql);
    // echo $sql;
    $row_search=mysqli_fetch_assoc($result);
    $num=$row_search["accountNum"];
    $sql2="select num,inorout,balance,handfee,trandate,handorauto from detail where num='$num'";
    $result2=@mysqli_query($link,$sql2);
    $parsql="select accountName,accountNum,sta,balance,act from user_account where userId=$id and sta!='0' ";
    $result_par=mysqli_query($link,$parsql);
    $result_par_date=mysqli_query($link,$parsql);
    $row_par_date=mysqli_fetch_assoc($result_par_date);

}else{
    header("location: index.php");
    exit();
}

if(isset($_POST["okbtn_out"])){
    $max=9999999999999;
    $cashout=$_POST["cashout_hide"];
    $pwd_out=$_POST["pwd_out"];
    require "condb.php";
    $pass="select passwd,balance,num from users u join user_account ua
    on ua.userId=u.userId where userName = '$user'";
    // echo $pass;
    // echo $cashout;
    $result_pass=mysqli_query($link,$pass);
    $row_pass=mysqli_fetch_assoc($result_pass);
    if($cashout>$max){
        echo "您已違反條例";
        header("refresh:1;url= account.php");
    }else{
        if($row_pass["balance"]>=$cashout){
            if(password_verify($pwd_out,$row_pass["passwd"])){
                $num=$row_pass["num"];
                $time=date("Y-m-d H:i:s");
                echo "交易進行中";
                $out_pass=intval($row_pass["balance"])-intval($cashout);
                $out_true="update user_account set balance=$out_pass where accountName = '$user' ";
                $insert_out="insert into detail values('$num','提款','$cashout',0,'$time','手動')";
                // echo $insert_out;
                $result_insert_out=mysqli_query($link,$insert_out);
                $result_out_true=mysqli_query($link,$out_true);
                header("refresh:1;url= account.php");
            }else{echo "密碼錯誤";}
        }else{echo "餘額不足";}
    }
        
}
if(isset($_POST["okbtn_in"])){
    $max=9999999999999;
    $cashin=$_POST["cashin_hide"];
    $pwd_in=$_POST["pwd_in"];
    require "condb.php";
    $pass_in="select passwd,balance,num from users u join user_account ua
    on ua.userId=u.userId where userName = '$user'";
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
            $num=$row_in_pass["num"];
            $time=date("Y-m-d H:i:s");
            // echo $in_pass;
            $in_true="update user_account set balance=$in_pass where accountName = '$user' ";
            $insert_in="insert into detail values('$num','存款','$cashin',0,'$time','手動')";
            $result_in_true=mysqli_query($link,$in_true);
            $result_insert=mysqli_query($link,$insert_in);
            header("refresh:1;url= account.php");
        }else{echo "密碼錯誤";}
    }else{
        echo "操作有誤,請洽客服";
        header("refresh:1;url= account.php");
    }
}
if(isset($_POST["online"])){
    $id=$_SESSION["id"];
    require "condb.php";
    $parname=$_POST["parname"];
    $apppwd=$_POST["apppwd"];
    $sql_app="select accountNum,accountName,passwd from user_account ua join users u on
    u.userName = ua.accountName where ua.userId= $id and sta='0'";
    // echo $sql_app;
    $result_app=mysqli_query($link,$sql_app);
    $row_app=mysqli_fetch_assoc($result_app);
    $app_num=substr($row_app["accountNum"],0,-1);
    $app_pwd=$row_app["passwd"];
    $sql_count="select Max(sta) from user_account where userId=$id";
    // echo $sql_count;
    $result_count=mysqli_query($link,$sql_count);
    $row_count=mysqli_fetch_assoc($result_count);


    if(password_verify($apppwd,$app_pwd)){
       $count=$row_count["Max(sta)"];
       $count+=1;
       $app_num=strval($app_num).strval($count);
       $app_insert="insert into user_account(userid,accountName,accountNum,sta,act,balance,showb)
       values($id,'$parname',$app_num,$count,'null',0,'1')";
    //    echo $app_insert;
       $result_app_insert=mysqli_query($link,$app_insert);
       header("location: account.php");
    }else{
        echo "wrong";
    }
}

if(isset($_POST["down_long"])){
    // echo "OK";
    $id=$_SESSION["id"];
    $sql_downb="update user_account set showb=0 where userId=$id and sta = '0'";
    $result_downb=mysqli_query($link,$sql_downb);
    header("location: account.php");
}
if(isset($_POST["show_long"])){
    $id=$_SESSION["id"];
    $sql_showb="update user_account set showb=1 where userId=$id and sta = '0'";
    $result_showb=mysqli_query($link,$sql_showb);
    header("location: account.php");
}
if(isset($_POST["datestr"])){
    $sta=$row_par_date["sta"];
    $id=$_SESSION["id"];
    $date=explode("-",$_POST["pardate"]);
    $sql_date="update user_account set act='每個月$date[2]自動撥款' where userId=$id and sta=$sta";
    echo $sql_date;
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
        var testn=0;
        function test(){
            $("#tableSearch").on("keyup", function() {
				var value = $(this).val().toLowerCase();
				$("#myTable tr").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
				});
			});
            $("#cashout").on("keyup",function(){
                $("#cashout_hide").val($("#cashout").val());
            }); 
            $("#fastselect_out").on("change",function(){
                var x=$("#fastselect_out").val();  
                function re(x){
                    var num = x.toString();
                    var pattern= /(-?\d+)(\d{3})/;
                    while(pattern.test(num)){
                        num= num.replace(pattern,"$1,$2");
                    }
                    return num;
                }
                if($("#fastselect_out").val()=="0"){
                    $("#cashout").val("");
                    $("#cashout_hide").val("");
                    $("#cashout").prop("disabled",false);
                }else{
                    $("#cashout").val(re(x));
                    $("#cashout_hide").val($("#fastselect_out").val());
                    $("#cashout").prop("disabled",true);
                    $("#cashout_hide").prop("readonly",true);
                }
            })
            $("#cashin").on("keyup",function(){
                $("#cashin_hide").val($("#cashin").val());
            }); 
            $("#fastselect_in").on("change",function(){
                var y=$("#fastselect_in").val();  
                function re(y){
                    var num_in = y.toString();
                    var pattern= /(-?\d+)(\d{3})/;
                    while(pattern.test(num_in)){
                        num_in= num_in.replace(pattern,"$1,$2");
                    }
                    return num_in;
                }
                if($("#fastselect_in").val()=="0"){
                    $("#cashin").val("");
                    $("#cashin_hide").val("");
                    $("#cashin").prop("disabled",false);
                }else{
                    $("#cashin").val(re(y));
                    $("#cashin_hide").val($("#fastselect_in").val())
                    $("#cashin").prop("disabled",true);
                    $("#cashin_hide").prop("readonly",true);
                }
            })
            $("#show").on("click",function(){
                testn++;
                if(testn%2 ==1){
                    $("#hide").val($("#real").val());
                    $("#show").val("hide");
                }else{
                    $("#hide").val("*****");
                    $("#show").val("show");
                }
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
        <form method="post">
        <table class="table tabel-striped">
            <thead>
                <tr>
                    <th>編號</th>
                    <th>主帳戶</th>
                    <th>餘額</th>
                    <th>長期顯示餘額</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row=mysqli_fetch_assoc($result_meau)){?>
                <tr>
                    <th><?=$row["accountNum"]?></th>
                    <th><?=$row["accountName"]?><input type="hidden" id="real" value="<?=$row["balance"]?>"></th>
                    <?php if ($row["showb"]==0){?>
                        <th><input class="hidden" id="hide" value="*****"><input type="button" class="hidden" id="show" value="show"></th>
                    <?php }else{ ?>
                        <th><input class="hidden" id="hide" value="<?=$row["balance"]?>"><input type="button" class="hidden" id="show" value="show"></th>
                    <?php }?>
                    <th><input type="submit" name="show_long" class="btn btn-outline-warning btn-sm" value="是">|<input class="btn btn-outline-dark btn-sm" name="down_long" type="submit" value="否"></th>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        </form>
        <table class="table tabel-striped">
            <thead>
                <tr>
                <span style="float:right"><button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal">申請子帳號</button></span>
                    <th>編號</th>
                    <th>子帳戶</th>
                    <th>狀態</th>
                    <th>餘額</th>
                    <th>功能</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row_par=mysqli_fetch_assoc($result_par)){?>
                <tr>
                    <td><?=$row_par["accountNum"]?></td>
                    <td><?=$row_par["accountName"]?></td>
                    <td><?=$row_par["act"]?></td>
                    <td><?=$row_par["balance"]?></td>
                    <td>
                    <input type="submit"  class="btn btn-outline-success btn-sm"data-toggle="modal" data-target="#Modal2" value="每個月自動撥款">
                    |
                    <input type="submit"  class="btn btn-outline-danger btn-sm" value="取消自動">
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        </p>
        </div>
        <div id="tab2" class="container tab-pane fade">
        <p>
        <form method="post" >
        <input type="hidden" id="cashout_hide" name="cashout_hide" value="">
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
            <input type="hidden" id="cashin_hide" name="cashin_hide" value="">
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
        <p>
            <table class="table tabel-striped">
            <input class="form-control mb-4" id="tableSearch" type="text"placeholder="輸入關鍵字查詢">
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
                <tbody id="myTable">
                <?php while($row2=mysqli_fetch_assoc($result2)){?>
                    <tr>
                        <th><?=$row2["num"]?></th>
                        <th><?=$row2["inorout"]?></th>
                        <th><?=$row2["balance"]?></th>
                        <th><?=$row2["handfee"]?></th>
                        <th><?=$row2["trandate"]?></th>
                        <th><?=$row2["handorauto"]?></th>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </p>
        </div>       
    </div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">子帳號申請</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post">
            <div class="form-group">
                <label for="app">申請項目</label>
                <input type="text" class="form-control" id="app" name="app" disabled="disabled" placeholder="加開子帳號">
            </div>
            <div class="form-group">
                <label for="parname">子帳戶名稱</label>
                <input type="text" class="form-control" id="parname" name="parname" placeholder="username">
            </div>
            <div class="form-group">
                <label for="apppwd">安全機制--請輸入使用者密碼</label>
                <input type="password" class="form-control" id="apppwd" name="apppwd" placeholder="password">
            </div>
      </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="online" name="online">線上申請</button>
        </div>
        </form>
    </div>
  </div>
</div>
<div class="modal fade" id="Modal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">自動轉款</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post">
            <div class="form-group">
                <label for="parname">子帳戶名稱</label>
                <input type="text" class="form-control" id="parname" name="parname" value="<?=$row_par_date["accountName"]?>">
            </div>
            <div class="form-group">
                <label for="pardate">輸入日期</label>
                <input type="date" class="form-control" id="pardate" name="pardate" placeholder="username">
            </div>
            <div class="form-group">
                <label for="datepwd">安全機制--請輸入使用者密碼</label>
                <input type="password" class="form-control" id="datepwd" name="datepwd" placeholder="password">
            </div>
      </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="datestr" name="datestr">開始使用</button>
        </div>
        </form>
    </div>
  </div>
</div>
</body>
</html>