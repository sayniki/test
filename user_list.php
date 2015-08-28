<?php
include 'page_header.php';
echo "<table>";
    echo "<tr>";
        echo "<td style='text-align:right'><a href='user_file.php'>Add New</a>";
        echo "</td>";
    echo "</tr>";
    echo "<tr>";
listMaker('user_file','created_date',array('user_name','name','user_type','department','finance_head','mas_status'),'User List');
    echo "</tr>";
echo "</table>";
?>