<?php
include 'page_header.php';
?>
<script>
    function hide_this(a)
    {
        document.getElementById('sms_row'+a).style.display='none'
        document.getElementById('2sms_row'+a).style.display='none'
    }
    function approve_btn(trans_num)
    {
        if (confirm("Are you sure you want to Approve this transaction?")) {
            
            url="xstatus=change_status&status=Request Release&trans_num="+trans_num
            loadXMLDoc('get_type.php?'+url,reloadPage)
            
            return false;
        }
    }
    function reloadPage(result)
    {
        page_type=document.getElementById('page_type').value
        window.location.assign('view_for_approve.php?page_type='+page_type);
    }
    function edit_btn(page_type,trans_num)
    {
        if(page_type=="Without PO")
            page_type="withoutpo"
            document.getElementById('form1').action = 'wo_po_form.php?type='+page_type+"&trans_num="+trans_num;
            document.form1.submit();
    }
</script>
<?php
$trans_num=$_REQUEST['trans_num'];
if(empty($_SESSION['uname']))
    echo "<script>window.location.assign('login.php')</script>";
echo "<form name='form1' id='form1' method=post>";


$type=$_REQUEST['page_type'];

echo "<input type='hidden' id='page_type' name='page_type' value='$type'>";
echo "<input type='hidden' id='trans_num' name='trans_num'  value='$trans_num'>";



$select="select * from po_file WHERE trans_no='$trans_num' LIMIT 1";
$select2="select * from po_item_file where trans_no='$trans_num'";
$head="";
if($type=="po_type"||$type=="With Po")
{
    $column=array('Letter Code', 'Requestor', 'Title', 'Company Name', 'Secretary', 'Supplier', 'Payment Type', 'Status', 'JO', 'PO', 'Item Description','Date Created','Created By');
    $val=array('letter_code', 'requestor', 'title', 'company_name', 'secretary', 'supplier', 'payment_type',  'status', 'jo', 'po', 'item_description','date_created','created_by','status');
    $head="<h2>With Po</h2>";
}
else
{
    $column=array('Letter Code', 'Requestor', 'Title', 'Company Name', 'Secretary', 'Supplier', 'Payment Type', 'Status', 'JO',  'Item Description','Date Created','Created By');
    $val=array('letter_code', 'requestor', 'title', 'company_name', 'secretary', 'supplier', 'payment_type',  'status', 'jo', 'item_description','date_created','created_by','status');
    $head="<h2>Without Po</h2>";
}

