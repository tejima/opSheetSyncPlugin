<?php

include(dirname(__FILE__).'/../bootstrap/Doctrine.php');
require_once dirname(__FILE__).'/../bootstrap/unit.php';
$t = new lime_test();
$t->comment("lime drive");

SheetSyncUtil::updatenumber();

