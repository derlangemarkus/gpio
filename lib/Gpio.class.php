<?php
/**
 * @author Markus Möller
 */
class Gpio
{
	const MODE_IN = "in";
	const MODE_OUT = "out";
	const MODE_PWM = "pwm";
	
	public static function setup($pin, $mode)
	{
		if(!self::isValidMode($mode))
		{
			throw new Exception("Invalid mode ".$mode);
		}
		file_put_contents("/sys/class/gpio/unexport", $pin);
		file_put_contents("/sys/class/gpio/export", $pin);
		file_put_contents("/sys/class/gpio/gpio".$pin."/direction", $mode);
	}
	
	public static function setGpio($pin, $value)
	{
		file_put_contents("/sys/class/gpio/gpio".$pin."/value", ($value ? 1 : 0));
	}
	
	public static function setupPwm($pin)
	{
		exec("gpio -g mode ".$pin." pwm");
		exec("gpio pwm-ms");
		exec("gpio pwmc 1920");
		exec("gpio pwmr 200");
	}
	
	public static function setPwm($pin, $value)
	{
		exec("gpio -g pwm ".$pin." ".intval($value));
	}
	
	private static function isValidMode($mode)
	{
		return in_array($mode, array(self::MODE_IN, self::MODE_OUT, self::MODE_PWM));
	}
}
