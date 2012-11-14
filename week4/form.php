<form action="submit.php" id="someform" method="post">
    <label>Name: <input class="forminput" type="text" name="name" /></label>
    <label>Email: <input class="forminput" type="text" name="email" autocapitalize="off" /></label>


<select name="book">
<?php
include("config.php");
$query = "SELECT * FROM books";
$result = mysql_query($query);
while ($row = mysql_fetch_assoc($result)) {

    echo "<option value='".$row["asin"]."'>".$row["title"]."</option>";

}
?>
</select>

<input type="submit" class="medium red awesome" value="Order &raquo;" />

</form>
