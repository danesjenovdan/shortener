<?php
header('Access-Control-Allow-Origin: *');
require 'config.php';

header('Content-Type: text/plain;charset=UTF-8');

//$url = isset($_GET['url']) ? urldecode(trim($_GET['url'])) : '';

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);



if(isset($_POST['fatmama'])){
	$url = isset($_POST['fatmama']) ? urldecode(trim($_POST['fatmama'])) : '';
}elseif (isset($_GET['fatmama'])) {
	$url = isset($_GET['fatmama']) ? urldecode(trim($_GET['fatmama'])) : '';
}

if (in_array($url, array('', 'about:blank', 'undefined', 'http://localhost/'))) {
	die('Enter a URL.');
}



// If the URL is already a short URL on this domain, don’t re-shorten it
if (strpos($url, SHORT_URL) === 0) {
	die($url);
}

function nextLetter(&$str) {
	$str = ('z' == $str ? 'a' : ++$str);
}

function getNextShortURL($s) {
	$a = str_split($s);
	$c = count($a);
	if (preg_match('/^z*$/', $s)) { // string consists entirely of `z`
		return str_repeat('a', $c + 1);
	}
	while ('z' == $a[--$c]) {
		nextLetter($a[$c]);
	}
	nextLetter($a[$c]);
	return implode($a);
}

$db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
$db->set_charset('utf8mb4');

$url = $db->real_escape_string($url);



$result = $db->query('SELECT slug FROM redirect WHERE url = "' . $url . '" LIMIT 1');
if ($result && $result->num_rows > 0) { // If there’s already a short URL for this URL
	die(SHORT_URL . $result->fetch_object()->slug);
} else {
	$result = $db->query('SELECT slug, url FROM redirect ORDER BY date DESC, slug DESC LIMIT 1');
	if ($result && $result->num_rows > 0) {
		$slug = getNextShortURL($result->fetch_object()->slug);


		if ($db->query('INSERT INTO redirect (slug, url, date, hits) VALUES ("' . $slug . '", "' . $url . '", NOW(), 0)')) {
			header('HTTP/1.1 201 Created');
			echo SHORT_URL . $slug;
			$db->query('OPTIMIZE TABLE `redirect`');
		}else{
			echo mysqli_errno($db);
		}
	}
}
$db->close();

?>