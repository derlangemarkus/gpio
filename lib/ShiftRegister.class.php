<?php
/**
 * @author Markus Möller
 */
class ShiftRegister
{
	private $dataPin;
	private $shiftPin;
	private $storePin;
	
	public function __construct($dataPin, $shiftPin, $storePin)
	{
		$this->dataPin = $dataPin;
		$this->shiftPin = $shiftPin;
		$this->storePin = $storePin;
		
		Gpio::setup($dataPin, Gpio::MODE_OUT);
		Gpio::setup($shiftPin, Gpio::MODE_OUT);
		Gpio::setup($storePin, Gpio::MODE_OUT);
	}
	
	public function setValues($values)
	{
		Gpio::setGpio($this->storePin, 0);
		
		foreach($values as $value)
		{
			Gpio::setGpio($this->shiftPin, 0);
			Gpio::setGpio($this->dataPin, $value);
			Gpio::setGpio($this->shiftPin, 1);
		}
		
		Gpio::setGpio($this->storePin, 1);
	}
}
