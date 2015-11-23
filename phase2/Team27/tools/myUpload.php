<?php
$target_f = basename($_FILES['upload']['name']);
move_uploaded_file($_FILES['upload']['tmp_name'],$target_f);
?>
<!DOCTYPE html>
<html>
<body>
<form action="myUpload.php" method="post" enctype="multipart/form-data">
    Select file:
    <input type="file" name="upload" id="fileToUpload">
    <input type="submit" value="Upload!" name="submit">
</form>
</body>
</html>
