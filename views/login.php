<?
	session_start();
	include_once("../config.php");

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$username = strtoupper($_POST["username"]);
		$password = strtoupper($_POST["password"]);
		$_SESSION["username"] = $username;
		$_SESSION["password"] = $password;
		$query = 'SELECT * FROM utenti WHERE username="'.$username.'" AND password="'.$password.'";';
		$result = mysqli_query($mysqli, $query);
		if(mysqli_num_rows($result)>0){  
			$row = mysqli_fetch_assoc($result);
			$_SESSION["user-type"] = $row["tipo"];
			header("Location: ../index.php");
		}
		else {
			echo $query;
			$error = "Credenziali non valide";
		}
	}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>CRUD con db</title>
    <link rel="stylesheet" href="css/style.css">
  </head>
	<body class="align">
	  <div class="card login-form">
		<h3>Accesso alla piattaforma</h3>
		<form method='POST' name='form' id='form'>
			<input type='hidden' id='status' name="status"/>
			<div class="form-field">
				<input type="text" name="username" placeholder="username" required/>
			</div>
			<div class="form-field">
				<input type="password" name="password" placeholder="password" required/>
			</div>
			<?
				echo "<div><p class='error'>".$error."</p></div>";
			?>
			<div class="form-field">
				<input type="submit" value="accedi"/>
			</div>
		  <p class="message">I sorgenti sono disponibili su <a href="https://github.com/fbognini/php-crud-db">GitHub</a></p>
		</form>
	  </div>

	</body>
</html>
