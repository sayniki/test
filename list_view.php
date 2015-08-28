<?php
include 'page_header.php';


echo "<table>";
    echo "<tr>";
        echo "<td style='text-align:right'><a href='masters.php?'>Add New</a>";
        echo "</td>";
    echo "</tr>";
    echo "<tr>";
    
        $val_array=array('account_type','first_name','last_name','department_id','account_executive_id','phone_number','date_created','mas_status');
    listMaker('master_address_file','first_name',$val_array,'Address Book'.' List');
    echo "</tr>";
echo "</table>";
?>