<?php
require_once '../lib/StepMotor28byj48.class.php';
require_once '../lib/Gpio.class.php';

$stepMotor = new StepMotor28byj48(14, 15, 18, 23);

$stepMotor->turnDegreesLeft(90, 5000);
$stepMotor->turnRightLeft(180, 5000);

$stepMotor->turnDegreesLeft(360, 1000);
$stepMotor->turnRightLeft(360, 50000);