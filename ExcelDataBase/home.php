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
   function addDatabase() {
        var databaseName = document.getElementsByName("new_db")[0].value.trim();

        if (databaseName !== "") {
            // Use AJAX to send the new database name to the server for creation
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Handle the response if needed
                    location.reload(); // Refresh the page for simplicity
                }
            };
            xhr.open("POST", "adddatabase.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("databaseName=" + encodeURIComponent(databaseName));
            // Prevent the default form submission
            return false;
        } else {
            alert("Please enter a valid database name.");
            return false; // Prevent form submission if the database name is empty
        }
    }

    function deleteSelectedDatabases() {
        var checkboxes = document.querySelectorAll("#tableDataBases tbody input[type='checkbox']:checked");

        if (checkboxes.length > 0) {
            var selectedDatabases = [];
            for (var i = 0; i < checkboxes.length; i++) {
                // Access the text content of the <td> element in the same row
                var databaseName = checkboxes[i].closest("tr").querySelector(".name a").textContent.trim();
                selectedDatabases.push(databaseName);
            }

            // Use AJAX to send the selected databases to the server for deletion
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        // Remove the selected items from the page
                        for (var i = 0; i < checkboxes.length; i++) {
                            var listItem = checkboxes[i].closest("tr");
                            listItem.parentNode.removeChild(listItem);
                        }
                    } else {
                        // Handle error if needed
                        alert("Error deleting databases");
                    }
                }
            };
            xhr.open("POST", "deletedatabase.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("selectedDatabases=" + encodeURIComponent(selectedDatabases.join(',')));
        }
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
        <div id="page_content_header">
            <font color=#2d3e4e size=5px>資料庫</font>

        </div>
        <ul id="li_ctrate_db" class="no_bullets">
            <form class="post" action="home.php" onsubmit="return addDatabase()">
                <strong>
                    <label for="text_create_db">建立新資料庫</label>
                </strong><br>
                <input type="hidden" name="token" value="0f5bbd6da033b62c3872f6f1155703a8">
                <input type="hidden" name="reload" value="1">
                <input type="text" name="new_db" value="" maxlength="64" class="textfield" id="text_create_db" required="" placeholder="資料庫名稱">
                <input type="submit" value="建立" id="addButton">
            </form>
        </ul>

        <div id="tableslistcontainer">
        
            <form class="ajax" action="home.php">
                <input type="hidden" name="pos" value="0">
                <input type="hidden" name="dbstats" value="">
                <input type="hidden" name="sort_by" value="SCHEMA_NAME">
                <input type="hidden" name="sort_order" value="asc">
                <input type="hidden" name="token" value="0f5bbd6da033b62c3872f6f1155703a8">
                <!-- Add the Select All checkbox and label -->
        
                <table id="tableDataBases" class="data">
                    <thead></thead>
                    <tbody>
                        <?php foreach ($subdirectories as $subdir): ?>
                            <tr>
                                <td class="tool">
                                    <input type="checkbox" name="selected_dbs[]" class="checkall" title="<?php echo $subdir; ?>" value="<?php echo $subdir; ?>">
                                </td>
                                <td class="name">
                                <a href="displayTable.php?pos=0&amp;db=<?php echo $subdir; ?>&sort_by=SCHEMA_NAME&amp;sort_order=desc&amp;token=0f5bbd6da033b62c3872f6f1155703a8">
                                        <?php echo $subdir; ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
                <!-- !!!!!!!!!!!!!!!!!!!新增了全選的框框!!!!!!!!!!!!!!!!!!! -->
                <div id="fixedContent">
                    <input type="checkbox" id="selectAll"> <!-- New checkbox for Select All -->
                    <label for="selectAll"><font color= #7373B9 size=2px>全選</label>
                </div>
                <!-- 新增删除按钮 -->
                <div id="fixedContent">
                    <button id="deleteButton">刪除</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
