<?php
/**
 * @author Markus Möller
 */
#  lcd_16x2.py
#  16x2 LCD Test Script
#
# Author : Matt Hawkins
# Date   : 06/04/2015
#
# http://www.raspberrypi-spy.co.uk/
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
	
	const E_PULSE = 50;  // Microseconds 
	const E_DELAY = 50;  // Microseconds 
	
	public function __construct($pinRs, $pinE, $pinD4, $pinD5, $pinD6, $pinD7, $lcdWith = 16)
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
		$this->setByte(0x01, self::MODE_CMD); # 000001 Clear display
		usleep(self::E_DELAY);
	}
	
	public function outputText($text, $line)
	{
		// Select line
		if(!in_array($line, array(self::LINE1, self::LINE2)))
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
	
	public function getUsedGpios()
	{
		return array($this->pinRs, $this->pinE, $this->pinD4, $this->pinD5, $this->pinD6, $this->pinD7);
	}
	
	private function setByte($byte, $mode)
	{
		Gpio::setGpio($this->pinRs, $mode);

		// High bits
		Gpio::setGpio($this->pinD4, 0);
		Gpio::setGpio($this->pinD5, 0);
		Gpio::setGpio($this->pinD6, 0);
		Gpio::setGpio($this->pinD7, 0);
		if(($byte & 0x10) == 0x10)
		{
			Gpio::setGpio($this->pinD4, 1);
		}
		if(($byte & 0x20) == 0x20)
		{
			Gpio::setGpio($this->pinD5, 1);
		}
		if(($byte & 0x40) == 0x40)
		{
			Gpio::setGpio($this->pinD6, 1);
		}
		if(($byte & 0x80) == 0x80)
		{
			Gpio::setGpio($this->pinD7, 1);
		}

		// Toggle 'Enable' pin
		$this->toggleEnable();
		
		Gpio::setGpio($this->pinD4, 0);
		Gpio::setGpio($this->pinD5, 0);
		Gpio::setGpio($this->pinD6, 0);
		Gpio::setGpio($this->pinD7, 0);
		if(($byte & 0x01) == 0x01)
		{
			Gpio::setGpio($this->pinD4, 1);
		}
		if(($byte & 0x02) == 0x02)
		{
			Gpio::setGpio($this->pinD5, 1);
		}
		if(($byte & 0x04) == 0x04)
		{
			Gpio::setGpio($this->pinD6, 1);
		}
		if(($byte & 0x08) == 0x08)
		{
			Gpio::setGpio($this->pinD7, 1);
		}

		// Toggle 'Enable' pin
		$this->toggleEnable();
	}
	
	private function toggleEnable()
	{
		usleep(self::E_DELAY);
		Gpio::setGpio($this->pinE, 1);
		usleep(self::E_PULSE);
		Gpio::setGpio($this->pinE, 0);
		usleep(self::E_DELAY);
	}
	
	private function getByteForChar($char)
	{
		$umlauts = array(
			"ü" => "\365",
			"ö" => "\357",
			"ä" => "\341",
			"ß" => "\342"
		);
		
		return isset($umlauts[mb_strtolower($char)]) ? ord($umlauts[mb_strtolower($char)]) : ord($char);
	}
}
