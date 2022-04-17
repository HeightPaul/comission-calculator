<?php

require_once realpath('vendor/autoload.php');

use ComissionCli\ComissionCli;

/**
 * @brief Comission Converter
 */

echo (new ComissionCli($argv[ComissionCli::FILE_ARG_POSITION]))->getResult();