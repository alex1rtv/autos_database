<?php
	session_start();	
	
	if (!isset($_SESSION['email'])) die("Not logged in");
	
?>


<!DOCTYPE html>
<html>
<head>
<title>Automobile Tracker</title>
</head>
<body>
<div class="container">
<h1>Tracking Autos for <?php echo $_SESSION['email']; ?> </h1>

<?php
	if ( isset($_SESSION['success']) ) {
		echo('<p style="color: green;">' . htmlentities($_SESSION['success']) . "</p>");
		unset($_SESSION['success']);
	}
?>

<h2>Automobiles</h2>

<?php
	echo "<ul>";
		try {
			require_once("pdo.php");
			$sql = "SELECT make, year, mileage FROM autos";
			$stmt = $pdo->query($sql);
			while($row = $stmt-> fetch(PDO::FETCH_ASSOC)) {
				echo "<li>" . $row['year'] . " " . $row['make'] . " / " . $row['mileage'] . "</li>";
			}			
		} 
		catch (PDOException $e) {
			$_SESSION['error'] = "PDO error: " . $e->getMessage();
			return;
		}		
	echo "</ul>";
?>

<p>
<a href="add.php">Add New</a> |
<a href="logout.php">Logout</a>
</p>
</div>
</body>
</html>
