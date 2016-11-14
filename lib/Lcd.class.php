<?php
/**
 * @author Markus Möller
 */
class Lcd
{
	private $pinRs;
	private $pinE;
	private $pinD4;
	private $pinD5;
	private $pinD6;
	private $pinD7;
	
	private $lcdWidth;
	
	const MODE_CMD = 0;
	const MODE_CHR = 1;
	
	const LINE1 = 0x80;
	const LINE2 = 0xC0;
	const LINE3 = 0x94;
	const LINE4 = 0xD4;
	const SET_CGADR = 0x40;
	
	const E_PULSE = 50;  // Microseconds 
	const E_DELAY = 300;  // Microseconds 
	
	public function __construct($pinRs, $pinE, $pinD4, $pinD5, $pinD6, $pinD7, $lcdWith = 20)
	{
		$this->pinRs = $pinRs;
		$this->pinE = $pinE;
		$this->pinD4 = $pinD4;
		$this->pinD5 = $pinD5;
		$this->pinD6 = $pinD6;
		$this->pinD7 = $pinD7;
		$this->lcdWidth = $lcdWith;
		
		foreach($this->getUsedGpios() as $gpio)
		{
			Gpio::setup($gpio, Gpio::MODE_OUT);
		}
	}
	
	public function init() 
	{
		$this->setByte(0x33, self::MODE_CMD); # 110011 Initialise
		$this->setByte(0x32, self::MODE_CMD); # 110010 Initialise
		$this->setByte(0x06, self::MODE_CMD); # 000110 Cursor move direction
		$this->setByte(0x0C, self::MODE_CMD); # 001100 Display On, Cursor Off, Blink Off
		$this->setByte(0x28, self::MODE_CMD); # 101000 Data length, number of lines, font size
		$this->clearDisplay();
	}
	
	public function clearDisplay()
	{
		$this->setByte(0x01, self::MODE_CMD); # 000001 Clear display
		usleep(self::E_DELAY);
	}
	
	public function outputMultilineText($text)
	{
		$this->outputText(mb_substr($text, 0, $this->lcdWidth), Lcd::LINE1);
		$this->outputText(trim(mb_substr($text, 1*$this->lcdWidth, $this->lcdWidth)), Lcd::LINE2);
		$this->outputText(trim(mb_substr($text, 2*$this->lcdWidth, $this->lcdWidth)), Lcd::LINE3);
		$this->outputText(trim(mb_substr($text, 3*$this->lcdWidth, $this->lcdWidth)), Lcd::LINE4);
	}
	
	public function outputText($text, $line)
	{
		// Select line
		if(!in_array($line, array(self::LINE1, self::LINE2, self::LINE3, self::LINE4)))
		{
			throw new Exception("Invalid line ".$line.". Please use RAM address.");
		}
		$this->setByte($line, self::MODE_CMD);

		// Fill text with spaces to delete the rest of the line and make sure that the text isn't longer than the display
		// Please note that we fill lcdWidth*2. This is because some umlauts can exists of 2 bytes.
		$text = mb_substr(str_pad($text, $this->lcdWidth*2, " "), 0, $this->lcdWidth);
		for($i = 0; $i < $this->lcdWidth; $i++)
		{
			// Set every single byte
			$this->setByte($this->getByteForChar(mb_substr($text, $i, 1)), self::MODE_CHR);
		}
	}

	public function generateChar($index, $data)
	{
		$this->setByte(self::SET_CGADR|(intval($index)<<3), self::MODE_CMD);
		// http://www.quinapalus.com/hd44780udg.html
		for ($i=0; $i<sizeof($data); $i++)
		{
			$this->setByte($data[$i], self::MODE_CHR);
		}
	}
	
	public function getUsedGpios()
	{
		return array($this->pinRs, $this->pinE, $this->pinD4, $this->pinD5, $this->pinD6, $this->pinD7);
	}
	
	private function setByte($byte, $mode)
	{
		$pinD4 = ($byte & 0x10) == 0x10 ? 1 : 0;
		$pinD5 = ($byte & 0x20) == 0x20 ? 1 : 0;
		$pinD6 = ($byte & 0x40) == 0x40 ? 1 : 0;
		$pinD7 = ($byte & 0x80) == 0x80 ? 1 : 0;
		$this->sendData($pinD4, $pinD5, $pinD6, $pinD7, $mode);
		
		$pinD4 = ($byte & 0x01) == 0x01 ? 1 : 0;
		$pinD5 = ($byte & 0x02) == 0x02 ? 1 : 0;
		$pinD6 = ($byte & 0x04) == 0x04 ? 1 : 0;
		$pinD7 = ($byte & 0x08) == 0x08 ? 1 : 0;
		$this->sendData($pinD4, $pinD5, $pinD6, $pinD7, $mode);
	}
	
	protected function sendData($pinD4, $pinD5, $pinD6, $pinD7, $pinRs)
	{
		Gpio::setGpio($this->pinRs, $pinRs);
		
		Gpio::setGpio($this->pinD4, $pinD4);
		Gpio::setGpio($this->pinD5, $pinD5);
		Gpio::setGpio($this->pinD6, $pinD6);
		Gpio::setGpio($this->pinD7, $pinD7);
		
		usleep(self::E_DELAY);
		Gpio::setGpio($this->pinE, 1);
		usleep(self::E_PULSE);
		Gpio::setGpio($this->pinE, 0);
		usleep(self::E_DELAY);
	}
	
	private function getByteForChar($char)
	{
//		$ownChars = array("\0", "\1", "\2", "\3", "\4", "\5", "\6", "\7");
//		if(isset($ownChars[$char]))
//		{
//			return array_search($char, $ownChars);
//		}
		
		$umlauts = array(
			"ü" => "\365",
			"ö" => "\357",
			"ä" => "\341",
			"ß" => "\342"
		);
		
		return isset($umlauts[mb_strtolower($char)]) ? ord($umlauts[mb_strtolower($char)]) : ord($char);
	}
}
