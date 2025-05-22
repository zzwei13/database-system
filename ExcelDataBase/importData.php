<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 檢查是否有選擇資料庫
    $selectedDatabase = isset($_POST['selectedDatabase']) ? htmlspecialchars($_POST['selectedDatabase']) : null;
    $selectedTable = isset($_POST['selectedTable']) ? htmlspecialchars($_POST['selectedTable']) : null;

    // 檢查是否成功上傳檔案
    if (isset($_FILES['input_importData_file']) && $_FILES['input_importData_file']['error'] == UPLOAD_ERR_OK) {
        // 設定上傳檔案的目錄
        $uploadDir = "C:\\AppServ\\www\\ExcelDataBase\\" . $selectedDatabase . DIRECTORY_SEPARATOR;
        $uploadFile = $uploadDir.basename($_FILES['input_importData_file']['name']);
        $existingFile = $uploadDir.$selectedTable . ".csv";

        // 移動上傳的檔案到指定的目錄
        if (move_uploaded_file($_FILES['input_importData_file']['tmp_name'], $uploadFile)) {
            // 檢查欄位名稱是否相符
            $uploadedColumns = getCSVColumns($uploadFile);
            
            if ($uploadedColumns !== false) {
                // Check if the file exists before attempting to open it
                $existingColumns = getCSVColumns($existingFile);

                if ($existingColumns !== false) {
                    
                    // Check if the number of columns is the same
                    if (count($uploadedColumns) === count($existingColumns)) {
                        // Check if each column name is the same
                        $columnEqual = true;
                        // Iterate using $existingColumns' count as the reference
                        foreach (range(1, count($existingColumns)) as $index) {
                            $existingColumn = $existingColumns[$index];
                            $uploadedColumn = $uploadedColumns[$index];
                           
                            if ($uploadedColumn !== $existingColumn) {
                                $columnEqual = false;
                                echo "Mismatch found in columns at index $index<br>";
                                echo "Uploaded Column: $uploadedColumn<br>";
                                echo "Existing Column: $existingColumn\n";
                                break;
                            }
                        }

                        if ($columnEqual === true) { 
                            // 將上傳的檔案內容追加到已存在的檔案中
                            $uploadedContents = file_get_contents($uploadFile);
                            // 將上傳的檔案內容讀取為陣列，每一行為一個元素
                            $uploadedLines = explode("\n", $uploadedContents);
                            // 從第二行開始追加到已存在的檔案中
                            for ($i = 1; $i < count($uploadedLines)-1; $i++) {
                                file_put_contents($existingFile, $uploadedLines[$i] . "\n", FILE_APPEND);
                            }

                            //file_put_contents($existingFile, $uploadedContents, FILE_APPEND);
    
                            // Delete the uploaded file
                            if (file_exists($uploadFile)) {
                                unlink($uploadFile);
                            }
    
                            // 成功上傳後重新導向到 displayData.php
                            $subdir = urlencode($selectedDatabase); // 對資料庫名稱進行 URL 編碼
                            $table = urlencode(pathinfo($selectedTable, PATHINFO_FILENAME)); // 對表格名稱進行 URL 編碼
                            $token = urlencode("0f5bbd6da033b62c3872f6f1155703a8"); // 對 token 進行 URL 編碼
                            $redirectUrl = "displayData.php?pos=0&db={$subdir}&table={$table}&sort_by=SCHEMA_NAME&sort_order=desc&token={$token}";
                    
                            // 使用 header 函式進行重新導向
                            header("Location: {$redirectUrl}");
                            exit;
                        } else {
                            echo "欄位名稱不相符，匯入失敗。";
                        }


                        
                    } else {
                        // Columns count mismatch, handle accordingly
                        echo "Columns count mismatch. Unable to perform detailed check.\n";
                    }

                    

                    
                } else {
                    echo "無法讀取已存在 CSV 檔案的欄位名稱。";
                }
            } else {
                echo "無法讀取 CSV 檔案的欄位名稱。";
            }

        } else {
            echo "移動上傳的檔案時發生錯誤。";
        }
    } else {
        echo "檔案上傳失敗。";
    }
} else {
    // 處理直接訪問腳本的情況
    echo "無效的請求。";
}

// 從 CSV 檔案中取得欄位名稱的函式
function getCSVColumns($csvFile) {
    // Initialize columns array
    $columns = [];

    // Open the CSV file for reading
    $handle = fopen($csvFile, "r");

    if ($handle !== false) {
        // Read the first row as column names
        $columns = fgetcsv($handle);

        // Close the file handle
        fclose($handle);

        // Output the retrieved column names
        echo "File Name: $csvFile<br>";
        echo "抓取到的欄位名稱: " . implode(', ', $columns);
        echo "<br>";
    } else {
        // Handle the case where fopen failed to open the file
        echo "無法打開 CSV 檔案。";
    }

    return $columns;
}

?>
