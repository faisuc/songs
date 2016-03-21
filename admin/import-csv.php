<h1>IMPORT CSV</h1>
<?php echo isset($_POST['message']) ? $_POST['message'] : ""; ?>
<form action="" method="post" enctype="multipart/form-data">
	<input type="file" name="csv" /> <input type="submit" name="importcsv" value="IMPORT" />
</form>