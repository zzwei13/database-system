
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取选中的数据库
    $selectedDatabases = isset($_POST['selectedDatabases']) ? explode(',', $_POST['selectedDatabases']) : [];

    // 删除选中的数据库
    foreach ($selectedDatabases as $database) {
        // 删除数据库对应的文件夹（你需要根据实际情况修改路径）
        $dirPath = "C:/AppServ/www/ExcelDataBase/{$database}"; // Use forward slashes in the path
        
        if (is_dir($dirPath)) {
            if (!deleteDirectory($dirPath)) {
                // 返回失败的响应
                echo json_encode(["status" => "error", "message" => "Failed to delete folder: {$dirPath}"]);
                exit();
            }
        } else {
            // 返回失败的响应，文件夹不存在
            echo json_encode(["status" => "error", "message" => "Folder does not exist: {$dirPath}"]);
            exit();
        }
    }

    // 返回成功的响应
    echo json_encode(["status" => "success"]);
    exit();
}

// 递归删除目录及其内容
function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        $path = $dir . DIRECTORY_SEPARATOR . $item;

        if (!deleteDirectory($path)) {
            // Log the error
            error_log("Failed to delete: {$path}");

            return false;
        }
    }

    if (!rmdir($dir)) {
        // Log the error
        error_log("Failed to delete directory: {$dir}");

        return false;
    }

    return true;
}

?>
