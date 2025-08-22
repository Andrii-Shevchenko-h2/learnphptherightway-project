<?php

declare(strict_types = 1);

// assumes all files are CSV, no error-handling
$csvParse = function(string $dir): array {
  $csvFiles = array_slice(scandir($dir), 2);
  $allFilesLines = [];

  foreach ($csvFiles as $csvFile) {
    $allFilesLines[$csvFile] = file($dir.$csvFile);
  }

  $processedFiles = [];

  foreach ($allFilesLines as $fileName => $fileLines) {
    $header = explode(';', $fileLines[0]);

    $processedLines = array_map(function($line) use ($header) {
      $tempArr = explode(';', $line);
      $newTempArr = [];

      for ($i = 0; $i < count($header); $i++) {
        $newTempArr[$header[$i]] = $tempArr[$i];
      }

      return $newTempArr;
    }, $fileLines);

    $processedFiles[$fileName] = $processedLines;
  }

  return $processedFiles;
};
