<?php
/**
 * @author Markus Möller
 */
class StepMotor28byj48
{
	private $pinBlue;
	private $pinPink;
	private $pinYellow;
	private $pinOrange;
	
	private $sequences = array(
		array(0, 0, 0, 1),
		array(0, 0, 1, 1),
		array(0, 0, 1, 0),
		array(0, 1, 1, 0),
		array(0, 1, 0, 0),
		array(1, 1, 0, 0),
		array(1, 0, 0, 0),
		array(1, 0, 0, 1)
	);
	
	public function __construct($pinBlue, $pinPink, $pinYellow, $pinOrange)
	{
		$this->pinBlue = $pinBlue;
		$this->pinPink = $pinPink;
		$this->pinYellow = $pinYellow;
		$this->pinOrange = $pinOrange;
		
		foreach($this->getUsedGpios() as $gpio)
		{
			Gpio::setup($gpio, Gpio::MODE_OUT);
		}
		$this->stop();
	}
	
	public function getUsedGpios()
	{
		return array($this->pinBlue, $this->pinPink, $this->pinYellow, $this->pinOrange);
	}

	public function turnDegreesRight($degrees, $delay)
	{
		$this->turnStepsRight(4096/$degrees, $delay);
	}
	
	public function turnStepsRight($steps, $delay)
	{
		$this->turn($this->sequences, $steps, $delay);
	}
	
	public function turnDegreesLeft($degrees, $delay)
	{
		$this->turnStepsLeft(4096/$degrees, $delay);
	}
	
	public function turnStepsLeft($steps, $delay)
	{
		$this->turn(array_reverse($this->sequences), $steps, $delay);
	}
	
	public function stop() 
	{
		$this->setBytes(0, 0, 0, 0);
	}
	
	private function turn($sequence, $steps, $delay)
	{
		for($i=0; $i<$steps; $i++)
		{
			$bytes = $sequence[$i%8];
			$this->setBytes($bytes[0], $bytes[1], $bytes[2], $bytes[3]);
			usleep($delay);
		}
	}
	
	private function setBytes($blue, $pink, $yellow, $orange)
	{
		Gpio::setGpio($this->pinBlue, $blue);
		Gpio::setGpio($this->pinPink, $pink);
		Gpio::setGpio($this->pinYellow, $yellow);
		Gpio::setGpio($this->pinOrange, $orange);
	}
}
