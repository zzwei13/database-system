
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dir = "C:\\AppServ\\www\\ExcelDataBase";  
    $database = $_POST['db'];
    $tableName = $_POST['table'];
    $csvFilePath = $dir . DIRECTORY_SEPARATOR . $database . DIRECTORY_SEPARATOR . $tableName . ".csv";
    echo "<script>";
    echo "console.log('csvFilePath: " . $csvFilePath . "');\n";
    echo "</script>";

    $data = [];
    foreach ($_POST['fields_data'] as $columnName => $value) {
        $data[] = $value;
    }

    // Check if all values in $data are empty
    if (array_filter($data)) {
        // Check if the CSV file is empty
        $isEmpty = filesize($csvFilePath) === 0;

        // Write data to CSV file
        $handle = fopen($csvFilePath, 'a');

        // Move the file pointer to the next row if it's not empty
        if (!$isEmpty) {
            fseek($handle, 0, SEEK_END);
        }

        // Add data to the next available row
        fputcsv($handle, $data);

        fclose($handle);

        // Send a response back to the client (optional)
        echo "Data added successfully!";
    } else {
        // Send an error response back to the client
        echo "Error: All values in field_data are empty!";
    }
}
?>
