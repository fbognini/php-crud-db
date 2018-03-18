<?
	session_start();
	include_once("../config.php");
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$username = mysqli_real_escape_string($mysqli, strtoupper($_POST["username"]));
		$password = mysqli_real_escape_string($mysqli, md5(strtoupper($_POST["password"])));
		$query = 'SELECT * FROM login_utenti WHERE username="'.$username.'" AND password="'.$password.'";';
		$result = mysqli_query($mysqli, $query);
		if(mysqli_num_rows($result)>0){  
			$row = mysqli_fetch_assoc($result);
			$_SESSION["username"] = $username;
			$_SESSION["usertype"] = $row["tipo"];
			if ($line[2][0]=='A')
				header("Location: ../admin/");
			else
				header("Location: ../user/");
		}
		else {
			// echo $query;
			$error = "Credenziali non valide";
		}
	}
?>
<!DOCTYPE html>
<html>
  <head>
		<title>Login</title>
		
		<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<link rel="stylesheet" href="../css/style.css">
		
  </head>
	<body class="login-page">
		<div class="container">
        <form method='POST' class="form-signin">
						<h2 class="form-signin-heading">Accesso alla piattaforma</h2>
            <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
						<?
							echo "<p class='error'>$error</p>";
						?>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Accedi</button>
				</form>
				<div class="message"> 
					<p><i>Accedere come utente <b>ROOT</b> (password: <b>ROOT</b>) per testare le funzionalità da amministratore</i></p>
				</div>
		</div>
		<footer class="container">
			<hr>
			<p>&copy; Franceso Bognini - I sorgenti sono disponibili su <a href="https://github.com/fbognini/php-crud-db" target="_blank">GitHub</a></p>
		</footer>

	</body>
</html>
