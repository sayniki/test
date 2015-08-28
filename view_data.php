<?php
include 'page_header.php';
?>
<style>
    .container
    {
        width: 970px;
    }
    .action_btn
    {
        background-color:blue;
        color:white;
        padding:25px;
        
    }
       .nav-tabs {
    border-bottom: 1px solid #ddd;
    }
    .nav {
        margin-bottom: 0;
        padding-left: 0;
        list-style: none;
    }
 
    .nav-tabs>li {
        float: left;
        margin-bottom: -1px;
    }
    .nav>li {
        position: relative;
        display: block;
    }
    .nav-tabs>li.active>a, .nav-tabs>li.active>a:hover, .nav-tabs>li.active>a:focus .selected_tab {
        color: #555555;
        background-color: #fff;
        border: 1px solid #ddd;
        border-bottom-color: transparent;
        cursor: default;
        padding: 10px;
    }
    .tabby
    {
        padding:10px;
    }
    .nav-tabs>li>a {
        
        margin-right: 2px;
        line-height: 1.428571429;
        text-decoration:none;
        border: 1px solid transparent;
        border-radius: 4px 4px 0 0;
    }
</style>
<script>
    function get_data(page_type,type,trans_num)
    {
        
        notSubmit=true
        if(type=="View")         
        document.getElementById('form1').action = 'view_datas.php?trans_num='+trans_num+"&page_type="+page_type;
        else if (type=='Reject') {
            notSubmit=false
            test="confirm_btn(\""+page_type+"\","+trans_num+")"
            document.getElementById('reject_btn').innerHTML="<input type='button' value='Confirm' onclick='"+test+"'> <input type='button' value='Cancel'>";
            document.getElementById('reject_div').style.display="block"
        }
        else if (type=='Request Release') {
            notSubmit=false
            if (confirm("Are you sure you want to Approve this transaction?"))
            {
                
                 document.getElementById('form1').action = 'view_for_approve.php?type='+page_type
                 
                url="xstatus=change_status&status=Request Release&trans_num="+trans_num
                loadXMLDoc('get_type.php?'+url,reloadPage)
                return false;
            }
        }
        else
        {
            if(page_type=="Without PO")
            page_type="withoutpo"
            document.getElementById('form1').action = 'wo_po_form.php?type='+page_type+"&trans_num="+trans_num;
        }
        if (notSubmit) 
            document.form1.submit();
        return false;
    }
    function reloadPage(result) {
       
       document.form1.submit();
    }
    function reject_this(page_type,type,trans_num)
    {
        alert(type)
        test="confirm_btn(\""+page_type+"\","+trans_num+",\""+type+"\")"
        document.getElementById('reject_btn').innerHTML="<input type='button' value='Confirm' onclick='"+test+"'> <input type='button' value='Cancel'>";
        document.getElementById('reject_div').style.display="block"
    }
    function confirm_btn(page_type,trans_num,status)
    {
        if (document.getElementById('reasons').value=='') {
            alert("Please enter a reason")
        }
        else
        {
            document.getElementById('form1').action = 'delete_transaction.php?trans_num='+trans_num+"&page_type="+page_type+"&status="+status
            document.form1.submit();
        }
    }
</script>
<form name='form1' id='form1' method=post>
<div id='reject_div' style='width:300px;display:none;position: fixed;top:15%;left:40%;border:1px solid black;background-color:white'>
    <table>
        <tr>
            <th style='padding:10px'>Are you sure you want to reject this item? Please Enter reason for rejection</th>
        </tr>
        <tr>
            <td style='padding:10px;'>
                <textarea style='width:280px;height:60px' id='reasons' name='reason'></textarea>
            </td>
        </tr>
        <tr>
            <td style='padding:10px;text-align:center' id='reject_btn'>
            </td>
        </tr>
    </table>
</div>
<?php
$type="With PO";
if(!empty($_REQUEST['type']))
$type=$_REQUEST['type'];
$active_po="class='active selected_tab '";
$active_wpo="class='tabby'";
$limit=10;
$start=0;
$page=1;
if(!empty($_POST['page']))
{
    $page=$_POST['page'];
    $start=(($_POST['page']-1)*$limit);
}
if(strtolower($type)=="with po")
{
    $select="select * from po_file where po!='---' and (status not in ('Request Release','Ready for pick up','Receive Request','Receive Cash Request') or mas_status=0)";
    $column=array('Letter Code','Date','Po#','Requestor','Company Name','Supplier','Engineer','Secretary','Payment Type','Total Amount','Created By','Status');
    $val=array('letter_code','date_created','po','requestor','company_name','supplier','engineer','secretary','payment_type','total_amount','created_by','status','trans_no','mas_status');
}
else
{
    $type="Without PO";
    $active_po="class='tabby'";
    $active_wpo="class='active selected_tab'";
    $select="select * from po_file where po='---' and (status not in ('Request Release','Ready for pick up','Receive Request','Receive Cash Request') or mas_status=0)";
    $column=array('Letter Code','Date','Requestor','Company Name','Supplier','Secretary','Payment Type','Total Amount','Created By','Status');
    $val=array('letter_code','date_created','requestor','company_name','supplier','secretary','payment_type','total_amount','created_by','status','trans_no','mas_status');
}
$select2=str_replace("*","id",$select);
$result = $conn->query($select2);
$rowcount=mysqli_num_rows($result);
$data=array();
$result = $conn->query($select." limit $start,$limit ");
$pages=$rowcount/$limit;
if((int)$pages<$pages)
    $pages++;
