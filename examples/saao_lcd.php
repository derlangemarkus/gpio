<?php
require_once '../lib/Lcd.class.php';
require_once '../lib/Gpio.class.php';

$lcd = new Lcd(7, 8, 25, 24, 23, 18);
$lcd->init();

while(true)
{
	$donations = readDonations("https://secure.fundraisingbox.com/pages_render/7ygs3o1j/bikeclimbfly.json");
	foreach($donations as $donation)
	{
		$amount = number_format($donation->amount, 2, ",", ".");
		$line1 = $donation->public_name;
		$line1 = str_pad($line1, 16-mb_strlen($amount), " ");
		$line1 .= $amount;
		$lcd->outputText($line1, Lcd::LINE1);
		
		$message = str_replace(array('\n\r'), array("", ""), $donation->public_message);
		$lcd->outputText($message, Lcd::LINE2);
		usleep(500000);
		for($i=0; $i<mb_strlen($message)-15; $i++)
		{
			$lcd->outputText(mb_substr($message, $i), Lcd::LINE2);
			usleep(200000);
		}

		sleep(1);
	}
}

function readDonations($url) {
	$json = file_get_contents($url);
	$fundraisingPage = json_decode($json);
	return $fundraisingPage->donations;
}