<?php
include("conn.php");

if(isset($_POST['category']))
{
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    $query = mysqli_query($conn,
        "SELECT * FROM sub_cat
         WHERE category_name='$category'
         ORDER BY sub_cat ASC"
    );

    echo '<option value="">Select Sub Category</option>';

    while($row = mysqli_fetch_assoc($query))
    {
        echo '<option value="'.$row['sub_cat'].'">';
        echo $row['sub_cat'];
        echo '</option>';
    }
}
?>