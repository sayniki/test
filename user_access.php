<?php
$select="select * from menu_file order by menu_head,menu_order";
$result = $conn->query($select);
echo "<table>";
while($row=$result->fetch_assoc())
{
    echo "<tr>";
        if($row['menu_type']==1)
        {
            echo "<th>".$row['']
        }
    echo "</tr>";
}
echo "</table>";
$select="select * from user_access_file ";

?>