<?php

use core\Route;

Route::add('/', 'HomeController@test');

Route::add('/second', 'SecondController@index');