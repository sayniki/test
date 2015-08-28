<?php
include 'page_header.php';
print_r($_POST);
?>
<script>
    function get_type(type) {
        if (type=='Secretary' ) 
            document.getElementById('finance_div').style.display='block';
        else
            document.getElementById('finance_div').style.display='none';
        if (type=='Finance Head') 
            document.getElementById('department_div').style.display='block';
        else
            document.getElementById('department_div').style.display='none';
       
         if (type=='Secretary' ||type=='QA')
            document.getElementById('phone_id').style.display='block';
        else
            document.getElementById('phone_id').style.display='none';
            
    }
</script>
<?php

    if(!empty($_POST['submit_btn']))
    {
        $username=$_POST['user_name'];
        $user_type=$_POST['user_type'];
        $department=$_POST['department'];
        $finance_head=$_POST['finance_head'];
        $phone_number=$_POST['phone_number'];
        $first_name=$_POST['first_name'];
        $last_name=$_POST['last_name'];
        $account_executive_id=" ";
        if($user_type=='Secretary' || $user_type=='Finance_head')
        {
            $account_executive_id=$_POST['finance_head'];
            if( $user_type=='Finance_head')
            $user_type='account_executive';
            else
            {
                $select="select department from master_address_file as a inner join master_department_file  as k
                on a.department_id=k.department_id
                where account_id='".$account_executive_id."' and account_type='account_executive' limit 1";
                $result = $conn->query($select);
                $row=$result->fetch_assoc();
                $department=$row['department'];
            }
            
            $id=getId('master_address_file','account_id');
            
            $add_array=array('account_type',"account_id",'first_name','last_name','department_id','account_executive_id','phone_number','date_created');
            $value_array=array($user_type,$id,$first_name,$last_name,$department,$account_executive_id,$phone_number,'now()');
            
            
            $result=insertMaker('master_address_file',$add_array,$value_array);
            //$result=insertMaker('master_address_file',array('secretary','secretary_id','account_executive_id'),array($name,$id,$account_executive_id));
        }
        if($department=='Choose')
        $department="";
        $result=insertMaker('user_file',array('user_name','password','first_name','last_name','user_type','department','finance_head','phone_number'),array($username,'password123',$first_name,$last_name,$user_type,$department,$finance_head,$phone_number));
        echo "<script>alert('Successfully added New User')</script>";
    }
    
?>
<form name='form1' id='form1' method=post>
<table style='width:300px' class='form_css'>
    <tr>
        <th style='text-align:left'><h2>User File</h2></th>
    </tr>
    <?php
    $select="select department from master_department_file where mas_status=1 order by department";
    $result = $conn->query($select);
    $departments=array();
    while($row=$result->fetch_assoc())
        $departments[]=$row['department'];
    $finance_head=array();
    $finance_head=array();
     $select="select concat(first_name,' ',last_name) as account_executive,account_id from master_address_file
     where mas_status=1 and account_type='account executive' order by account_executive";
    $result = $conn->query($select);
    while($row=$result->fetch_assoc())
    {
        $finance_value[]=$row['account_id'];
        $finance_head[]=$row['account_executive'];
    }
    /*
    $select="select user_name from user_file where mas_status=1 order by user_name";
    $result = $conn->query($select);
    $username=array();
    while($row=$result->fetch_assoc())
        $username[]=$row['user_name'];*/
    echo textMaker('Username','user_name');
    echo textMaker('First Name','first_name');
    echo textMaker('Last Name','last_name');
    echo selectMaker('User Type','user_type',array('Finance Head','Secretary','QA','Admin','Cash Release'),'get_type(this.value)');
    
    echo "<tr><td colspan=2><div style='display:none' id='phone_id'>";
    echo "<table>";
    
    echo "<tr >";
    echo textMaker('Phone Number','phone_number');
    echo "</tr>";
    echo "</table></td></tr>";
    echo "<tr>";
    echo "<tr><td colspan=2><div style='display:none' id='department_div'>";
    echo "<table>";
    echo selectMaker('Department','department',$departments,'');
    echo "</table>";
    echo "</div></td></tr>";
    echo "<tr><td colspan=2><div style='display:none' id='finance_div'>";
    echo "<table>";
    echo selectMakerValue('Finance Head','finance_head',$finance_head,'',$finance_head);
    echo "</table>";
    echo "</div></td></tr>";
    echo "<tr>";
        echo "<td colspan=2 style='text-align:center'>";
            echo "<input type='submit' name='submit_btn' value='Submit' style='margin:15px'>";
            echo "<input type='button' value='Cancel' style='margin:15px'>";
        echo "</td>";
    echo "</tr>";
    ?>
</table>
</form>