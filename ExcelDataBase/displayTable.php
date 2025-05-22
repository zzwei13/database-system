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
        margin-left: 240px;
        transition: margin-left 0.3s ease;
        padding-top:50px;
        padding-left:50px;
    }
    #page_content div{
        display: block;
    }

    a {
        color: #444; /* Default link color */
        text-decoration: none; /* Remove underline for all links */
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

<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.4.min.js">
    

   function prepareCreateTableUrl() {
        // Modify this function to construct the URL based on your requirements
        var dataBaseNameInput = document.querySelector("input[name='db']");
        var tableNameInput = document.getElementById("tableNameInputForm");
        var numFieldsInput = document.querySelector("input[name='num_fields']");

        // Construct the URL
        var url = "createTable.php?db=" + encodeURIComponent(dataBaseNameInput.value.trim()) +
                "&tableName=" + encodeURIComponent(tableNameInput.value.trim()) +
                "&numFields=" + encodeURIComponent(numFieldsInput.value.trim());

        // Redirect to the prepared URL
        window.location.href = url;

        // Return false to prevent the default form submission
        return false;
    }
  
</script>

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
    function deleteSelectedTables() {
        console.log("Delete button clicked");
        var checkboxes = document.querySelectorAll("#page_content ul input[type='checkbox']:checked");
        //使用CSS選擇器選擇了所有被勾選的checkbox，這些checkbox位於#page_content元素下的ul元素中。
        var selectedDb = document.querySelector("input[name='db']").value;
        //獲取了名稱為'db'的input元素的值，這個值代表了所選的資料庫
        console.log(selectedDb);
        if (checkboxes.length > 0) {
            //檢查是否有選擇了要刪除的資料表，只有當有勾選的checkbox存在時才執行下面的代碼
            var selectedTables = [];//創建陣列存放所選資料表的名稱
            checkboxes.forEach(function (checkbox) {
                // 獲取表格名稱
                var listItem = checkbox.closest("li");//找到包含當前checkbox的li元素
                var nameElement = listItem.querySelector("a");//找到li元素中的a元素，即資料表名稱的元素
                if (nameElement) {
                    var tableName = nameElement.textContent.trim();//取得資料表的名稱，並去除首尾的空格
                    selectedTables.push(tableName);//將資料表名稱添加到selectedTables陣列中
                }
            });

            console.log("Selected tables:", selectedTables);

            // 使用 AJAX 發送 POST 請求
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {//MLHttpRequest的狀態改變時，這個函式會被呼叫
                if (xhr.readyState == 4) {//請求已完成
                    if (xhr.status == 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === "success") {
                            // 成功刪除後刷新頁面
                            location.reload();
                        } else {
                            // 處理錯誤
                            alert("Error deleting tables: " + response.message);
                        }
                    } else {
                        // 處理錯誤
                        alert("Error deleting tables");
                    }
                }
            };

            xhr.open("POST", "deletetable.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("selectedTables=" + encodeURIComponent(selectedTables.join(',')) + "&db=" + encodeURIComponent(selectedDb));
            //這部分用來告訴伺服器是哪個資料庫中的資料表需要刪除
        }
    }
    document.addEventListener("DOMContentLoaded", function () {
        console.log("DOMContentLoaded event fired");
        // Your existing code for DOMContentLoaded
        //var deleteButton = document.getElementById("deleteTableButton");
        var deleteButton = document.querySelector("#fixedContent button");

        if (deleteButton) {
            console.log("Delete button clicked");
            deleteButton.addEventListener("click", deleteSelectedTables);
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
    <!---div class="notice">資料庫中沒有任何資料表</div--->
    <!-- !!!!!!!!!!!!!!!!!!!讀取檔案夾名稱作為TABLE!!!!!!!!!!!!!!!!!!! -->
    
    <?php
    // Assuming the selected database is passed as a parameter in the URL
    $selectedDb = isset($_GET['db']) ? $_GET['db'] : null;

    // Loop through the subdirectories
    foreach ($subdirectories as $subdir):
        if ($subdir == $selectedDb) {
            $databasePath = $dir . DIRECTORY_SEPARATOR . $subdir;
            $tableFiles = scandir($databasePath);
            $tables = array_filter($tableFiles, function ($item) {
                return pathinfo($item, PATHINFO_EXTENSION) === 'csv';
            });
    ?>
            <div>
                <strong style="font-size: 20px;" ><?php echo htmlspecialchars($subdir); ?>資料庫</strong>
                <a href="chooseImportTable.php?db=<?php echo htmlspecialchars($subdir); ?>&amp;token=0f5bbd6da033b62c3872f6f1155703a8" class="tab">[匯入]</a>
                <p>建立新資料表</p>
                <div id="fixedContent">
                    <form method="POST" action="createTable.php" onsubmit="return prepareCreateTableUrl()">
                        <input type="hidden" name="db" value="<?php echo htmlspecialchars($subdir); ?>">
                        <div class="formelement">
                            名稱: <input type="text" name="tableName" id="tableNameInputForm" maxlength="64" size="30" required="required"><br>
                            欄位數: <input type="number" min="1" name="num_fields" value="val" required="required">
                            <input type="submit" value="執行">
                        </div>
                    </form>
                </div>
            </div>
            <ul>
                <?php foreach ($tables as $table): ?>
                    <li>
                        <input type="checkbox" >
                        <a href="displayData.php?db=<?php echo htmlspecialchars($subdir); ?>&table=<?php echo htmlspecialchars(pathinfo($table, PATHINFO_FILENAME)); ?>&token=<?php echo htmlspecialchars($token); ?>" title="切換到資料表">
                            <?php echo htmlspecialchars(pathinfo($table, PATHINFO_FILENAME)); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php
        }
    endforeach;
    ?>
    <input type="hidden" name="db" value="<?php echo htmlspecialchars($selectedDb); ?>">

    <!-- 删除按钮 -->
    <div id="fixedContent">
        <button id="deleteTableButton">刪除</button>
    </div>
    </div>

</body>

</html>
