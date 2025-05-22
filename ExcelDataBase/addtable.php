<?php
// 在適當的地方，調用授予管理員權限的腳本
include('grant_admin_permissions.php');
?>
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dir = "C:\\AppServ\\www\\ExcelDataBase";  
    $database = $_POST['database'];
    $tableName = $_POST['tableName'];
    $numFields = $_POST['numFields'];
    $fieldValues = $_POST['fieldValues'];
    // Log received data to error log
    echo "<script>";
    echo "console.log('Received databaseName: " . $database . "');\n";
    echo "console.log('Received tableName: " . $tableName . "');\n";
    echo "console.log('Received numFields: " . $numFields . "');\n";
    echo "console.log('Received fieldValues: " . $fieldValues . "');\n";
    echo "</script>";

    $databasePath = $dir . DIRECTORY_SEPARATOR . $database;
    echo "<script>";
    echo "console.log('databasePath: " . $databasePath . "');\n";
    echo "</script>";
    if (!empty($tableName) && $numFields > 0) {
        // Create the new table (CSV file)
        $tablePath = $databasePath . DIRECTORY_SEPARATOR . $tableName . ".csv";
        echo "<script>";
        echo "console.log('tablePath: " . $tablePath . "');\n";
        echo "</script>";

        if (!file_exists($tablePath)) {

            $csvFile = fopen($tablePath, 'w');

            // 寫入 BOM（位元組順序標記）
            fwrite($csvFile, "\xEF\xBB\xBF");

            // 寫入數據
            fwrite($csvFile, utf8_encode($fieldValues));
            fwrite($csvFile, "\n");

            fclose($csvFile);

        
            echo "<script>";
            echo "console.log('{$tableName}.csv created successfully!');";
            echo "</script>";
        }
        
    } else {
        echo "<script>";
        echo "console.error('Unable to open {$tableName}.csv for writing.');";
        echo "</script>";
    }
}
?>
