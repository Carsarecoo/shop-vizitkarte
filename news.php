<?php
/*
 * Jaunumu saraksts
 */

require_once('class.mdb.php');
require_once('news-config.php');

//izveido savienojumu ar datubāzi
$db = new mdb($db_user, $db_pswd, $db_base, $db_host);

?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
	<title>Jaunumi</title>
	<link rel="stylesheet" type="text/css" href="news.css" />
</head>
<body>

<h1><center>Jaunumi</center></h1>

<?php
	//saraksts ar rakstiem
	$news = $db->get_results("SELECT * FROM `news` ORDER BY `created` DESC LIMIT 10");
	if(!empty($news)) {
		echo '<ul id="news">';
		foreach($news as $article) {

			//datuma formāts, maināms ar http://php.net/manual/en/function.date.php norādītajiem parametriem
			$article->created = date('Y.m.d H:i', strtotime($article->created));

			echo '<li>';
			echo '	<h2>'.$article->title.'</h2>';
			echo '	<div class="news-date">'.$article->created.'</div>';
			echo '	<div class="news-content">'.$article->content.'</div>';
			echo '</li>';
		}
		echo '</ul>';
	}
?>

</body>
</html>
