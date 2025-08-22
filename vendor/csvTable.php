<?php

declare(strict_types = 1);

$getSecureString = fn(string $str): string => htmlspecialchars($str, ENT_QUOTES);

$generateTableCaption = fn(string $name): string => '<caption>'. $getSecureString($name) .'</caption>';

$formatDate = fn(string $date): string => date('D, \t\h\e jS F, Y', strtotime($date));

$formatAmountColor = fn(string $amount): string => $amount[0] === '-' ? '<span style="color: red">'. $amount .'</span>' : '<span style="color: green">'. $amount .'</span>';

$generateTableCell = function(array $row, float &$incomeTotal, float &$expensesTotal) use ($formatDate, $formatAmountColor, $getSecureString): string {
  $string = '';

    foreach ($row as $name => $data) {
    $name = trim($name);

    if ($name === 'Date') {
      $string .= '<td>'. $formatDate($getSecureString($data)) .'</td>';
    } elseif ($name === "Amount") {
      $numericData = (double) str_replace(['$', ','], '', $data);
      $string .= '<td>'. $formatAmountColor($getSecureString($data)) .'</td>';

      if ($numericData >= 0) $incomeTotal += $numericData;
      else $expensesTotal += (double) $numericData;
    } else {
      $string .= '<td>'. $getSecureString($data) .'</td>';
    }
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

$generateTableBody = function(array $tableData, float &$income, float &$expenses) use ($generateTableCell): string {
  $tableBody = '';

  foreach ($tableData as $row) {
    $tableBody .= '<tr>'. $generateTableCell($row, $income, $expenses) .'</tr>';
  }

  return $tableBody;
};

$generateStandaloneRow = function(string $valueName, string $value, int $totalCells = 4): string {
  // current logic for this is not ideal, but who cares
  $totalCellsString = '';

  for ($i = 0; $i < $totalCells - 1; $i++) {
    $totalCellsString .= "<td>\n</td>\n";
  }

  return <<<HTML
    <tr>
      $totalCellsString
      <td>
        $valueName: $value
      </td>
    </tr>
  HTML;
};

// will return csv table html string which I can put in later with parse.php
$generateCsvTable = function(string $csvName, array $csvContent) use ($generateTableHead, $generateTableBody, $generateTableCaption, $formatAmountColor, $generateStandaloneRow): string {
  $header = $csvContent[0];
  $incomeTotal = 0;
  $expensesTotal = 0;
  $csvBodyContent = array_slice($csvContent, 1);
  $tableCaption = $generateTableCaption($csvName);
  $tableHeader = $generateTableHead($header);
  $tableBody = $generateTableBody($csvBodyContent, $incomeTotal, $expensesTotal);
  $total = (string) ($incomeTotal + $expensesTotal);
  $incomeTotal = (string) $incomeTotal;
  $expensesTotal = (string) $expensesTotal;
  $tableColumns = substr_count($tableHeader, '<th>');

  $formattedIncome = $generateStandaloneRow('Income', $formatAmountColor($incomeTotal), $tableColumns);
  $formattedExpenses = $generateStandaloneRow('Expenses', $formatAmountColor($expensesTotal), $tableColumns);
  $formattedTotal = $generateStandaloneRow('Total', $formatAmountColor($total), $tableColumns);

  return <<<tableHTML
    <table>
      $tableCaption
      $tableHeader
      $tableBody
      $formattedIncome
      $formattedExpenses
      $formattedTotal
    </table>
  tableHTML;
};
