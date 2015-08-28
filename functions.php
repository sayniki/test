<?php
function textMaker($label,$id,$value='')
{
    return "<tr><th style='text-align:left'>$label</th><td><input type='text' id='$id' name='$id' value=\"".$value." \"></td></tr>";
}
function updateMaker($table,$columns,$val,$trans_num)
{
    global $conn;
    $in="";
    for($a=0;$a<count($val);$a++)
    {
        if($in!='')
        $in.=",";
        $in.=$columns[$a]."='".$val[$a]."'";
    }
    $update="update $table set ".$in." ".$trans_num." ";
    $conn->query($update);
    echo $update;
}
function getId($table,$var)
{
    global $conn;
    $select="select ".$var." from ".$table." order by ".$var." desc limit 1";
    $id=1;
    $result = $conn->query($select);
    if ($result->num_rows > 0)
    {
        $row = $result->fetch_assoc();
        $id=$row[$var]+1;
    }
    return $id;
}
function get_title($type)
{
    switch ($type) {
        case 'account_executive': return 'Account Executive'; break;
        case 'engineer': return 'Engineer';break;
        case 'department': return 'Department'; break;
        case 'secretary': return 'Secretary'; break;
    }
        
}
function get_letter_code($number_code)
{
 $letter_code="";
 if($number_code>0)
 {
    $alpha = " ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $count=26;
    
    $num2=$number_code;
     $letter=array();
    while($num2>26)
    {
        $a=$num2/26;
        $b=$num2-((int)$a*$count);
        $letter[]=$alpha[$b];
        $num2=(int)$a;
    }
    if($num2>0)
    $letter[]=$alpha[$num2];
         for($a=count($letter)-1;$a>=0;$a--)
      $letter_code.=$letter[$a];
 }
      return $letter_code;
}
function toStringList($array)
{
    $list="";
    for($a=0;$a<count($array);$a++)
    {
        if($list!='')
        $list.=",";
        $list.="`".$array[$a]."`";
    }
    return $list;
}
function convert_to_dateTime($date)
{
    return date("F d, Y h:m:i a",strtotime($date));
}
function listMaker($table_name,$order,$select_list,$title)
{
    global $conn;
    
    $sel=" ";
     if($order=='secretary'|| $order=='engineer')
     $sel=" ,account_executive_id";
    $select="select ".toStringList($select_list).$sel."  from $table_name order by $order";
    ?>
    <h2><?php echo $title;?></h2>
    <form name='form1' id='form1' method=post>
    <table class='table_data'>
    <?php
    echo $select;
    $result = $conn->query($select);
    echo "<tr>";
        for($a=0;$a<count($select_list);$a++)
        {
            if($select_list[$a]=='department_id')
            echo "<th>Department</th>";
            else if($select_list[$a]=='account_executive_id')
            echo "<th>Account Executive</th>";
            else
            echo "<th>".ucwords(str_replace("_"," ",$select_list[$a]))."</tk>";
        }
        if($order=='secretary'|| $order=='engineer')
            echo "<th>Account Executive</tk>";
            
        echo "<th colspan=2 style='text-align:left'>Action</th>";
    echo "</tr>";
    $executive=array();
    $department_list=array();
    while($row=$result->fetch_assoc())
    {
        /*
        if(($order=='secretary'|| $order=='engineer') && empty($executive[$row['account_executive_id']]))
        {
            $select="select account_executive from master_account_executive_file where account_executive_id='".$row['account_executive_id']."' limit 1";
            $result2= $conn->query($select);
            $row2=$result2->fetch_assoc();
            $executive[$row['account_executive_id']]=$row2['account_executive'];
        }*/
        echo "<tr>";
            for($a=0;$a<count($select_list);$a++)
            {
                if($select_list[$a]=='account_type')
                    echo "<td>".ucwords(str_replace("_"," ",$row['account_type']))."</td>";
                else if($select_list[$a]=='mas_status')
                {
                    if($row['mas_status']==1)
                    echo "<td>Active</td>";
                    else
                    echo "<td>Inactive</td>";
                }
                else if($select_list[$a]=='department_id')
                {
                    if(empty($department_list[$row[$select_list[$a]]]))
                    {
                        $select="select department from master_department_file where department_id='".$row[$select_list[$a]]."' limit 1";
                        $result2= $conn->query($select);
                        $row2=$result2->fetch_assoc();
                        $department_list[$row[$select_list[$a]]]=$row2['department'];
                    }
                    echo "<td>".$department_list[$row[$select_list[$a]]]."</td>";
                }
                else if($select_list[$a]=='account_executive_id')
                {
                    if(empty($accout_exe[$row[$select_list[$a]]]))
                    {
                        $select="select concat(first_name,' ',last_name) as name from master_address_file where account_type='account_executive' and account_id='".$row[$select_list[$a]]."' limit 1";
                        $result2= $conn->query($select);
                        $row2=$result2->fetch_assoc();
                        $accout_exe[$row[$select_list[$a]]]=$row2['name'];
                    }
                    echo "<td>".$accout_exe[$row[$select_list[$a]]]."</td>";
                        
                }
                else if($select_list[$a]=='date_created')
                {
                    echo "<td>".date("F j, Y")."</td>";
                }
                else
                echo "<td>".$row[$select_list[$a]]."</td>";
            }
            if($order=='secretary'|| $order=='engineer')
            echo "<td>".$executive[$row['account_executive_id']]."</td>";
            echo "<td><input type='image' src='assets/Edit.png' name='image' width='20' height='20' onclick=''></td>";
            if($row['mas_status']==0)
            echo "<td><input type='image' src='assets/check.png' name='image' width='20' height='20' onclick=''></td>";
            else
            echo "<td><input type='image' src='assets/cross.jpg' name='image' width='21' height='20' onclick=''></td>";
            
        echo "</tr>";
    }
    ?>  
    </table>
    </form>
    <?php
}

