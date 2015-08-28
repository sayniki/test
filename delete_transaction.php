<?php
session_start();
include 'connect.php';
?>
<script>
    function go() {
    
    document.getElementById('form1').action = 'view_data.php';
    document.form1.submit();
    }
</script>
<?php
$trans_num=$_REQUEST['trans_num'];
$page_type=$_REQUEST['page_type'];
$remarks=$_REQUEST['reason'];
$status=$_REQUEST['status'];

$insert="insert into  po_remarks_file (remarks,status,date_created,rejected_by,trans_no) values
('".addslashes($remarks)."','$status',now(),'".$_SESSION['uname']."','".$trans_num."')";
 $conn->query($insert);
 echo $insert;
$update="update po_file set mas_status=0 where trans_no='$trans_num' limit 1";
 $conn->query($update);
echo "
<form id='form1' name='form1' method=post>
<input type='hidden' id='type' name='type' value='$page_type'>
</form>
<script>go()</script>";

?>