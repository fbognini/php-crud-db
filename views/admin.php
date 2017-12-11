<?
	session_start();
	include_once("../config.php");
	
	if(isset($_POST["status"]) && !empty($_POST["status"]))
		$status = $_POST["status"];
	else
		$status = "menu";
	
	if(isset($_POST["user-selected"]) && !empty($_POST["user-selected"]))
		$userSelected = $_POST["user-selected"];

	if($status == "createuser") {
		$newUsername = strtoupper($_POST["new-username"]);
		$newPassword = strtoupper($_POST["new-password"]);
		$newUserType = strtoupper($_POST["new-user-type"]);
		$query = 'SELECT * FROM utenti WHERE username="'.$newUsername.'";';
		$result = mysqli_query($mysqli, $query);
		$exist = false;
		if(mysqli_num_rows($result)>0){  
			$exist = true;
		}

		if ($exist) {
			$newUserError = "Impossibile creare l'utente ".$newUsername;
			$status = "create";
		}
		else {
			$userTypes = array("A" => "AMMINISTRATORE", "U" => "UTENTE");
			$newUserMessage = '<table class="summary-table"><tr><td>Username</td><td>'.$newUsername.'</td></tr><tr><td>Password</td><td>'.$newPassword.'</td></tr><tr><td>Tipo utente</td><td>'.$userTypes[$newUserType].'</td></tr></table>';
			$status = "created";
			$query = 'INSERT INTO utenti (username, password, tipo) VALUES ("'.$newUsername.'","'.$newPassword.'","'.$newUserType.'");';
			$result = mysqli_query($mysqli, $query);
			// echo $query;
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<title>Amministratore</title>
		<link rel="stylesheet" href="css/style.css">
		<script>
			function updateStatus(status){
				document.getElementById("status").value = status;
				document.getElementById("form").submit();
			}
			function setUser(user){
				document.getElementById("user-selected").value = user;
			}
		</script>
	</head>
	<body class="align">
	
	<div class="card">
		<h3>Pannello amministratore</h3>
		<?
			echo '<form method="POST" name="form" id="form">';
			echo '<input type="hidden" id="status" name="status"/>';
			echo '<input type="hidden" id="user-selected" name="user-selected"/>';
			// echo 'Stato '.$status;
			switch ($status) {
				case 'menu':
					echo '<h4>Benvenuto '.$_SESSION["username"].'</h4>';
					echo '<div class="form-field"><input type="button" value="lista utenti" onclick="updateStatus(\'read\')"/></div>';
					echo '<div class="logout-button"><a href="logout.php">Logout</a></div>';
					break;
				case 'read':
					echo '<h4>Lista utenti</h4>';
					echo '<table class="user-table"><thead><tr><th>Username</th><th>Password</th><th>Tipo</th><th colspan="2">Aggiorna</th></tr></thead><tbody>';
					$query = 'SELECT * FROM utenti;';
					$result = mysqli_query($mysqli, $query);
					while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
						$username = $row['username'];
						$password = $row['password'];
						$userType = $row['tipo'];
						echo '<tr><td>'.$username.'</td><td>'.$password.'</td><td>'.$userType.'</td>';
						if($username == "ROOT")
							echo '<td colspan="2">Non è possibile modificare l\'utente ROOT</td></tr>';
						else
							echo '<td onclick="setUser(\''.$username.'\'); updateStatus(\'edit\')"><div class="updater">Modifica</div></td><td onclick="setUser(\''.$username.'\'); updateStatus(\'delete\')"><div class="updater">Elimina</div></td></tr>';
					}
					echo '</tbody></table>';
					echo '<div class="form-field"><input type="button" value="aggiungi un utente" onclick="updateStatus(\'create\')"/></div>';
					echo '<div class="form-field"><input type="button" value="torna al menu\'" onclick="updateStatus(\'menu\')"/></div>';
					break;
				case 'create':
					echo '<h4>Aggiungi utente</h4>';
					echo '<div class="form-field"><input type="text" name="new-username" placeholder="username" required/></div>';
					echo '<div class="form-field"><input type="text" name="new-password" placeholder="password" required/></div>';
					echo '<p>Tipo utente</p>';
					echo '<select name="new-user-type" id="user-type">';
					echo '<option value="U">Utente</option>';
					echo '<option value="A">Amministratore</option>';
					echo '</select>';
					echo "<div><p class='error'>".$newUserError."</p></div>";
					echo '<div class="form-field"><input type="submit" value="crea utente" onclick="updateStatus(\'createuser\')"/></div>';
					echo '<div class="form-field"><input type="button" value="torna indietro" onclick="updateStatus(\'read\')"/></div>';
					break;
				case 'created':
					echo '<h4>Aggiungi utente</h4>';
					echo $newUserMessage;
					echo '<div class="form-field"><input type="button" value="torna al menu\'" onclick="updateStatus(\'menu\')"/></div>';
					break;
				case 'edit':
					echo '<h4>Modifica utente</h4>';
					if ($userSelected == $_SESSION["username"])
						echo "<div><p class='error'>Impossibile modificare se stessi</p></div>";
					else{
						$query = 'SELECT * FROM utenti WHERE username="'.$userSelected.'";';
						$result = mysqli_query($mysqli, $query);
						if(mysqli_num_rows($result)>0){  
							$row = mysqli_fetch_assoc($result);
							$username = $row['username'];
							$password = $row['password'];
							$userType = $row['tipo'];
						}
						echo '<div class="form-field"><input type="text" name="edit-username" value="'.$username.'" disabled/></div>';
						echo '<div class="form-field"><input type="text" name="edit-password" value="'.$password.'" required/></div>';
						echo '<p>Tipo utente</p>';
						echo '<select name="edit-user-type">';
						echo '<option value="U"'.($userType == "U" ? ' selected' : '').'>Utente</option>';
						echo '<option value="A"'.($userType == "A" ? ' selected' : '').'>Amministratore</option>';
						echo '</select>';
						echo '<div class="form-field"><input type="submit" value="conferma modifiche" onclick="setUser(\''.$userSelected.'\'); updateStatus(\'edited\')"/></div>';
					}
					echo '<div class="form-field"><input type="button" value="torna indietro" onclick="updateStatus(\'read\')"/></div>';
					break;
				case 'edited':
					echo '<h4>Modifica Utente</h4>';
					echo '<p>'.$userSelected.' è stato modificato</p>';
					$editUsername = $userSelected;
					$editPassword = strtoupper($_POST["edit-password"]);
					$editUserType = strtoupper($_POST["edit-user-type"]);					
					$query = 'UPDATE utenti SET username="'.$editUsername.'", password="'.$editPassword.'", tipo="'.$editUserType.'" WHERE username="'.$userSelected.'";';
					$result = mysqli_query($mysqli, $query);
					// echo $query;
					echo '<div class="form-field"><input type="button" value="torna al menu\'" onclick="updateStatus(\'menu\')"/></div>';
					break;				
				case 'delete':
					echo '<h4>Elimina utente</h4>';
					if ($userSelected == $_SESSION["username"])
						echo "<div><p class='error'>Impossibile eliminare se stessi</p></div>";
					else{
						echo '<p>'.$userSelected.' è stato eliminato</p>';
						$query = 'DELETE FROM utenti WHERE username="'.$userSelected.'";';
						$result = mysqli_query($mysqli, $query);
						// echo $query;
					}
					echo '<div class="form-field"><input type="button" value="torna indietro" onclick="updateStatus(\'read\')"/></div>';
					break;
			}
		?>
		<form>
	</div>
	</body>
</html>