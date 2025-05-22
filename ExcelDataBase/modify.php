<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "console.log('modify open!');\n";
    $dir = "C:\\AppServ\\www\\ExcelDataBase";  
    $database = $_POST['db'];
    $tableName = $_POST['table'];
    $csvFilePath = $dir . DIRECTORY_SEPARATOR . $database . DIRECTORY_SEPARATOR . $tableName . ".csv";
    echo "<script>";
    echo "console.log('csvFilePath: " . $csvFilePath . "');\n";
    echo "</script>";

    $findRow = [];
    foreach ($_POST['fields_toModify'] as $Original => $value) {
        $findRow[] = $value;
    }
    echo "<script>";
    echo "console.log('findRow:', " . json_encode($findRow) . ");\n";
    echo "</script>";

    $data = [];
    foreach ($_POST['fields_update'] as $UpdateData => $value) {
        $data[] = $value;
    }
    echo "<script>";
    echo "console.log('data:', " . json_encode($data) . ");\n";
    echo "</script>";

    $rows = array_map('str_getcsv', file($csvFilePath));

    $updated = false;
$newRows = [];

foreach ($rows as $row) {
    // Check if the first element matches
    if (isset($row[0]) && $row[0] == $findRow[0]) {
        // Found the matching row, update specific columns
        $newRow = $data;
        $updated = true;
    } else {
        $newRow = $row;
    }

    $newRows[] = $newRow;
}

if ($updated) {
    // Write the updated data back to the CSV file
    $handle = fopen($csvFilePath, 'w');
    foreach ($newRows as $newRow) {
        fputcsv($handle, $newRow);
    }
    fclose($handle);

    echo "<script>";
    echo "console.log('Row updated successfully.');\n";
    echo "</script>";
} else {
    echo "<script>";
    echo "console.log('Row not found.');\n";
    echo "</script>";
}

}
?>