$result = $conn->query($select);
$result2 = $conn->query($select2);
if ($result->num_rows > 0)
{
    
   $row = $result->fetch_assoc();
   $requestor_id=$row['requestor'];
   $select2="select phone_number,concat(first_name,' ',last_name) as requestor  from master_address_file where account_type='Account Executive' and mas_status=1 and account_id='$requestor' limit 1";
    $result2 = $conn->query($select2);
    $row3=$result2->fetch_assoc();
    $requestor=$row3['requestor'];
    $phone_number=$row3['phone_number'];
    if(!empty($_REQUEST['chat_box']))
    {
        $text="Letter Code ".$row['letter_code']." ".$_REQUEST['chat_box'];
        $insert="insert into chat_history_file (remarks,user_name,chat_date,trans_no)
        values('".addslashes($_REQUEST['chat_box'])."','".$_SESSION['uname']."',now(),'".$trans_num."')";
        $conn->query($insert);
        $response = file_get_contents("http://127.0.0.1:13013/cgi-bin/sendsms?user=sms-app&pass=app125&text=$text&to=".$row3['phone_number']);
    }

   $trans_no=$row['trans_no'];
   if(!empty($_REQUEST['num']))
    {
        $num=$_REQUEST['num'];
        if(!empty($_REQUEST['sms_id']))
        {
            $sms_id=$_REQUEST['sms_id'];
            for($a=0;$a<count($sms_id);$a++)
            {
                $update="update sms_files set trans_no='".$trans_no."' where sms_id='".$sms_id[$a]."'";
                $conn->query($update);
            }
        }
    }
    echo "<table style='width:100%;border-collapse:collapse' >";
    echo "<td style='vertical-align:top'>";
    echo "<table style='border-collapse:collapse'>";
    echo "<tr><th colspan=2 style='text-align:left'>".$head."</th></tr>";
    
    
    
    for($a=0;$a<count($val)-1;$a++)
    {
        echo "<tr>";
            echo "<th style='text-align:left;padding-top:7px;padding-right:15px;'>".$column[$a]."</th><td>";
            if($val[$a]!='')
            echo $row[$val[$a]];
            echo "</td>";
        echo "</tr>";
    }
    $status=$row['status'];
    if($result2->num_rows > 0)
    {
        echo "<tr><th colspan=2 style='text-align:left;font-size:25px;padding:10px'>Items</th></tr>";
        echo "<tr><td colspan=2><table border=1 style='border-collapse:collapse'>";
        echo "<thead style='display:block'>";
        echo "<tr>";
            echo "<th style='width:100px;padding:10px;'>Item</th>";
            echo "<th style='width:100px;padding:10px;'>Description</th>";
            echo "<th style='width:100px;padding:10px;'>Quantity</th>";
            echo "<th style='width:100px;padding:10px;'>Unit Price</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody style='min-height:100px;overflow-y:auto;display:block;min-width:520px'>";
        while($row2=$result2->fetch_assoc())
        {
            echo "<tr>";
                echo "<th style='width:100px;padding:10px;border:1px solid black;text-align:left'>".$row2['item']."</th>";
                echo "<th style='width:100px;padding:10px;text-align:left'>".$row2['description']."</th>";
                echo "<td style='width:100px;padding:10px;'>".$row2['quantity']."</td>";
                 echo "<td style='width:100px;padding:10px;'>".$row2['unit_price']."</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</tr></table></td>";
    }
  //  echo $row['created_by']."==".$_SESSION['uname'];
    if($status=='Receive Cash Request' || $status=="Received")
    {
        $select="select name,title,cv from po_check_file where trans_no='$trans_num' limit 1";
        $result = $conn->query($select);
        while($row2=$result->fetch_assoc())
        {
            echo "<tr><td colspan=5><table>";
            echo "<tr><th colspan=2 style='padding-top:10px;font-size:19px'>Check Details</th></tr>";
            echo "<tr><th style='width:50px;text-align:left'>Name</th><td>name".$row2['name']."</td></tr></tr>";
            echo "<tr><th style='width:50px;text-align:left'>Title</th><td>".$row2['title']."</td></tr>";
            echo "<tr><th style='width:50px;text-align:left'>CV</th><td>".$ro2w['cv']."</td></tr>";
            echo "</table></td></tr>";
        }
    }
    
    $select="select remarks,date_created,rejected_by,status from po_remarks_file where trans_no='$trans_num' limit 1";
    $result2 = $conn->query($select);
    $rowcount=mysqli_num_rows($result2);
    if($rowcount>0)
    {
        echo "<tr><td colspan=5><table>";
            echo "<tr><th colspan=2 style='padding-top:10px;padding-bottom:10px;font-size:19px;text-align:left'>Rejection History</th></tr>";
        while($row2=$result2->fetch_assoc())
        {
            echo "<tr><th style='width:115px;text-align:left'>Rejected By</th><td>".$row2['rejected_by']."</td></tr>";
            echo "<tr><th style='width:115px;text-align:left'>Date Rejected</th><td>".$row2['date_created']."</td></tr>";
            echo "<tr><th style='width:115px;text-align:left'>Status</th><td>".$row2['status']."</td></tr>";
            echo "<tr><th style='width:115px;text-align:left'>Remarks</th><td>name".$row2['remarks']."</td></tr></tr>";
           
           
        }
         echo "</table></td></tr>";
    }
    if(($status=='pending'||$row['mas_status']!=1) && $row['created_by']==$_SESSION['uname'])
    echo "<tr><td colspan=2 style='text-align:center;padding-top:10px'><input onclick='edit_btn(\"".$type."\",".$trans_num.")' style='padding:10px;height:50px' type='button' value='Edit'></td></tr>";
    else if($status=='For Approval')
    echo "<tr><td colspan=2 style='text-align:center;padding-top:10px'><input onclick='approve_btn(".$trans_num.")' style='padding:10px;height:50px' type='button' value='Approve'></td></tr>";
    if($status!='pending')
    {
        echo "<tr><th colspan=2><h2>Chat Box</h2> </th></tr>";
        echo "<tr><td colspan=2>
        <textarea id='chat_box' name='chat_box' style='width:100%;height:100px'></textarea>
        </td></tr>";
        echo "<tr><td colspan=2 style='text-align:right;padding:10px'><input type='submit' value='Submit'></td>";
        $select="select remarks,date,received from ((select sms as remarks,date_sent as date,received from sms_files where trans_no='".$trans_num."' ) UNION ALL
        (select remarks ,chat_date as date,user_name as received     from chat_history_file where trans_no='".$trans_num."')) as s order by date";
        //echo $select;
        $result = $conn->query($select);
         while($row=$result->fetch_assoc())
        {
            echo "<tr><td colspan=3 style='padding:0px;' ><table style='border:1px solid black;width:400px;border-collapse:collapse'>";
            echo "<tr>";
                echo "<td style='width:100px;text-align:left;padding:5px'><b>Sender:</b></td><td> ".$row['received']."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td style='text-align:left;;padding:5px'><b>Remarks:</b> </td><td> ".$row['remarks']."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td colspan=2 style='padding:5px;text-align:left'>".convert_to_dateTime($row['date'])."</td>";
            echo "</tr>";
            echo "</table></td></tr>";
        }
        
        echo "</table>";
        echo "</td>";
    
        $select="select * from sms_files where hide!=1 and trans_no='0' and received='$phone_number' order by date_sent desc ";
        $result = $conn->query($select);
        if($result->num_rows > 0)
        {
            echo "<td style='vertical-align:top'>";
                echo "<h2>SMS List</h2>";
                    echo "<table  style='border-collapse:collapse'>";
                    echo "<tbody style='border:1px solid black;display:block;height:400px;overflow:auto;min-width:300px'>";
                    $a=0;
                    while($row=$result->fetch_assoc())
                    {
                       // echo "<tr><th>".$row['received']."</th></tr>";
                        echo "<tr><th style='width:60px;padding-left:10px;padding-top:8px;padding-bottom:10px;text-align:left'>Sender</th>
                        <td style='padding-left:10px;padding-top:8px;padding-bottom:10px;text-align:left'>".$row['received']."</th></tr>";
                        echo "<tr  id='sms_row".$a."'  style='vertical-align: middle;display:block' name='sms_row".$a."'>";
                            echo "<td style='width:60px;padding-left:10px;padding-top:8px;padding-bottom:10px;text-align:right;vertical-align: middle;'>
                            <input type='checkbox' id='sms_id$a' name='sms_id[] ' value='".$row['sms_id']."' style='text-align:right;width:50px;height:30px' ></td>";
                            echo "<td style='padding-left:5px;border:1px solid black;min-width:250px;min-height:190px;vertical-align: middle;'>".$row['sms']."</td>";
                        echo "</tr>"; 
                        echo "<tr id='sms_row".$a."' name='sms_row".$a."'>";
                            echo "<td colspan=2 style='padding-left:10px;text-align:left'>".convert_to_dateTime($row['date_sent']);
                            echo "<input type='button' style='border:none;background-color:transparent;margin:10px;textdecoration:underliner;' onclick='hide_this($a)' value='Hide'>";
                            echo "</td>";
                        echo "</tr>";
                        $a++;
                    }
                  //  echo "<input type='hidden' id='num' name='num' value='$a'>";
                    echo "</tbody>";
                    echo "<tr>";
                        echo "<td colspan=2 style='text-align:center'><input type='hidden' id='num' name='num' value='$a'>
                        <input style='width:100px;padding:5px;height:20px' type='submit' name='match_submit' value='Match' ></td>";
                    echo "</tr>";
                    echo "</table>";
                echo "</td>";
        }
        else
            echo "<td><h2>No Data Found</h2></td>";
            echo "</tr>";
    }    
    echo "</table>";
}
?>
</form>