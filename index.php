<?php

$f3 = require_once("lib/fatfree/base.php");

$f3->config("config/config.ini");
$f3->config("config/routes.ini");

$f3->run();