$pages=(int)$pages;
// $result = $conn->query($select);
$data=array();
while($row=$result->fetch_assoc())
{
    $items=array('');
    for($a=0;$a<count($val);$a++)
    {
        
        if($a==0 &&$val[$a]!='' )
        {$items[$a]=$row[$val[$a]];    }
        else if($a==1)
        $items[$a]=date("F d,Y",strtotime($row[$val[$a]]));
        else
        {
            if(empty($row[$val[$a]]))
            $row[$val[$a]]="";
            $items[$a]=$row[$val[$a]];
        }
    }
    $status[]=$row['status'];
    $data[]=$items;
}
?>
<div>
    <ul class='nav nav-tabs'>
        <li <?php echo $active_po;?> role='presentation'><a href='view_data.php?type=With Po'>With PO</a></li>
        <li <?php echo $active_wpo;?> role='presentation'><a href='view_data.php?type=Without Po'>Without PO</a></li>
    </ul>
</div>

<?php
echo "<br>  <h2 style='text-align:left;padding:0px' >RM $type</h2>";
?>

<table class='table_data'>
    <?php
        echo "<tr>";
    for($a=0;$a<count($column);$a++)
            echo "<th>".$column[$a]."</th>";
    echo "<th colspan=2>Action</th>";
        echo "</tr>";
        for($a=0;$a<count($data);$a++)
        {
            echo "<tr>";
            for($k=0;$k<2;$k++)
                echo "<td>".$data[$a][$k]."</td>";

if(empty($requestor[$data[$a][$k]]))
{
    $select="select concat(first_name,' ',last_name) as name from master_address_file where account_id='".$data[$a][$k]."' limit 1";
   
  $result = $conn->query($select);
$row1=$result->fetch_assoc();
$requestor[$data[$a][$k]]=$row1['name'];

}

echo "<td>".$requestor[$data[$a][$k]]."</td>";
 for($k=3;$k<5;$k++)
                echo "<td>".$data[$a][$k]."</td>";
if(empty($secretary[$data[$a][$k]]))
{
    $select="select concat(first_name,' ',last_name) as name from master_address_file where account_id='".$data[$a][$k]."' limit 1";
   
  $result = $conn->query($select);
$row1=$result->fetch_assoc();
$secretary[$data[$a][$k]]=$row1['name'];

}
echo "<td>".$secretary[$data[$a][$k]]."</td>";
 for($k=6;$k<count($data[$a])-3;$k++)
                echo "<td>".$data[$a][$k]."</td>";
            if($data[$a][$k+2]!=1)
            echo "<td>Rejected</td>";  
            else
            echo "<td>".$data[$a][$k]."</td>";    
            
            echo "<td><input type='image' src='assets/view_details.png' name='image' width='20' height='20' onclick=\"get_data('".$type."','View','".$data[$a][$k+1]."')\"></td>";
            
            
            if($_SESSION['uname']==$data[$a][$k-2] && ($data[$a][$k-1]=='pending'||$data[$a][$k+2]!=1))
            echo "<td><input type='image' src='assets/Edit.png' name='image' width='20' height='20' onclick=\"get_data('".$type."','Edit','".$data[$a][$k]."')\"></td>";
            
            //echo "<td><input type='image' src='assets/cross.jpg' name='image' width='20' height='20' onclick=\"get_data('".$type."','Reject','".$data[$a][$k]."')\"></td>";
            if($data[$a][$k+2]==1)
            echo "<td><img  src='assets/cross.jpg' name='image' width='20' height='20' onclick=\"reject_this('".$type."','".$data[$a][$k]."','".$data[$a][$k+1]."');\"></td>";
        
            $k++;
            if($data[$a][$k-1]!='pending')
            echo "<td><img  src='assets/check.png' name='image' width='20' height='20' onclick=\"get_data('".$type."','Request Release','".$data[$a][$k]."');\"></td>";
        
            //echo "<td><input type='image' src='assets/check.png' name='image' width='20' height='20' onclick=\"get_data('".$type."','Request Release','".$data[$a][$k]."');\"></td>";
        
            echo "</tr>";
        }
        
    ?>
<tr>
     <td colspan=20 style='text-align:center'>
            <table align=center > 
                <?php
                    if($page!=1)
                    echo "<td><input type='button' value='First' onclick='getPage(1)'></td>";
                    if($page>1)
                    echo "<td><input type='button' value='Prev' onclick='getPage(".($page-1).")'></td>";
                    if($page+1<=$pages)
                    echo "<td><input type='button' value='Next' onclick='getPage(".($page+1).")'></td>";
                    if($page!=$pages)
                    echo "<td><input type='button' value='Last' onclick='getPage(".($pages).")'></td>";
                ?>
                <td style='width:200px;border:none;padding: 0px' colspan=16>Page
                    <select style='font-size:24px' id='page' name='page' onchange='getPage(this.value)' >
                        <?php
                        for($a=0;$a<$pages;$a++)
                        {
                            if($a+1==$page)
                            echo "<option selected>".($a+1)."</option>";
                            else
                            echo "<option >".($a+1)."</option>";
                        }
                        ?>
                    </select>
                    of <?php echo $pages;?> 
                </td>
            </table>
        </td>
    </tr>   
</table>
</form>		