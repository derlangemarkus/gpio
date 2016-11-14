<?php
/**
 * @author Markus Möller
 */
class ShiftRegisterLcd extends Lcd
{
	const DONT_CARE = 0; // Just a way to show that this pin is not used
	
	private $shiftRegister;
	private $registerOut4;
	private $registerOut5;
	private $registerOut6;
	private $registerOut7;
	private $registerOutE;
	private $registerOutRs;
	
	public function __construct($dataPin, $shiftPin, $storePin, $registerOutRs, $registerOutE, $registerOut4, $registerOut5, $registerOut6, $registerOut7, $lcdWith = 20)
	{
		$this->shiftRegister = new ShiftRegister($dataPin, $shiftPin, $storePin);
		$this->registerOutRs = $registerOutRs;
		$this->registerOutE = $registerOutE;
		$this->registerOut4 = $registerOut4;
		$this->registerOut5 = $registerOut5;
		$this->registerOut6 = $registerOut6;
		$this->registerOut7 = $registerOut7;
		parent::__construct(null, null, null, null, null, null, $lcdWith);
	}
	
	public function getUsedGpios()
	{
		return $this->shiftRegister->getUsedGpios();
	}
	
	protected function sendData($pinD4, $pinD5, $pinD6, $pinD7, $pinRs)
	{
		$data = array_fill(0, 8, self::DONT_CARE);
		$data[$this->registerOutRs] = $pinRs;
		$data[$this->registerOut4] = $pinD4;
		$data[$this->registerOut5] = $pinD5;
		$data[$this->registerOut6] = $pinD6;
		$data[$this->registerOut7] = $pinD7;
		$data[$this->registerOutE] = 1;
		
		$this->shiftRegister->setValues($data);
		usleep(self::E_PULSE);
		
		$data[$this->registerOutE] = 0;
		$this->shiftRegister->setValues($data);
		usleep(self::E_DELAY);
	}
}
