<?php

declare(strict_types = 1);

require_once('../constants.php');
require_once(VENDOR_PATH.'compose.php');

$csvFiles = $csvParse(FILES_PATH);

$csvTables = '';

foreach ($csvFiles as $csvName => $csvData) {
  $csvTables .= $generateCsvTable($csvName, $csvData);
}

$includesTop = $includePublic(css: 'style.css', js: 'main.js', favicon: 'favicon.ico');
error_log($includesTop);

require_once(VIEWS_PATH.'main.php');
