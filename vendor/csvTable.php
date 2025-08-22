<?php

declare(strict_types = 1);

$getSecureString = fn(string $str): string => htmlspecialchars($str);

$generateTableCaption = fn(string $name): string => '<caption>'. $getSecureString($name) .'</caption>';

$generateTableCell = function(array $row) use ($getSecureString): string {
  $string = '';

  foreach ($row as $data) {
    $string .= '<td>'. $getSecureString($data) .'</td>';
  }

  return $string;
};

$generateTableHead = function(array $headerRow) use ($getSecureString): string {
  $ths = '<tr>';

  foreach ($headerRow as $headerColumn) {
    $ths .= '<th>'. $getSecureString($headerColumn) .'</th>';
  }

  $ths .= '</tr>';

  return $ths;
};

$generateTableBody = function(array $tableData) use ($generateTableCell): string {
  $tableBody = '';

  foreach ($tableData as $row) {
    $tableBody .= '<tr>'. $generateTableCell($row) .'</tr>';
  }

  return $tableBody;
};

// will return csv table html string which I can put in later with parse.php
$generateCsvTable = function(string $csvName, array $csvContent) use ($generateTableHead, $generateTableBody, $generateTableCaption): string {
  $header = $csvContent[0];
  $csvBodyContent = array_slice($csvContent, 1);
  $tableCaption = $generateTableCaption($csvName);
  $tableHeader = $generateTableHead($header);
  $tableBody = $generateTableBody($csvBodyContent);

  return <<<tableHTML
    <table>
      $tableCaption
      $tableHeader
      $tableBody
    </table>
  tableHTML;
};
