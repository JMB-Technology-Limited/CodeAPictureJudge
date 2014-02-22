<?php

require __DIR__.'/../../../app/php/bootstrap.php';
/**
 * @link https://github.com/JMB-Technology-Limited/CodeAPictureJudge
 * @license https://raw.github.com/JMB-Technology-Limited/CodeAPictureJudge/master/LICENSE.txt BSD
 */

$siteid = $_GET['siteid'];
$site = $app['siterepository']->loadSiteById($siteid);
if (!$site) {
	die("404");
}
if ($site->getType() != 'versus'){
	die("404 wrong type");
}

header("Access-Control-Allow-Origin: *");

$indata = array_merge($_POST,$_GET);

$order = isset($indata['order']) && $indata['order'] ? $indata['order'] : "DESC";
$limit = isset($indata['limit']) && $indata['limit'] ? $indata['limit'] : 30;
$threshhold = isset($indata['threshhold']) && $indata['threshhold'] ? $indata['threshhold'] : 1;


$pictures = $app['picturerepository']->getChartForTypeVersus($site, $threshhold, $order, $limit);

$out = array();
foreach($pictures as $picturedata) {
	$out[] = array(
		'picture'=>array(
			'id'=>$picturedata['picture']->getId(),
			'source_url'=>$picturedata['picture']->getSourceUrl(),
			'source_text'=>$picturedata['picture']->getSourceText(),
			'url_full_size'=>$app['webenvironment']->getSiteRoot().'pictures/full/'.$picturedata['picture']->getFilename(),
		),
		'votes_won'=>$picturedata['votes_won'],
		'votes_total'=>$picturedata['votes_total']
	);
}

print json_encode(array('pictures'=>$out));








