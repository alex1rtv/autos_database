
<!DOCTYPE html>
<html>
<head>
<title>Automobile Tracker</title>
</head>
<body>

<?php
	session_start();
	
	if (!isset($_SESSION['email'])) die('Not logged in');
	
?>

<div class="container">
<h1>Tracking Autos for <?php echo $_SESSION['email']; ?> </h1>

<div class="container">
<p id="error_message"></p><br>
</div>

<?php	
	if (isset($_SESSION['error'])) {
		?>
			<script>
				let errDiv = document.getElementById("error_message");
				errDiv.innerHTML = "<p style='color: red;'>" + "<?=$_SESSION['error']?>" + "</p>";
			</script>
		<?php
		unset($_SESSION['error']);
	}	
	
	// check validity of input fields
	if (isset($_POST['add'])) {	
		if (!ctype_alnum($_POST['make']) || $_POST['make'] == "") {
			$_SESSION['error'] = "Incorrect make: must contain letters and digits only";
			header("Location: add.php");
			exit();
		}
		if (strlen($_POST['year']) !== 4 || !is_numeric($_POST['year']) || $_POST['year'] == "") {
			$_SESSION['error'] = "Incorrect year: must contain 4 digits only";
			header("Location: add.php");
			exit();
		}
		
		if (!is_numeric($_POST['mileage']) || $_POST['mileage'] == "") {
			$_SESSION['error'] = "Incorrect mileage: must contain digits only";
			header("Location: add.php");
			exit();
		}
		
		// write data into database
		try {
			require_once("pdo.php");
			$sql = "INSERT INTO autos (make, year, mileage) VALUES (:make, :year, :mileage)";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				':make' => $_POST['make'],
				':year' => $_POST['year'],
				':mileage' => $_POST['mileage'],
			));
		} 
		catch (PDOException $e) {
			$_SESSION['error'] = "PDO error: " . $e->getMessage();
			return;
		}
		
		$_SESSION['success'] = "Record inserted";
		header("Location: view.php");
		return;
	}	
	
?>


<form method="post">
<p>Make:
<input type="text" name="make" size="60"/></p>
<p>Year:
<input type="text" name="year"/></p>
<p>Mileage:
<input type="text" name="mileage"/></p>
<input type="submit" name="add" value="Add">
<input type="submit" name="cancel" value="Cancel">
</form>
</ul>
</body>
</html>
