<?php

// include files
require_once __DIR__ . '/defines.php';
require_once SRC . 'Utils.php';
require_once SRC . 'Console.php';
require_once SRC . 'Parser.php';
require_once SRC . 'Application.php';
require_once SRC . 'BookObject.php';
require_once SRC . 'Download.php';
require_once VENDOR . 'autoload.php';

// run
( new \Knigavuhe\Application() )->run();