function selectMakerValue($label,$id,$array,$function,$value,$val='' )
{
    
    $func="";
    if($function!='')
    $func="onchange='".$function."'";
    $text= "<tr><th style='text-align:left'>".$label."</th>";
    $text.="<td><select id='".$id."' name='".$id."' ".$func.">";
    $text.= "<option>Choose</option>";
    for($a=0;$a<count($array);$a++)
    {
        if($val==$value[$a])
        $text.= "<option selected value='".$value[$a]."'>".$array[$a]."</option>";
        else
        $text.= "<option value='".$value[$a]."'>".$array[$a]."</option>";
    }
     $text.="</select>";
     $text.="</td></tr>";
     return $text;
}
function selectMakerEach($label,$id,$array,$function , $default='')
{
    $func="";
    if($function!='')
    $func="onchange='".$function."'";
    $text= "<tr><th style='text-align:left'>".$label."</th>";
    $text.="<td><select id='".$id."' name='".$id."' ".$func.">";
    $text.= "<option>Choose</option>";
    foreach($array as $key=>$value)
    {
        if($default==$key)
        $text.= "<option selected value='".$key."'>".$value."</option>";
        else
        $text.= "<option value='".$key."'>".$value."</option>";
    }
     $text.="</select>";
     $text.="</td></tr>";
     return $text;
}
function selectMaker($label,$id,$array,$function , $default='')
{
    $func="";
    if($function!='')
    $func="onchange='".$function."'";
    $text= "<tr><th style='text-align:left'>".$label."</th>";
    $text.="<td><select id='".$id."' name='".$id."' ".$func.">";
    $text.= "<option>Choose</option>";
    for($a=0;$a<count($array);$a++)
    {
        if($default==$array[$a])
        $text.= "<option selected>".$array[$a]."</option>";
        else
        $text.= "<option>".$array[$a]."</option>";
    }
     $text.="</select>";
     $text.="</td></tr>";
     return $text;
}
function insertMaker($table_name,$columns,$val)
{
    global $conn;
    $insert="insert into ".$table_name."(";
    for($a=0;$a<count($columns);$a++)
    {
        if($a!=0)
        $insert.=",";
        $insert.="`".$columns[$a]."`";
    }
    $insert.=")values(";
    for($a=0;$a<count($val);$a++)
    {
        if($a!=0)
        $insert.=",";
        if($val[$a]!='now()')
        $insert.="'".addslashes($val[$a])."'";
        else
        $insert.="now()";
    }
    $insert.=")";
    $result = $conn->query($insert);
    return $result;
    
}
function selectMakerArray($title,$id,$departments)
{
    $text="<tr><th style='text-align:left'>".$title."</th>";
    $text.= "<td><select id='".$id."' name='".$id."'>";
    $text.="<option>Choose</option>";
    for($a=0;$a<count($departments);$a++)
        $text.= "<option value=\"".$departments[$a][1]."\">".$departments[$a][0]."</option>";
    $text.= "</select></td>";
    return $text;
}
?>

