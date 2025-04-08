<?php
    session_start();
    ob_start();
    if(isset($_SESSION['s_user'])&&(is_array($_SESSION['s_user']))&&(count($_SESSION['s_user'])>0)){
        $admin=$_SESSION['s_user'];
    }else{
        header('location: login.php');
    }
?>
<h1>Bạn đang đăng nhập vào trang Admin: <?=$admin["username"]?> </h1>