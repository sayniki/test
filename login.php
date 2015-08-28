<?php
include 'page_header.php';


if(!empty($_POST['sub_btn']))
{
    $username=$_POST['username'];
    $password=$_POST['password'];
    print_r($_POST);
    $select="select id from user_file where user_name='".addslashes($username)."' and password='".addslashes($password)."' limit 1";
  //echo "<br>".$select;
  // $select="select * from user_file";
    $result = $conn->query($select);
    if ($result->num_rows > 0) {
    {
        $_SESSION['uname']=$username;
        echo "<script>
        document.getElementById('login_form').action = 'main_page.php';
        //document.login_form.submit();</script>";
       // echo "<script>window.location.assign('main_page.php')</script>";
    }
} else {
    //print_r($conn);
    //echo "Error: " . $select . "<br>=" . $conn->error;
}

    
}
if(!empty($_SESSION['uname']))
{
    echo "<script>window.location.assign('main_page.php')</script>";
}

?>
<form id='login_form' name='login_form' method=post>
<div class='container'>
<br><br><br><h2>Log In</h2>
<table>
    <tr>
        <th style='text-align:left'>Username</th>
    </tr>
    <tr>
        <td><input type='text' name='username' id='username'></td>
    </tr>
    <tr>
        <th style='text-align:left'>Password</th>
    </tr>
    <tr>
        <td><input type='password' name='password' id='password'></td>
    </tr>
    <!--<tr>
        <td><input type='checkbox' id='remember' name='remember'> Remember me</td>
    </tr>-->
    <tr>
        <td><input  NAME='sub_btn' type='submit' value='Log in'></td>
    </tr>
    
</table>
</div>
</form>