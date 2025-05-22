<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8">
  <style type="text/css">
    
    *{
    margin:0;
    }
    #side_navigation {
        position: fixed;
        top: 0;
        height: 100%;
        background-color: #f0f0f0;
        padding-top: 60px;
    }
    #side_navigation_resizer {
        width: 3px;
        height: 100%;
        background-color: #f0f0f0;
        cursor: col-resize;
        position: fixed;
        top: 0;
        left: 240px;
        z-index: 801;
    }
    #side_navigation_collapser {
        width: 20px;
        height: 22px;
        line-height: 22px;
        background: white;
        color: #555;
        font-weight: bold;
        position: fixed;
        top: 0;
        left: 240px;
        text-align: center;
        cursor: pointer;
        z-index: 800;
        text-shadow: 0 1px 0 #fff;
        filter: dropshadow(color=#fff, offx=0, offy=1);
        border: 1px solid white;
    }
    
    #side_navigation_content {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 0;
    }

    #side_navigation_header {
        margin: 10px;
        overflow: hidden;
        text-align: center;
    }
    #side_navigation_tree {
        margin: 10px;
        overflow: hidden;
        color: #444;
        height: 74%;
        position: relative;
        text-align: center;
    }
   
   
    #side_navigation_resizer, #side_navigation_collapser {
            position: absolute;
            cursor: pointer;
            font-size: 20px;
    }

    #page_content {
        padding:50px;
        margin: 0 0.5em;
        margin-left: 240px; /* Add this line to set the initial left margin */
        transition: margin-left 0.3s ease;
    }
    #page_content div{
        display: block;
    }
    
    #page_content_header {
        padding:50px;
    }

    #li_ctrate_db{
        padding:50px;
    }
    #tableslistcontainer{
        padding:50px;
        margin-top: 1em;
    }
    #tableslistcontainer form {
        padding: 0;
        margin: 0;
        display: inline;
    }

    .colborder {
        cursor: col-resize;
        height: 100%;
        margin-left: -6px;
        position: absolute;
        width: 5px;
    }
    .edb_table td {
        position: static;
    }
    table {
        display: table;
        border-collapse: separate;
        box-sizing: border-box;
        text-indent: initial;
        border-spacing: 2px;
        border-color: gray;
        border-collapse: collapse;
    }

    table th, table td {
        padding: 0.3em;
        margin: 0.1em;
        vertical-align: top;
        text-shadow: 0 1px 0 #fff;
    }

    thead th {
        border-right: 1px solid #fff;
        text-align: left;
        background: -webkit-linear-gradient(top, #ffffff, #cccccc)
    }

    tbody {
        display: table-row-group;
        vertical-align: middle;
        border-color: inherit;
    }
    body {
        font-family: sans-serif;
        color: #444;
        background: #fff;
    }
    a {
        color: #444; /* Default link color */
        text-decoration: none; /* Remove underline for all links */
    }
   
    ul{
    margin:0;
    padding:0;
    list-style-type:none;
    }

   

  </style>
</head>

<script type="text/javascript">
    function toggleSideNavigation() {
        var sideNavigation = document.getElementById("side_navigation");
        var sideNavigationResizer = document.getElementById("side_navigation_resizer");
        var sideNavigationCollapser = document.getElementById("side_navigation_collapser");
        var pageContent = document.getElementById("page_content");

        if (sideNavigation.style.width === "240px") {
            sideNavigation.style.width = "0";
            sideNavigationResizer.style.left = "0";
            sideNavigationCollapser.innerHTML = "→";
            sideNavigationCollapser.title = "顯示面板";
            sideNavigationCollapser.style.left = "3px";
            pageContent.style.marginLeft = "3px";
        } else {
            sideNavigation.style.width = "240px";
            sideNavigationResizer.style.left = "240px";
            sideNavigationCollapser.innerHTML = "←";
            sideNavigationCollapser.title = "隱藏面板";
            sideNavigationCollapser.style.left = "240px";
            pageContent.style.marginLeft = "240px";
        }
    }


    function deleteSelectedRows() {
        //var checkboxes = document.querySelectorAll("#page_content ul input[type='checkbox']:checked");
        //使用CSS選擇器選擇了所有被勾選的checkbox，這些checkbox位於#page_content元素下的ul元素中。
        var selectedDb = document.querySelector("input[name='db']").value;
        console.log(selectedDb);//確定是有抓到值的!!
        var selectedTable = document.querySelector("input[name='table']").value;
        console.log(selectedTable);//確定是有抓到值的!!
        var selectedRows = document.querySelectorAll('input[name="rows_to_delete[0]"]:checked');
        var selectedRowsValues = [];

        selectedRows.forEach(function (row) {
            selectedRowsValues.push(row.value);
        });
        console.log(selectedRowsValues);//確定是有抓到值的!!

        // 使用 AJAX 發送 POST 請求
        var xhr = new XMLHttpRequest();
        //MLHttpRequest的狀態改變時，這個函式會被呼叫
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    
                    try {
                        // 嘗試解析 JSON
                        var response = JSON.parse(xhr.responseText);

                        // 在這裡處理 JSON 解析後的內容
                        if (response.status === "success") {
                            location.reload();
                        } else {
                            alert("Error deleting selectedRows: " + response.message);
                        }
                    } catch (error) {
                        // JSON 解析失敗，可能是因為回應不是有效的 JSON
                        console.error("Error parsing JSON:", error);

                        // 在這裡處理錯誤情況，例如顯示一個錯誤訊息
                        alert("Error parsing server response"); //現在一直跳這個
                    }
                } else {
                    // 處理錯誤
                    alert("Error deleting selectedRows");
                }
            }
        };
        xhr.open("POST", "deleteData.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("selectedRowsValues=" + encodeURIComponent(selectedRowsValues.join(',')) + "&table=" + encodeURIComponent(selectedTable)+ "&db=" + encodeURIComponent(selectedDb));
        

        //這部分用來告訴伺服器是哪個資料庫中的資料表需要刪除

    }

    function modifySelectedRows() {
        var formData = new FormData();

        var selectedFirstRow = document.querySelectorAll('.column_heading');

        var selectedColumnsNames = [];
        selectedFirstRow.forEach(function (row) {
            var columnName =row.getAttribute('data-column');
            selectedColumnsNames.push(columnName);
        });

        formData.append('selectedColumnsNames', selectedColumnsNames.join(','));

        var selectedDb = document.querySelector("input[name='db']").value;
        var selectedTable = document.querySelector("input[name='table']").value;

        formData.append('db', selectedDb);
        formData.append('table', selectedTable);

        var selectedRows = document.querySelectorAll('input[name="rows_to_delete[0]"]:checked');
        
        var selectedRowsValues = [];
        selectedRows.forEach(function (row) {
            selectedRowsValues.push(row.value);
        });

        formData.append('selectedRowsValues', selectedRowsValues.join(','));

        console.log("[Sending formData]");
        console.log("selectedColumnsNames:"+ selectedColumnsNames);
        console.log("table: " + selectedTable);
        console.log("db: " + selectedDb);
        console.log("selectedRowsValues: " + selectedRowsValues);

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);

                        if (response.status === "success") {
                            location.reload();
                        } else {
                            alert("Error deleting selectedRows: " + response.message);
                        }
                    } catch (error) {
                        console.error("Error parsing JSON:", error);
                        alert("Error parsing server response");
                    }
                } else {
                    alert("Error modify selectedRows");
                }
            }
        };

        xhr.open("POST", "modifyData.php", true);
        xhr.send(formData);

        var modifyLink = "modifyData.php?db=" + encodeURIComponent(selectedDb) +
        "&table=" + encodeURIComponent(selectedTable) +
        "&rows=" + encodeURIComponent(selectedRowsValues.join(','))+ "&ColumnName="+encodeURIComponent(selectedColumnsNames.join(','));

        window.location.href = modifyLink;

    }


   document.addEventListener("DOMContentLoaded", function () {
        var addButton = document.getElementById("addDataButton");
        var modifyButton = document.getElementById("modifyDataButton");
        var deleteButton = document.getElementById("deleteButton");

        if (addButton) {
            addButton.addEventListener("click", function () {
                window.location.href = "createData.php";
            });
        }

        if (modifyButton) {
            modifyButton.addEventListener("click", modifySelectedRows );
        }
        
        if (deleteButton) {
            deleteButton.addEventListener("click", deleteSelectedRows);
        }
    });

