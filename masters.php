<?php
include 'page_header.php';
?>
<script>
function change_info(type)
{
     if (type=='Secretary' ||type=='Engineer') 
            document.getElementById('account_exe').style.display='block';
        else
            document.getElementById('account_exe').style.display='none';
     if (type=='Account Executive' ) 
            document.getElementById('dept_div').style.display='block';
        else
            document.getElementById('dept_div').style.display='none';
}
</script>
<?php
//$addType=$_REQUEST['add_type'];
//$title=get_title($addType);
if(!empty($_POST['submit_btn']))
{
    $type=$_POST['account_type'];
    $id=getId('master_address_file','account_id');
    $first_name=$_POST['first_name'];
    $last_name=$_POST['last_name'];
    $department=$_POST['department'];
    $account_executive=$_POST['account_executive'];
    $phone_number=$_POST['phone_number'];
    $add_array=array('account_type',"account_id",'first_name','last_name','department_id','account_executive_id','phone_number','date_created');
    $value_array=array($type,$id,$first_name,$last_name,$department,$account_executive,$phone_number,'now()');
    
    
    $result=insertMaker('master_address_file',$add_array,$value_array);
    echo "<script>alert('Sucessfully Added New $type');</script>";
}
?>
<form name=form1 id=form1 method=post>
<table style='width:500px' class='form_css'>
    <tr>
        <th colspan=2 style='text-align:left'><h2>Address Book</h2></th>
    </tr>
    <?php
    
    
    echo selectMaker('Account Type','account_type',array('Account Executive','Secretary','Engineer'),"change_info(this.value)");
    echo textMaker('First Name','first_name');
    echo textMaker('Last Name','last_name');
    echo textMaker('Phone Number','phone_number');
    
        $finance_head=array();
        $select="select account_id , concat(first_name,' ',last_name) as account_executive from master_address_file
    where mas_status=1 and account_type='account executive' order by account_executive";
        $result = $conn->query($select);
        $finance_head=array();
        $finance_value=array();
        
        while($row=$result->fetch_assoc())
        {
            $finance_head[]=$row['account_executive'];
            $finance_value[]=$row['account_id'];
        }
        echo "<tr><td colspan=2><table id='account_exe' style='width:90%;display:none'>";
        echo selectMakerValue('Account Executive','account_executive',$finance_head,'',$finance_value);
        echo "</table></td></tr>";
        $select="select department,department_id from master_department_file where mas_status=1 order by department";
        $result = $conn->query($select);
        $departments=array();
        while($row=$result->fetch_assoc())
            $departments[]=array($row['department'],$row['department_id']);
        echo "<tr><td colspan=2><table id='dept_div' style='width:90%;display:none'>";
         echo selectMakerArray('Department','department',$departments);
          echo "</table></td></tr>";
    
    
    echo "<tr>";
        echo "<td colspan=2 style='text-align:center'>";
            echo "<input type='SUBMIT' name='submit_btn' value='Submit' style='margin:15px'>";
            echo "<input type='button' value='Cancel' style='margin:15px'>";
        echo "</td>";
    echo "</tr>";
    ?>
</form>
</table>
<?php
?>