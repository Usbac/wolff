<?php
include('config.php');
include('start.php');
Start::loadSystemFiles();

chdir(dirname(__FILE__));
session_start();

$start = new Start();
$start->checkInstallation();
$start->begin();