</script>

<body>
    <div id ="side_navigation" style="width:240px;">
        <div id="side_navigation_resizer" onclick="toggleSideNavigation()"></div>
        <div id="side_navigation_collapser" title="隱藏面板" onclick="toggleSideNavigation()">←</div>
        <div id="side_navigation_content">
            <div id="side_navigation_header">
                <a href ="home.php" style="text-decoration: none;"><font color=#2d3e4e size=5px>ExcelDB</font></a>
            </div>
            <div id="side_navigation_tree">
                <?php //用來顯示所有的database
                    $dir = "C:\AppServ\www\ExcelDataBase";
                    $files = scandir($dir);
                    $subdirectories = array_filter($files, function($item) use ($dir) {
                    return is_dir($dir . '/' . $item) && !in_array($item, ['.', '..']);
                    });
                ?>
                <ul>
                <?php foreach ($subdirectories as $subdir): ?>
                    <li>
                    <a href="displayTable.php?pos=0&amp;db=<?php echo $subdir; ?>&sort_by=SCHEMA_NAME&amp;sort_order=desc&amp;token=0f5bbd6da033b62c3872f6f1155703a8">
                        <?php echo $subdir; ?></a>
                    </li>
                <?php endforeach; ?>
                </ul>

            </div>
        </div>
    </div>
  
    <div id="page_content">
        <?php
        // 構建資料庫路徑
        $databasePath = $dir . DIRECTORY_SEPARATOR . $subdir;
        
        // 從URL參數中獲取所選擇的資料庫和資料表
        $selectedDB = isset($_GET['db']) ? $_GET['db'] : '';
        $selectedTable = isset($_GET['table']) ? $_GET['table'] : '';
        
        // 獲取資料庫中的所有文件
        $tableFiles = scandir($databasePath);

        // 過濾出所有的CSV文件
        $tables = array_filter($tableFiles, function ($item) {
            return pathinfo($item, PATHINFO_EXTENSION) === 'csv';
        });
    
        // 過濾出所有的子目錄
        $subdirectories = array_filter($tableFiles, function ($item) use ($dir, $subdir) {
            return is_dir($dir . DIRECTORY_SEPARATOR . $subdir . DIRECTORY_SEPARATOR . $item);
        });

        // 遍歷所有的CSV文件，顯示匹配的資料表名稱
        foreach ($tables as $table): 
            $tableName = pathinfo($table, PATHINFO_FILENAME);
            if ($selectedDB === $dir && $selectedTable === $tableName): ?>
                <!---資料表--->
            <?php endif;
        endforeach;

        // 遍歷所有的子目錄，顯示匹配的子目錄和其中的CSV文件
        foreach ($subdirectories as $subdir): 
            $subdirPath = $dir . DIRECTORY_SEPARATOR . $subdir;
            $subdirTableFiles = scandir($subdirPath);
            $subdirTables = array_filter($subdirTableFiles, function ($item) {
                return pathinfo($item, PATHINFO_EXTENSION) === 'csv';
            });
            // 遍歷子目錄中的CSV文件，顯示匹配的資料表名稱
            foreach ($subdirTables as $subdirTable): 
                $subdirTableName = pathinfo($subdirTable, PATHINFO_FILENAME);
                if ($selectedDB === $subdir && $selectedTable === $subdirTableName): ?>
                    <li><?php echo $subdirTableName; ?></li>
                <?php endif;
            endforeach;
        endforeach;
        ?>
        <div>
            <strong style="font-size: 20px;" ><?php echo $selectedTable; ?>資料表</strong>
            <a href="chooseImportData.php?db=<?php echo $selectedDB; ?>&table=<?php echo $selectedTable; ?>&amp;token=0f5bbd6da033b62c3872f6f1155703a8" class="tab">[匯入]</a>

        </div>
        <!--新增紀錄 button -->
        <form method= "POST" action="createData.php" name="dataForm" class="ajax">
            <button id="addDataButton">新增紀錄</button>
            <input type="hidden" name="db" value="<?php echo $selectedDB; ?>"> 
            <input type="hidden" name="table" value="<?php echo $selectedTable; ?>">
            <input type="hidden" name="token" value="f516c89cd0adfa8b6e7c3cd0bbe0295c">
        </form>
        <!--顯示table 的data -->
        <div class="data" style="position: relative;">
            <div class="cRsz" style="height: ;">
                <div class="colborder" style="left: ; display: block;"></div>
                <div class="colborder" style="left: ; display: block;"></div>
            </div>
            <table class="edb_table">
                <thead>
                    <tr>
                        <th class="column_action print_ignore" colspan="1">
                            <span>
                                <a></a>
                            </span>
                        </th>
                        <?php
                        // 讀取所選取的CSV檔的第1欄(也就是 sid, eid之類的)
                            $csvFilePath = $dir . DIRECTORY_SEPARATOR . $selectedDB . DIRECTORY_SEPARATOR . $selectedTable . '.csv';
                            if (file_exists($csvFilePath)) { // 檢查 CSV 檔案是否存在
                                $csvFile = fopen($csvFilePath, 'r'); // 打開 CSV 檔案，使用 'r' 模式表示讀取操作
                                $header = fgetcsv($csvFile); // 從 CSV 檔案中讀取一行，這行通常是標頭行（包含列的名稱）
                                fclose($csvFile);

                                // CSV檔的第1欄($header) 作為 表格的標頭($columnName)
                                foreach ($header as $columnName): ?>
                                    <th class="column_heading" data-column="<?php echo $columnName; ?>">
                                        <span>
                                            <a><?php echo $columnName; ?></a>
                                        </span>
                                    </th>
                                <?php endforeach;
                            }
                        ?>
                    </tr>
                </thead>
                
                <tbody>
                    <?php
                        // Read the remaining rows of the CSV file and display the data in the table
                        if (file_exists($csvFilePath)) {
                            $csvFile = fopen($csvFilePath, 'r');
                            // Skip the header row
                            fgetcsv($csvFile);
                            while (($row = fgetcsv($csvFile)) !== false): ?>
                             <!-- 開始處理每一列的資料 -->
                                <tr>
                                    <td class="">
                                        <span>
                                            <input type="checkbox" id="id_rows_to_delete0_left" name="rows_to_delete[0]" class="multi_checkbox checkall" value="<?php echo implode(',', $row); ?>">
                                        </span>
                                    </td>
                                    <?php
                                    // Display the data in each column
                                    foreach ($row as $cell): ?>
                                        <td data-decimals="0" data-type="string" class="">
                                            <span><?php echo $cell; ?></span>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endwhile;
                            fclose($csvFile);
                        }
                    ?>

                </tbody>

            </table>
        </div>
        <!--修改紀錄 button -->
        <button id="modifyDataButton">修改</button>
        <!--刪除紀錄 button -->
        <button id="deleteButton">刪除</button>
            
        
    </div>
</body>
</html>

<!--
<div class="print_ignore">
    <input type="checkbox" id="resultsForm_11489_checkall" class="checkall_box" title="全選"> 
        <label for="resultsForm_11489_checkall">全選</label> 
    <i style="margin-left: 2em">已選擇項目:</i>
       
    <button class="mult_submit" type="submit" name="submit_mult" value="delete" title="刪除">
        <span class="nowrap">刪除</span>
    </button>
                
</div>
                    -->