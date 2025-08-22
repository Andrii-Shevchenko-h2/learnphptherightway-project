<?php

declare(strict_types = 1);

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;

define('APP_PATH', $root . 'vendor' . DIRECTORY_SEPARATOR);
define('FILES_PATH', $root . 'transaction_files' . DIRECTORY_SEPARATOR);
define('VIEWS_PATH', $root . 'views' . DIRECTORY_SEPARATOR);

/* YOUR CODE (Instructions in README.md) */
require(APP_PATH . 'compose.php');

$csvFiles = $csvParse(FILES_PATH);

$csvTables = '';

foreach ($csvFiles as $csvName => $csvData) {
  $csvTables .= $generateCsvTable($csvName, $csvData);
}

require_once(VIEWS_PATH.'main.php');
