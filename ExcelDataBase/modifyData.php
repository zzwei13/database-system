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
        margin: 0 0.5em;
        margin-left: 240px; 
        transition: margin-left 0.3s ease;
    }
    #page_content div{
        display: block;
    }
    
    #page_content_header {
        padding-top:50px;
        padding-left:50px;
       
    }

    #li_ctrate_db{
        padding-top:20px;
        padding-left:50px;
    }

    input[type=submit], input[type=button], button[id=deleteButton]{
        margin: 6px 14px;
        border: 1px solid #aaa;
        padding: 3px 7px;
        color: #111;
        text-decoration: none;
        background: #ddd;
        border-radius: 12px;
        -webkit-border-radius: 12px;
        -moz-border-radius: 12px;
        text-shadow: 0 1px 0 #fff;

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
    thead th {
    border-right: 1px solid #fff;
}
table caption, table th, table td {
    padding: 0.3em;
    margin: 0.1em;
    vertical-align: top;
    text-shadow: 0 1px 0 #fff;
}
th {
    text-align: left;
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
    // Add this function for Select All
    function toggleSelectAll() {
       //var checkboxes = document.querySelectorAll("#side_navigation_tree ul input[type='checkbox']");
       var checkboxes = document.querySelectorAll("#tableDataBases tbody input[type='checkbox']");
       var selectAllCheckbox = document.getElementById("selectAll");

       for (var i = 0; i < checkboxes.length; i++) {
           checkboxes[i].checked = selectAllCheckbox.checked;
       }
   }

    function modifyData() {
        event.preventDefault();
        // Get form data
        var database = document.getElementsByName("db")[0].value.trim();
        var tableName = document.getElementsByName("table")[0].value.trim();
        
        var inputFields = document.querySelectorAll("#table_modify input[type='text']");
        var fieldValues = [];

        inputFields.forEach(function (field) {
            var fieldValue = field.value.trim();
            if (fieldValue !== "") {
                fieldValues.push(fieldValue);
            }
        });



        
        var formData = new FormData(document.getElementById("modifyForm"));

        formData.append("database", database);
        formData.append("tableName", tableName);
        formData.append("fieldValues", fieldValues.join(','));
            
        console.log("Sending formData:");
        console.log("databaseName: " + database);
        console.log("tableName: " + tableName);
        console.log("fieldValues: " + fieldValues.join(','));

        console.log("FormData:", formData);

        // Make an AJAX request to handle form submission
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "modify.php", true);

        // Set up the callback function to handle the response
        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                window.location.href = "displayData.php?db=" + database +
                    "&table=" + tableName + "&token=";
                // Request was successful, handle the response if needed
                console.log(xhr.responseText);
            } else {
                // Request failed, handle errors if needed
                console.error(xhr.statusText);
            }
        };

        // Send the form data
        xhr.send(formData);

        // Prevent the default form submission
        return false;
    }

   document.addEventListener("DOMContentLoaded", function () {
       var addButton = document.getElementById("addButton");
        if (addButton) {
            addButton.addEventListener("click", addDatabase);
        }

       var selectAllCheckbox = document.getElementById("selectAll");
       var deleteButton = document.getElementById("deleteButton");
       
       if (selectAllCheckbox) {
           selectAllCheckbox.addEventListener("click", toggleSelectAll);
       }
       if (deleteButton) {
        deleteButton.addEventListener("click", deleteSelectedDatabases);
       }

       var form = document.querySelector("form");
       if (form) {
            form.addEventListener("submit", function (event) {
                event.preventDefault(); // Prevent the default form submission
                modifyData();
            });
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
        <form id="modifyForm" class="lock-page " method="post"   enctype="multipart/form-data" novalidate="novalidate">
            <input type="hidden" name="db" value="<?php echo isset($_GET['db']) ? htmlspecialchars($_GET['db']) : ''; ?>">
            <input type="hidden" name="table" value="<?php echo isset($_GET['table']) ? htmlspecialchars($_GET['table']) : ''; ?>">
            <input type="hidden" name="token" value="a49c43ddb92f1ac2140930ed01de50ba">
            <table class="modifyRowTable topmargin" id ="table_modify">
                <thead>
                    <tr>
                        <th>欄位</th>
                        <th>值</th>
                        <th>修改</th>
                    </tr>
                </thead> 
                <tbody>
                    <?php
                    // Retrieve selected rows from query parameters
                    $selectedFirstRow = isset($_GET['ColumnName']) ? explode(',', $_GET['ColumnName']) : [];
                    $selectedRows = isset($_GET['rows']) ? explode(',', $_GET['rows']) : [];
                    

                    foreach (range(0, count($selectedFirstRow) - 1) as $index): 
                        $columnName = $selectedFirstRow[$index];
                        $value = $selectedRows[$index]
                    ?>
                        <tr class="noclick">
                            <td class="columnName">
                                <span class="default_value hide"><?php echo htmlspecialchars($columnName); ?></span>
                                <input type="hidden" name="fields_name[<?php echo htmlspecialchars($columnName); ?>]" value="<?php echo htmlspecialchars($columnName); ?>">
                            </td>          
                            <td class="columnValue">
                                <span class="default_value hide"><?php echo htmlspecialchars($value); ?></span>
                                <input type="hidden" name="fields_toModify[<?php echo htmlspecialchars($columnName); ?>]" value="<?php echo htmlspecialchars($value); ?>">
                            </td>
                            <td class="columnUpdate">
                                
                                <input type="text" id="dataModifyForm" name="fields_update[<?php echo htmlspecialchars($columnName); ?>]" value="<?php echo htmlspecialchars($value); ?>" size="15" data-maxlength="15" data-type="CHAR" class="textfield" autofocus="" required="">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

                
            </table> 
            <div class="formelement">
            <input type="submit" name="modify_data"value="執行">
            </div>
       
    </form>
    </div>
</body>

</html>
