<?php
/*
 * Jaunumu administrēšana
 *
 * (c) 2013, Maadinsh - http://code.exs.lv/
 *
 * Šim failam NAV jābūt publiski pieejamam
 *
 * Ja tev jau ir izveidota lietotāju ielogošanās sistēma, izmantot to,
 * lai ļautu piekļūt tikai lietotājiem, kuriem atļauts izveidot aptaujas.
 * Ja nav, vari ļaut piekļūt tikai no savas IP adreses, ieliekot sākumā ko šādu:
 *
 * if($_SERVER['REMOTE_ADDR'] != '123.123.TAVA.IP') {
 * 	die('Pieeja liegta!');
 * }
 *
 */

require_once('class.mdb.php');
require_once('news-config.php');

//izveido savienojumu ar datubāzi
$db = new mdb($db_user, $db_pswd, $db_base, $db_host);

$title = '';
$content = '';
$status = '';

//ja tie padots ?edit=X parametrs, ielasam raksta saturu labošanai
if(isset($_GET['edit'])) {
	
	$id = (int)$_GET['edit'];

	//parbaudam vai tāds raksts eksistē
	$article = $db->get_row("SELECT * FROM `news` WHERE `id` = '$id'");

	//raksts nav atrasts
	if(empty($article)) {
		$status = '<p class="error">Pieprasītais raksts neeksistē!</p>';

	//pieprasīts dzēst rakstu
	} elseif(isset($_GET['delete'])) {		
		$db->query("DELETE FROM `news` WHERE `id` = '$id'");
		$status = '<p class="success">Raksts izdzēsts!</p>';

	} else {

		//ja pieprasījums nāk no formas submit pogas, 
		if(isset($_POST['title'])) {

			$title = htmlspecialchars($_POST['title']);
			$content = $_POST['content'];

			//sagatavojam datus rakstīšanai db
			$title_escaped = $db->escape($title);
			$content_escaped = $db->escape($content);

			//izmaina ierakstu datubāzē
			$db->query("UPDATE `news` SET `title` = '$title_escaped', `content` = '$content_escaped', `modified` = NOW() WHERE `id` = '$id'");
			$status = '<p class="success">Izmaiņas saglabātas!</p>';
		
		//nav nospiests submit, vienkārši rādam šī raksta saturu formā
		} else {
			$title = htmlspecialchars($article->title);
			$content = $article->content;
		}

	}

//jauna raksta veidošana
} elseif(isset($_POST['title'])) {

	//sagatavojam datus rakstīšanai db
	$title_escaped = $db->escape(htmlspecialchars($_POST['title']));
	$content_escaped = $db->escape($_POST['content']);

	//izveido jaunu ierakstu datubāzē
	$db->query("INSERT INTO `news` (`title`, `content`, `created`, `modified`) VALUES ('$title_escaped', '$content_escaped', NOW(), NOW())");
	$status = '<p class="success">Izveidots jauns raksts!</p>';
}




?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
	<title>CP</title>
	<link rel="stylesheet" type="text/css" href="news-admin.css" />
</head>
<body>

	<div id="wrapper">
		<h1>Jaunumu administrācija</h1>

		<?php echo $status; ?>

		<form class="form" action="" method="post">
			<fieldset>
				<p>
					<label for="title">Nosaukums</label><br />
					<input type="text" name="title" id="title" value="<?php echo $title; ?>" />
				</p>
				<p>
					<label for="content">Raksta saturs</label><br />
					<textarea name="content" cols="30" rows="5"><?php echo htmlspecialchars($content); ?></textarea>
				</p>
				<p>
					<input class="button" type="submit" value="Saglabāt" />
				</p>
			</fieldset>
		</form>

		<h2>Visi raksti (<a href="?new">+</a>)</h2>

		<?php
			//saraksts ar jau esošajiem rakstiem
			$news = $db->get_results("SELECT * FROM `news` ORDER BY `created` DESC");
			if(!empty($news)) {
				foreach($news as $article) {
					echo '<strong>'.$article->title.'</strong> [<a href="?edit='.$article->id.'">labot</a>] [<a href="?edit='.$article->id.'&amp;delete=true">dzēst</a>]<br />';
				}
			}
		?>

	</div>
</body>

</html>
