<?php
$directoryPath = "C:\\AppServ\\www\\ExcelDataBase";

// 使用icacls命令授予管理員權限
$command = "icacls \"" . $directoryPath . "\" /grant administrators:(OI)(CI)F";

// 執行命令
exec($command, $output, $returnVar);

// 檢查命令是否成功執行
if ($returnVar === 0) {
    echo "管理員權限已成功授予給 " . $directoryPath;
} else {
    echo "發生錯誤，無法授予管理員權限。";
}
?>
