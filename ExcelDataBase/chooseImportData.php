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
    #side_navigation_tree a {
        color: #444; /* Default link color */
        text-decoration: none; /* Remove underline for all links */
    }

    #side_navigation_tree a:visited {
        color: #444; /* Color for visited links */
    }
   
    #side_navigation_resizer, #side_navigation_collapser {
            position: absolute;
            cursor: pointer;
            font-size: 20px;
    }

    #page_content {
        margin: 0 0.5em;
        margin-top: 50px;
        margin-left: 240px; /* Add this line to set the initial left margin */
        transition: margin-left 0.3s ease;
    }
    #page_content strong{
        transition: margin-left 0.3s ease;
    }
    #page_content div{
        display: block;
        margin-left: 50px;
    }
    
    #page_content_header {
        padding-top:50px;
        padding-left:50px;
    }
    #importoptions{
        padding-top:50px;
        padding-left:50px;
    }
    #li_ctrate_db{
        padding-top:20px;
        padding-left:50px;
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
    table {
        display: table;
        border-collapse: separate;
        box-sizing: border-box;
        text-indent: initial;
        border-spacing: 2px;
        border-color: gray;
        border-collapse: collapse;
    }
    body {
        font-family: sans-serif;
        color: #444;
        background: #fff;
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
    // Add this function for Select All
    function toggleSelectAll() {
       //var checkboxes = document.querySelectorAll("#side_navigation_tree ul input[type='checkbox']");
       var checkboxes = document.querySelectorAll("#tableDataBases tbody input[type='checkbox']");
       var selectAllCheckbox = document.getElementById("selectAll");

       for (var i = 0; i < checkboxes.length; i++) {
           checkboxes[i].checked = selectAllCheckbox.checked;
       }
   }
   
   document.addEventListener("DOMContentLoaded", function () {
    
       var selectAllCheckbox = document.getElementById("selectAll");
       if (selectAllCheckbox) {
           selectAllCheckbox.addEventListener("click", toggleSelectAll);
       }
      
   });

</script>

<body>
    <div id ="side_navigation" style="width:240px;">
        <div id="side_navigation_resizer" onclick="toggleSideNavigation()"></div>
        <div id="side_navigation_collapser" title="隱藏面板" onclick="toggleSideNavigation()"> ← </div>
        <div id="side_navigation_content">
            <div id="side_navigation_header">
                <a href ="home.php" style="text-decoration: none;"><font color=#2d3e4e size=5px>ExcelDB</font></a>
            </div>
           
            <div id="side_navigation_tree">
                
                <?php //用來顯示所有的database
                    $dir = "C:\AppServ\www\ExcelDataBase";  // 替换成你的目录路径
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
            <strong style="font-size: 20px;" > 匯入到 " <?php echo $selectedTable; ?> " 資料表</strong>
        </div>
        <div>
            <form id="importForm" action="importData.php" method="post" enctype="multipart/form-data">
         
                <label for="input_importData_file">由電腦上傳:</label>
                <input type="file" id="input_importData_file" name="input_importData_file" accept=".csv, .xls, .xlsx">
                
                <div id="upload_form_status" style="display: none;"></div>
                <div id="upload_form_status_info" style="display: none;"></div>
                
                <input type="hidden" name="MAX_FILE_SIZE" value="209715200">

                <!-- Add a hidden input to pass the selected database name to the server -->
                <input type="hidden" name="selectedDatabase" value="<?php echo $selectedDB?>">
                <input type="hidden" name="selectedTable" value="<?php echo $selectedTable?>">

                <br><br>
                <input type="submit" value="執行" id="buttonGo">
            </form>    
        </div>
      
    </div>
</body>

</html>
