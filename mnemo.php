#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use Ealore\Mnemo\Command\MnemoCommand;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new MnemoCommand('generate'));
$application->setDefaultCommand('generate');

$application->run();