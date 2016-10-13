<?php
/**
 * @author Markus Möller
 */
class SegmentDisplay 
{
	private $pins = array();
	
	public function __construct($pinA, $pinB, $pinC, $pinD, $pinE, $pinF, $pinG, $pinDP)
	{
		$this->pins = array($pinA, $pinB, $pinC, $pinD, $pinE, $pinF, $pinG, $pinDP);
		foreach($this->getUsedGpios() as $gpio)
		{
			Gpio::setup($gpio, Gpio::MODE_OUT);
		}
	}
	
	public function getUsedGpios()
	{
		return $this->pins;
	}
	
	public function showChar($char)
	{
		$segments = self::getSegmentsForChar($char);
		foreach($this->pins as $i => $pin)
		{
			Gpio::setGpio($pin, $segments[$i]);
		}
	}
	
	public static function getSegmentsForChar($char)
	{
		/*
				  -0A-
				 |    |
		 		5F    1B
		 		 |	  |
				  -6G-
				 |	  |
		 		4E    2C
		 		 |    |
				  -3D-       [7DP]
		*/
		$segments = array(
			0 => array(1, 1, 1, 1, 1, 1, 0, 0),
			1 => array(0, 1, 1, 0, 0, 0, 0, 0),
			2 => array(1, 1, 0, 1, 1, 0, 1, 0),
			3 => array(1, 1, 1, 1, 0, 0, 1, 0),
			4 => array(0, 1, 1, 0, 0, 1, 1, 0),
			5 => array(1, 0, 1, 1, 0, 1, 1, 0),
			6 => array(1, 0, 1, 1, 1, 1, 1, 0),
			7 => array(1, 1, 1, 0, 0, 0, 0, 0),
			8 => array(1, 1, 1, 1, 1, 1, 1, 0),
			9 => array(1, 1, 1, 1, 0, 1, 1, 0),
			
			"" => array(0, 0, 0, 0, 0, 0, 0, 0),
			"a" => array(1, 1, 1, 0, 1, 1, 1, 0),
			"b" => array(0, 0, 1, 1, 1, 1, 1, 0),
			"c" => array(1, 0, 0, 1, 1, 1, 0, 0),
			"d" => array(0, 1, 1, 1, 1, 0, 1, 0),
			"e" => array(1, 0, 0, 1, 1, 1, 1, 0),
			"f" => array(1, 0, 0, 0, 1, 1, 1, 0),
			"g" => array(1, 0, 1, 1, 1, 1, 0, 0),
			"h" => array(0, 0, 1, 0, 1, 1, 1, 0),
			"i" => array(0, 0, 0, 0, 1, 1, 0, 0),
			"j" => array(0, 1, 1, 1, 0, 0, 0, 0),
			"k" => array(0, 1, 1, 0, 1, 1, 1, 0),  // !
			"l" => array(0, 0, 0, 1, 1, 1, 0, 0),
			"m" => array(0, 0, 0, 0, 0, 0, 0, 0),  // !
			"n" => array(1, 1, 1, 0, 1, 1, 0, 0),  
			"o" => array(0, 0, 1, 1, 1, 0, 1, 0),
			"p" => array(1, 1, 0, 0, 1, 1, 1, 0),
			"q" => array(1, 1, 1, 0, 0, 1, 1, 0),
			"r" => array(0, 0, 0, 0, 1, 0, 1, 0),
			"s" => array(1, 0, 1, 1, 0, 1, 1, 0),   // = 5
			"t" => array(0, 0, 0, 1, 1, 1, 1, 0),
			"u" => array(0, 0, 1, 1, 1, 0, 0, 0),
			"v" => array(0, 0, 0, 0, 0, 0, 0, 0),   // !
			"w" => array(0, 0, 0, 0, 0, 0, 0, 0),   // !
			"x" => array(0, 0, 0, 0, 0, 0, 0, 0),   // !
			"y" => array(0, 1, 0, 0, 1, 1, 1, 0),
			"z" => array(1, 1, 0, 1, 1, 0, 1, 0),   // = 2
			"." => array(0, 0, 0, 0, 0, 0, 0, 1),
			"-" => array(0, 0, 0, 0, 0, 0, 1, 0),
		);
		
		return isset($segments[strtolower(trim($char))]) ? $segments[strtolower(trim($char))] : $segments[""];
	}
}

