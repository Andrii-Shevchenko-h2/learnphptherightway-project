<?php

declare(strict_types = 1);

$includesTop ??= ''; // meta, css or js
$includesBottom ??= ''; // js before closing </body> tag
$csvTable ??= '';
?>

<!DOCTYPE html>
<html>
    <head>
        <?= $includesTop ?>
    </head>
    <body>
        <?= $csvTables ?>
        <?= $includesBottom ?>
    </body>
</html>
