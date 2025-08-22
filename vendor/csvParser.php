<?php

declare(strict_types = 1);

// because "10;15" should be treated as one field
$explodeNonQuotes = function(string $separator, string $str): array {
  $stingLength = strlen($str);
  $quotesMode = false;
  $resultArray = [];
  $currentField = '';

  for ($i = 0; $i < $stingLength; $i++) {
    if ($str[$i] === '"') {
      $quotesMode ^= true;
      continue;
    }

    if ($str[$i] === $separator && $quotesMode) $str[$i] = ',';

    if ($str[$i] === $separator && !$quotesMode) {
      $resultArray[] = $currentField;
      $currentField = '';
      continue;
    }

    $currentField .= $str[$i];
  }

  $resultArray[] = $currentField;

  return $resultArray;
};

// assumes all files are CSV, no error-handling
$csvParse = function(string $dir) use ($explodeNonQuotes): array {
  $csvFiles = array_slice(scandir($dir), 2);
  $allFilesLines = [];

  foreach ($csvFiles as $csvFile) {
    $allFilesLines[$csvFile] = file($dir.$csvFile);
  }

  $processedFiles = [];

  foreach ($allFilesLines as $fileName => $fileLines) {
    $header = $explodeNonQuotes(';', $fileLines[0]);

    $processedLines = array_map(function($line) use ($header, $explodeNonQuotes) {
      $tempArr = $explodeNonQuotes(';', $line);
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
