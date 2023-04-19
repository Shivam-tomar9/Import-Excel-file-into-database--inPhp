<?php require 'config.php'; ?>
 <!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <title>Excel</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
        <style>
            table,tr,td {
  border: 1px solid;
}
</style>
	</head>
	<body>
		<form class="" action="" method="post" enctype="multipart/form-data">
            <h1>Import Excel data into database</h1>
            <div class="container">
            <div class="form-group">
                <div class="col-md-4">
                
			<input type="file" name="excel" class="form-control" required value="">
			<button type="submit" class="btn btn-primary" name="import">Import</button>
</div>
</div>
		</form>
        <br>
		
		<!-- <table >
			<tr>
				<td>#</td>
				<td>Name</td>
				<td>Age</td>
				<td>Country</td>
			</tr>
			<?php
			$i = 1;
			$rows = mysqli_query($conn, "SELECT * FROM tb_data");
			foreach($rows as $row) :
			?>
			<tr>
				<td> <?php echo $i++; ?> </td>
				<td> <?php echo $row["name"]; ?> </td>
				<td> <?php echo $row["age"]; ?> </td>
				<td> <?php echo $row["email"]; ?> </td>
			</tr>
			<?php endforeach; ?>
            
		</table>
        <div> -->
		<?php

        //The first line uses the isset() function to check if the "import" 
        //button has been clicked and the form has been submitted.
		if(isset($_POST["import"])){

            //If the form has been submitted, the script retrieves the name of the uploaded Excel file from the $_FILES superglobal
            // array using the $_FILES
           // ["excel"]["name"] syntax. It also extracts the file extension from the file name using the explode() and end() 
			$fileName = $_FILES["excel"]["name"];
			$fileExtension = explode('.', $fileName);
            $fileExtension = strtolower(end($fileExtension));
			$newFileName = date("Y.m.d") . " - " . date("h.i.sa") . "." . $fileExtension;

           //The script then sets the target directory for the uploaded file and moves the file from the 
          //temporary directory to the target directory using the move_uploaded_file() function.
			$targetDirectory = "uploads/" . $newFileName;
			move_uploaded_file($_FILES['excel']['tmp_name'], $targetDirectory);

            //The next two lines disable error reporting to suppress any errors that might occur while reading the Excel file.
			error_reporting(0);
			ini_set('display_errors', 0);

            //The script requires two external PHP files, excel_reader2.php and SpreadsheetReader.php, 
            //that provide the necessary classes and functions for reading Excel files.
			require 'excelReader/excel_reader2.php';
			require 'excelReader/SpreadsheetReader.php';

            //The script creates a new instance of the SpreadsheetReader class using the target directory of the uploaded file as a parameter. 
            //It then loops through each row 
            //of the Excel file and extracts the values of the three columns (name, age, and email) into separate variables.
			$reader = new SpreadsheetReader($targetDirectory);
			foreach($reader as $key => $row){
				$name = $row[0];
				$age = $row[1];
				$email = $row[2];


            //Finally, the script inserts the extracted values into a MySQL database table called tb_data using the mysqli_query() function.
				mysqli_query($conn, "INSERT INTO tb_data VALUES('', '$name', '$age', '$email')");
			}

			echo
			"
			<script>
			alert('Succesfully Imported');
			document.location.href = '';
			</script>
			";
		}
		?>
	</body>
</html>
