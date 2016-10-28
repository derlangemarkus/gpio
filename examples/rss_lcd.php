<?php
require_once '../lib/Lcd.class.php';
require_once '../lib/Gpio.class.php';

$lcd = new Lcd(7, 8, 25, 24, 23, 14);
$lcd->init();
$counter = 0;

while(true)
{
	$rssEntries = readRSSFeed("http://www.tagesschau.de/xml/rss2");
	foreach($rssEntries as $rssEntry)
	{
		$lcd->outputText(mb_substr($rssEntry[0], 0, 16), Lcd::LINE1);
		$lcd->outputText(trim(mb_substr($rssEntry[0], 16)), Lcd::LINE2);

		sleep(3);
	}
}

function readRSSFeed($url) {
	$feed = file_get_contents($url);
	if(!strlen($feed)) {
		return array();
	}
	$result = array();
	$data = new SimpleXMLElement($feed);
	foreach($data->channel->item as $item) {
		$result[] = array($item->title, $item->description);
	}
	return $result;
}