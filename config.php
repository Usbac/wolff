<?php

if (getenv('APP_ENV') === 'local') {
    $dotenv = Dotenv\Dotenv::create(__DIR__);
    $dotenv->load();
} else {
    // Load .env for other environment under assumption
    // that the file is called .env.{environment}
    $dotenv = Dotenv\Dotenv::create(__DIR__, '.env.'.getenv('APP_ENV'));
    $dotenv->load();
}

$dotenv->required(['SERVER', 'LANGUAGE', 'APP']);