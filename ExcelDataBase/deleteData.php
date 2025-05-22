<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    // 获取选中的数据库和表格
    $selectedDb = isset($_POST['db']) ? $_POST['db'] : '';//包含了数据库名称
    $selectedTable = isset($_POST['table']) ? $_POST['table'] : '';
    $selectedRowsValues = isset($_POST['selectedRowsValues']) ? $_POST['selectedRowsValues'] : '';

    // Construct the path to the CSV file
    $csvFilePath = $selectedDb . DIRECTORY_SEPARATOR . $selectedTable . '.csv';

    // Check if the CSV file exists
    if (file_exists($csvFilePath)) {
        // 將整個檔案讀取到 $csvData 中
        $csvData = array_map('str_getcsv', file($csvFilePath));

        // 將 $csvData 中的第一行（標題行）移除並存儲在 $headers 中
        $headers = array_shift($csvData);

        // 這一行將來自 POST 請求的名為 'selectedRowsValues' 的參數的值轉換為數組。
        //explode 函數將字符串按照逗號分割為數組
        $selectedRowsArray = explode(',', $selectedRowsValues);

        // 遍歷 $csvData 中的每一行
        foreach ($csvData as $key => $row) {
            // 將當前行轉換為數組，然後比較兩個數組是否相等
            if (array_diff($row, $selectedRowsArray) == array() && array_diff($selectedRowsArray, $row) == array()) {
                // 如果當前行與選擇的行數組相等，則從 $csvData 中移除這一行
                unset($csvData[$key]);
            }
        }
        
        // 將先前移除的標題行 $headers 加回到 $csvData 的前面
        array_unshift($csvData, $headers);

        // 打開csv檔寫檔
        $csvFile = fopen($csvFilePath, 'w');

        // 將每一欄寫入csv檔
        foreach ($csvData as $row) {
            fputcsv($csvFile, $row);
        }

        // 關閉csv檔
        fclose($csvFile);
        
        // Return a success message
        echo json_encode(["status" => "success"]);
    } else {
        // Return an error message if the CSV file doesn't exist
        echo json_encode(["status" => "error", "message" => "File does not exist: {$csvFilePath}"]);
    }
}

?>

