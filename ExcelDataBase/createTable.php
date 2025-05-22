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
        margin-left: 240px; 
        transition: margin-left 0.3s ease;
    }
    #page_content div{
        display: block;
    }
    #page_content ul{
        padding:50px;
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

    table {
        display: table;
        border-collapse: collapse;
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
    table th {
        font-weight: bold;
        color: #000;
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
       var checkboxes = document.querySelectorAll("#side_navigation_tree ul input[type='checkbox']");
       var selectAllCheckbox = document.getElementById("selectAll");

       for (var i = 0; i < checkboxes.length; i++) {
           checkboxes[i].checked = selectAllCheckbox.checked;
       }
   }

   /**新增欄位**/ 
   
    function addFields() {
        var numFields = document.getElementsByName("added_fields")[0].value;
        var tableBody = document.getElementById("table_columns").getElementsByTagName('tbody')[0];

        for (var i = 0; i < numFields; i++) {
            var newRow = tableBody.insertRow(tableBody.rows.length);
            var cell = newRow.insertCell(0);
            var inputId = 'field' + (tableBody.rows.length-1);

            cell.innerHTML = '<input id="' + inputId + '" type="text" name="field_name[' + (tableBody.rows.length - 2) + ']" maxlength="64" class="textfield" title="欄位" size="10" value="">';
        }
    }
  

    //**新增資料表**//
    function addTable() {
        event.preventDefault(); // prevent the default form submission

        var database = document.getElementsByName("database")[0].value.trim();
        var tableName = document.getElementById('tableNameInputForm').value.trim();

        if (tableName !== "") {
            // Count non-empty input fields
            var numFields = 0;
            var inputFields = document.querySelectorAll("#table_columns input[type='text']");
            var fieldValues = [];

            inputFields.forEach(function (field) {
                var fieldValue = field.value.trim();
                if (fieldValue !== "") {
                    numFields++;
                    fieldValues.push(fieldValue);
                }
            });
           
            var formData = new FormData(document.forms[0]);
            formData.append("database", database);
            formData.append("tableName", tableName);
            formData.append("numFields", numFields);
            formData.append("fieldValues", fieldValues.join(',')); // Convert array to comma-separated string

            console.log("Sending formData:");
            console.log("databaseName: " + database);
            console.log("tableName: " + tableName);
            console.log("numFields: " + numFields);
            console.log("fieldValues: " + fieldValues.join(','));
           
            // Use AJAX to send the new table name and column count to the server for creation
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        window.location.href = "displayData.php?db=" + database+
                            "&table=" + tableName +"&token=" ;
                    }
                }
            };
            xhr.open("POST", "addtable.php", true);
        
            // Collect form data and send it with the request
           
            xhr.send(formData);
            

        } else {
            alert("Please enter a valid table name.");
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        var form = document.querySelector("form");

        if (form) {
            form.addEventListener("submit", function (event) {
                event.preventDefault(); // Prevent the default form submission
                addTable();
            });
        }

       var selectAllCheckbox = document.getElementById("selectAll");
       var deleteButton = document.getElementById("deleteButton");
       
       if (selectAllCheckbox) {
           selectAllCheckbox.addEventListener("click", toggleSelectAll);
       }

       if (deleteButton) {
        deleteButton.addEventListener("click", deleteSelectedDatabases);
       }

   });

</script>

<body>
    <div id ="side_navigation" style="width:240px;">
        <div id="side_navigation_resizer" onclick="toggleSideNavigation()"></div>
        <div id="side_navigation_collapser" title="隱藏面板" onclick="toggleSideNavigation()">← </div>
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
        <ul>              
            <p>建立新資料表</p>
            <div id="fixedContent">
                <div class="formelement">
                    名稱: <input type="text" id="tableNameInputForm" name="table_name" size="40" maxlength="64" value="<?php echo isset($_POST['tableName']) ? htmlspecialchars($_POST['tableName']) : ''; ?>" class="textfield" autofocus="" required="">
                    新增<input type="number" id="added_fields" name="added_fields" size="2" value="1" min="1" onfocus="this.select()">欄
                    <input type="button" name="add_num_fields" value="新增欄位" onclick="addFields()">

                </div>
                <!-- !!!!!!!!!!!!!!!!!!!設置table欄位名稱!!!!!!!!!!!!!!!!!!! -->
                <form method="post"  action="">
                
                    <input type="hidden" name="database" value="<?php echo isset($_POST['db']) ? htmlspecialchars($_POST['db']) : ''; ?>">
                    <input type="hidden" name="reload" value="1">
                    <input type="hidden" name="orig_num_fields" value="<?php echo isset($_POST['num_fields']) ? htmlspecialchars($_POST['num_fields']) : ''; ?>">
                    <table id="table_columns">
                        <tbody>
                            <tr>
                                <th>名稱</th>
                            </tr>

                            <?php
                            $numFields = isset($_POST['num_fields']) ? intval($_POST['num_fields']) : 0;

                            for ($i = 0; $i < $numFields; $i++) {
                                $inputId = 'field' . ($i + 1);
                                $fieldValue = isset($_POST['field_name'][$i]) ? htmlspecialchars($_POST['field_name'][$i]) : '';
                                ?>
                                <tr class="columnName">
                                    <td>
                                        <input id="<?php echo $inputId; ?>" type="text" name="field_name[<?php echo $i; ?>]" maxlength="64" class="textfield" title="欄位" size="10" value="<?php echo $fieldValue; ?>">
                                    </td>
                                </tr>
                                
                            <?php
                            }
                            ?>
                            
                        </tbody>
                       
                    </table>
                    <div class="formelement">
                        <input type="submit" name="do_save_data" value="儲存">
                    </div>
                </form>
                
                
                
            </div>
            
        </ul>        
        
    </div>
</body>

</html>
