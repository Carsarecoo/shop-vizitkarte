<?php
/*
 * Jaunumu saraksts
 */

require_once('class.mdb.php');
require_once('news-config.php');

//izveido savienojumu ar datubāzi
$db = new mdb($db_user, $db_pswd, $db_base, $db_host);

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Sakums</title>
  <meta name="description" content="Description of your site goes here">
  <meta name="keywords" content="keyword1, keyword2, keyword3">
  <link href="css/style.css" rel="stylesheet" type="text/css">
  <link href="css/news.css" rel="stylesheet" type="text/css">
  <link href="css/drop_menu.css" rel="stylesheet" type="text/css">
  </head>
<body>
<div class="main">
<div class="page">
<div class="header">
<h1>Nosaukums</h1>
</div>
<div class="content">
<div id="menu">
    <ul>
        <li><a href="index.php">Sākums</a>
      </li>
  </ul>
    <ul>
        <li><a href="galeries.php">Galerijas</a>
        </li>
    </ul>
    <ul>
        <li><a href="">Kontakti</a>
            <ul>
                <li><a href="">Atrašanās vieta</a></li>
                <li><a href="">No news</a></li>
                <li><a href="">No news</a></li>
            </ul>
        </li>
    </ul>
</div>
<div class="left">
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

</div>
<div class="right">
<h2>Twitter vai facebook</h2>
</div>
</div>
<a href="auth.php">
<img src="images/footnote.gif" class="copyright" alt="CP Pannel"></a>
<div class="footer">&reg; David &nbsp; &copy; Copyright 2014.&nbsp; <a
 class="footer-link" target="_blank" href="auth.php">CP</a></div>
</div>
</div>
</body>
</html>
