<?php
session_start();
include 'connect.php';
if($_REQUEST['xstatus']=='getRequestor')
{
    $requestor=$_REQUEST['requestor'];
    $engineer=array();
   $select="select account_id , concat(first_name,' ',last_name) as engineer from master_address_file
    where mas_status=1 and account_type='engineer' and account_executive_id='$requestor' order by engineer";
    $result = $conn->query($select);
    echo "<option>Choose</option>";
    while($row=$result->fetch_assoc())
        echo "<option value='".$row['account_id']."'>".$row['engineer']."</option>";
    echo "~";
    $secretary=array();
    $select="select account_id , concat(first_name,' ',last_name) as secretary from master_address_file
    where mas_status=1 and account_type='secretary' and account_executive_id='$requestor' order by secretary";
    $result = $conn->query($select);
    echo "<option>Choose</option>";
    while($row=$result->fetch_assoc())
        echo "<option value='".$row['account_id']."'>".$row['secretary']."</option>";
}
if($_REQUEST['xstatus']=='readyForPickUp')
{
    $trans_num=$_REQUEST['trans_num'];
    $status=$_REQUEST['status'];
    $trans_num=$_REQUEST['trans_num'];
    $name=$_REQUEST['name'];
    $cv=$_REQUEST['cv'];
    $title=$_REQUEST['title'];
    if($status=='Ready for pick up')
    $new_status="Receive Cash Request";
    $insert="insert into po_check_file (`trans_no`,`name`,`title`,`cv`,`date_created`,`created_by`) value
    ('$trans_num','".addslashes($name)."','".addslashes($title)."','".addslashes($cv)."',now(),'".addslashes($_SESSION['uname'])."')";
    $conn->query($insert);
    $update="update po_file set status='$new_status' where trans_no='$trans_num'";
    $conn->query($update);
}
if($_REQUEST['xstatus']=='change_status')
{
    $trans_num=$_REQUEST['trans_num'];
    $status=$_REQUEST['status'];

    //'Request for Cash Release','Ready for pick up','Receive Cash Request'
    $new_status="";
    if($status=='For Approval')
    $new_status="Request Release";
    else if($status=='Request Release')
    $new_status="Ready for pick up";
    else if($status=='Ready for pick up')
    $new_status="Receive Cash Request";
    else if($status=='Receive Cash Request')
    $new_status="Received";
    else
    $new_status=$status;
    print_r($_REQUEST);
    
    $update="update po_file set status='$new_status' where trans_no='$trans_num'";
echo $update;
    $conn->query($update);
    
    
}
?>		