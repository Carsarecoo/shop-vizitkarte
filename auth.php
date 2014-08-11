<?php
/*
 * http://coding.lv/read/vienkarss-php-login
 */

################### KONFIGURĀCIJA ########################

/*
 * lietotāji un paroles, ar ko var ielogoties
 */
$auth_users = array(
	'admin' => 'parole',
	'test' => 'test123'
);

/*
 * kur redirektēt pēc izlogošanās?
 */
$auth_redirect = '/';

/*
 * lai izlogotu lietotāju, jebkurā vietā ieliec linku:
 *
 *      <a href="?logout">Iziet</a>
 *
 * lai redzētu lietotājvārdu aktīvajam lietotājam, izmanto $user mainīgo:
 *
 *      <?php echo $user; ?>
 *
 */
##########################################################

session_start();

if (isset($_GET['logout'])) {
	$_SESSION['SIMPLEAUTH'] = null;
	header('Location: ' . $auth_redirect);
	exit;
}

if (empty($_SESSION['SIMPLEAUTH'])) {
	$error = null;
	if (
			isset($_POST['usr']) &&
			isset($_POST['pwd']) &&
			!empty($auth_users[(string) $_POST['usr']]) &&
			$auth_users[$_POST['usr']] === $_POST['pwd']
	) {
		$user = $_SESSION['SIMPLEAUTH'] = $_POST['usr'];
	} else {
		?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
			"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="lv" lang="lv">
			<head>
				<meta http-equiv="content-type" content="text/html; charset=utf-8" />

				<title>Ielogojies, lai piekļūtu šai lapai!</title>

			</head>
			<body>
				<form id="login" action="" method="post">
					<fieldset>
						<?php
						if (isset($_POST['usr'])) {
							echo '<p class="notice">Nepareizs lietotājvārds vai parole!</p>';
						}
						?>
						<label for="usr">Lietotājs:</label><br />
						<input type="text" name="usr" id="usr" /><br />
						<label for="pwd">Parole:</label><br />
						<input type="password" name="pwd" id="pwd" /><br />
						<input type="submit" value="Ielogoties" />
					</fieldset>
				</form>
			</body>
		</html>
		<?php
		exit;
	}
} else {
	$user = $_SESSION['SIMPLEAUTH'];
}

