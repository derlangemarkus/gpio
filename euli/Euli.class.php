<?php
/**
 * @author Markus Möller
 */
class Euli
{
	const EYES_OPEN = 30;
	const EYES_CLOSE = 14;
	
	private $eyesStatus;
	private $eyesPin;
	private $lcd;
	
	public function __construct($eyesPin, $pinRs, $pinE, $pinD4, $pinD5, $pinD6, $pinD7, $lcdWith = 16)
	{
		$this->eyesPin = $eyesPin;
		Gpio::setupPwm($eyesPin);
		
		$this->lcd = new Lcd($pinRs, $pinE, $pinD4, $pinD5, $pinD6, $pinD7, $lcdWith);
	}
	
	public function init()
	{
		$this->lcd->init();
		$this->closeEyes(true);
	}
	
	public function openEyes($forceMovement = false)
	{
		$this->moveEyes(self::EYES_OPEN, $forceMovement);
	}
	
	public function closeEyes($forceMovement = false)
	{
		$this->moveEyes(self::EYES_CLOSE, $forceMovement);
	}
	
	public function outputText($text, $line)
	{
		$this->lcd->outputText($text, $line);
	}
	
	private function moveEyes($status, $forceMovement)
	{
		if($this->eyesStatus != $status || $forceMovement)
		{
			Gpio::setPwm($this->eyesPin, $status);
			usleep(400000);
			$this->eyesStatus = $status;
			Gpio::setPwm($this->eyesPin, 0);
		}
	}
}
