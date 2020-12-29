
<!DOCTYPE html>
<html>
<head>
<title>Login Page</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1><br>
<p id="error_message"></p><br>

<?php
	
	session_start();	
	
	if (isset($_POST['cancel'])) {
		header("Location: index.php");
		exit();
	}

	if (isset($_SESSION['error'])) {
		?>
			<script>
				let errDiv = document.getElementById("error_message");
				errDiv.innerHTML = "<p style='color: red;'>" + "<?=$_SESSION['error']?>" + "</p>";
			</script>
		<?php
		unset($_SESSION['error']);
	}	
	else {		
		if (isset($_POST['login'])) {		
			if ($_POST['email'] == "" || $_POST['pass'] == "") {
				$_SESSION['error'] = "User name and password are required";
				header("Location: login.php");
				exit();
			} elseif(strpos($_POST['email'], "@") === false) {
				$_SESSION['error'] = "Email must have an at-sign (@)";
				header("Location: login.php");
				exit();
			} else {
				$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'fred', 'fred');
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
				$salt = 'XyZzy12*_';
				
				$md5_user_email = hash('md5', $salt . htmlentities($_POST['email']));
				$md5_user_password = hash('md5', $salt . htmlentities($_POST['pass']));
				
				$sql = 'SELECT email, password FROM users WHERE email="'. htmlentities($_POST['email']) . '"';		
				$stmt = $pdo->query($sql);
				
				if($stmt->rowCount() === 0) {
					$_SESSION['error'] = "Incorrect email";
					error_log("Login fail " . $_POST['email'] . " $check");
					header("Location: login.php");
					exit();
				}
				
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					$stored_hash = hash('md5', $salt . htmlentities($row['password']));
					if ($md5_user_password === $stored_hash) {
						$_SESSION['email'] = htmlentities($_POST['email']);
						error_log("Login success " . $_POST['email']);
						header("Location: view.php?email=" . urlencode($_POST['email']));
						exit();
					}
				}
				
				$_SESSION['error'] = "Incorrect password";
				error_log("Login fail " . $_POST['email'] . " $check");
				header("Location: login.php");
				exit();									
			}
		}
	}
	
?>


<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">


<form method="POST" action="login.php">
<label for="email">Email</label>
<input type="text" name="email" id="email"><br/>
<label for="id_1723">Password</label>
<input type="text" name="pass" id="id_1723"><br/>
<input type="submit" name="login" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>
For a password hint, view source and find an account and password hint
in the HTML comments.
<!-- Hint:
The account is csev@umich.edu
The password is the three character name of the
programming language used in this class (all lower case)
followed by 123. -->
</p>
</div>
</body>
