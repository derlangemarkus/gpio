<?php
/**
 * @author Markus Möller
 */
class Gpio
{
	const MODE_IN = "in";
	const MODE_OUT = "out";
	
	public static function setup($index, $mode)
	{
		file_put_contents("/sys/class/gpio/unexport", $index);
		file_put_contents("/sys/class/gpio/export", $index);
		file_put_contents("/sys/class/gpio/gpio".$index."/direction", $mode);
	}
	
	public static function setGpio($index, $value)
	{
		file_put_contents("/sys/class/gpio/gpio".$index."/value", ($value ? 1 : 0));
	}
}
