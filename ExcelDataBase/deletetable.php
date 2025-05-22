
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取选中的数据库和表格
    $selectedDb = isset($_POST['db']) ? $_POST['db'] : '';//包含了数据库名称
    $selectedTables = isset($_POST['selectedTables']) ? explode(',', $_POST['selectedTables']) : [];//包含了以逗号分隔的选中表格的字符串。explode 函数用于将字符串转换为数组

    // 删除选中的数据库中的表格
    foreach ($selectedTables as $table) {   
        $dir = "C:\AppServ\www\ExcelDataBase";  // 替換為您的目錄路徑
        $filePath = $dir . DIRECTORY_SEPARATOR . $selectedDb . DIRECTORY_SEPARATOR . $table . '.csv';

        // 將相對路徑轉換為絕對路徑
        $absolutePath = realpath($filePath);

        if (file_exists($absolutePath)) {
            if (!unlink($absolutePath)) {
                // 返回失败的响应
                echo json_encode(["status" => "error", "message" => "Failed to delete file: {$absolutePath}"]);
                exit();
            }
        } else {
            // 返回失败的响应，文件不存在
            echo json_encode(["status" => "error", "message" => "File does not exist: {$absolutePath}"]);
            exit();
        }
    }

    // 返回成功的响应
    echo json_encode(["status" => "success"]);
    exit();
}
?>

