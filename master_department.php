<?php
include 'page_header.php';
if(!empty($_POST['submit_btn']))
{
    $department=$_POST['department'];
    $result=insertMaker('master_department_file',array('department'),array($department));
}
?>
<form name=form1 id=form1 method=post></form>
<table style='width:400px' class='form_css'>
    <tr>
        <th style='text-align:left'><h2>Department</h2></th>
    </tr>
    <?php
    echo textMaker('Department Name','department');
    echo "<tr>";
        echo "<td colspan=2 style='text-align:center'>";
            echo "<input type='submit' name='submit_btn' value='Submit' style='margin:15px'>";
            echo "<input type='button' value='Cancel' style='margin:15px'>";
        echo "</td>";
    echo "</tr>";
    ?>
</table>