<html style='font-size: 10px;-webkit-tap-highlight-color: transparent;'>
<link rel="stylesheet" type="text/css" href="menuCss.css">
<body>
    <style>
        .navbar-brand {
    float: left;
    padding: 15px 15px;
    font-size: 18px;
    line-height: 20px;
    height: 50px;
}
body {
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    font-size: 14px;
    line-height: 1.428571429;
    color: #333333;
    background-color: #fff;
}
div{display:block;}
.container {
    margin-right: auto;
    margin-left: auto;
    padding-left: 15px;
    padding-right: 15px;
    width: 970px;
}
.navbar-toggle {
    position: relative;
    float: right;
    margin-right: 15px;
    padding: 9px 10px;
    margin-top: 8px;
    margin-bottom: 8px;
    background-color: transparent;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;
}
.navbar-toggle .icon-bar {
    margin:4px;
    border-radius: 1px;
    
    background-color: #888;border:1px solid #ddd;display:block;width:22px;height:1px;
}

h2 {
    display: block;
    font-size: 1.5em;
    -webkit-margin-before: 0.83em;
    -webkit-margin-after: 0.83em;
    -webkit-margin-start: 0px;
    -webkit-margin-end: 0px;
    font-weight: bold;
}
    </style>
<div style='display:block;background-color: #f8f8f8;border-color: #e7e7e7;width:100%;height:51px;position:fixed;top:0px;left:0px'>
<?php
session_start();
include 'connect.php';
include 'functions.php';
$str_request=str_replace("/finance_gsm","",$_SERVER["REQUEST_URI"]);
$str_request=str_replace("/","",$str_request);
$str_request=str_replace("/","",$str_request);
if ("login.php"== $str_request && empty($_SESSION['uname']))
echo "";
else if("login.php"!= $str_request && empty($_SESSION['uname']))
 echo "<script>alert(' Session Expired Please Log In');window.location.assign('login.php')</script>";
?>
<script >
    var xmlhttp;
    function loadXMLDoc(url,cfunc)
    {
       if (window.XMLHttpRequest)
         xmlhttp=new XMLHttpRequest();
       else
         xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
            cfunc(xmlhttp.responseText);
    }
    xmlhttp.open("GET",url,true);
    xmlhttp.send();
    }
   
</script>

    <div class='container'>
        <div style='display:block'>
            <div style='top:18px;left:230px;position:fixed'>
                <img id="logo" src="assets/sysgen-5d40db44871cdcc325594e2c19bda4c7.png" alt="Sysgen" width="86" height="20">
            </div>
            <?php
            if(!empty($_SESSION['uname']))
                {
                    ?>
            <div style='top:0px;left:180px;position:relative'>
               
                <nav class="primary_nav_wrap">
                <ul >
               <?php
                        include 'menu.php';
                   // echo "<li class='current-menu-item'>".$_SESSION['uname']."</li>";
                   // echo "<h3>".$_SESSION['uname']."</h3>";
                    echo "<li class='current-menu-item'><a href='logout.php'>Log Out</a></li>";
                  // echo "<h4><a href=''>Log Out</a></h4>        ";
                ?>
                </ul>
           </nav>
            </div>
            <?php
            }
                ?>    
            </div> 
            
    </div>
</div>
<div style='height:51px'><br></div>
<div class='container'>
