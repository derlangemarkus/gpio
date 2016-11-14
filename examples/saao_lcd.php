<?php
require_once '../lib/Lcd.class.php';
require_once '../lib/Gpio.class.php';

$lcd = new Lcd(7, 8, 25, 24, 23, 14);
$lcd->init();

while(true)
{
	$donations = readDonations("https://secure.fundraisingbox.com/pages_render/7ygs3o1j/bikeclimbfly.json");
	foreach($donations as $donation)
	{
		$amount = number_format($donation->amount, 2, ",", ".");
		$text = str_pad($donation->public_name, 20-mb_strlen($amount), " ");
		$text .= $amount;
		$text .= str_replace(array('\n', '\r'), array("", ""), $donation->public_message);
		$lcd->outputMultilineText($text);
		sleep(3);
	}
}

function readDonations($url) {
	$json = file_get_contents($url);
	$fundraisingPage = json_decode($json);
	return $fundraisingPage->donations;
}