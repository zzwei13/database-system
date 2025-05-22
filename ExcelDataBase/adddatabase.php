<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['databaseName'])) {
    $databaseName = $_POST['databaseName'];
    $dir = "C:\AppServ\www\ExcelDataBase";  // Replace with your directory path
    
    // Validate the database name to prevent security issues
    if (preg_match('/^[a-zA-Z0-9_-]+$/', $databaseName)) {
        $databasePath = $dir . DIRECTORY_SEPARATOR . $databaseName;

        // Check if the database already exists
        if (!file_exists($databasePath)) {
            // Create the new database directory
            mkdir($databasePath);
            

            // Respond with success
            echo "Database $databaseName created successfully.";
        } else {
            // Respond with an error if the database already exists
            echo "Database $databaseName already exists.";
        }
    } else {
        // Respond with an error if the database name is not valid
        echo "Invalid database name.";
    }
}
?>
