<?php
include 'page_header.php';


$addType=$_REQUEST['add_type'];
$title=get_title($addType);
if(!empty($_POST['submit_btn']))
{
    $account_executives=$_POST[$addType];
    $result=insertMaker('master_'.$addType.'_file',array($addType),array($account_executives));
}
?>
<form name=form1 id=form1 method=post></form>
<table style='width:300px' class='form_css'>
    <tr>
        <th style='text-align:left'><h2><?php echo $title." List";?></h2></th>
    </tr>
    <?php
    echo textMaker($title,$addType);
    echo "<tr>";
        echo "<td colspan=2 style='text-align:center'>";
            echo "<input type='submit' name='submit_btn' value='Submit' style='margin:15px'>";
            echo "<input type='button' value='Cancel' style='margin:15px'>";
        echo "</td>";
    echo "</tr>";
    ?>
</table>