<?php
require_once '../lib/StepMotor28byj48.class.php';
require_once '../lib/Gpio.class.php';

$stepMotor = new StepMotor28byj48(14, 15, 18, 23);

$stepMotor->turnDegreesLeft(180, 500);
$stepMotor->turnDegreesRight(90, 500);

$stepMotor->turnDegreesLeft(360, 1000);
$stepMotor->turnDegreesRight(360, 500);

$stepMotor->turnDegreesLeft(90, 10000);

$stepMotor->stop();