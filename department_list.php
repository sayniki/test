<?php
include 'page_header.php';
echo "<table>";
    echo "<tr>";
        echo "<td style='text-align:right'><a href='master_department.php'>Add New</a>";
        echo "</td>";
    echo "</tr>";
    echo "<tr>";
        listMaker('master_department_file','department',array('department','mas_status'),'Department List');
    echo "</tr>";
echo "</table>";?>