<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedDatabase = isset($_POST['selectedDatabase']) ? $_POST['selectedDatabase'] : null;

    // Check if a file was uploaded successfully
    if (isset($_FILES['input_import_file']) && $_FILES['input_import_file']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = "C:\\AppServ\\www\\ExcelDataBase\\" . $selectedDatabase . DIRECTORY_SEPARATOR;
        $uploadFile = $uploadDir . basename($_FILES['input_import_file']['name']);

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES['input_import_file']['tmp_name'], $uploadFile)) {
            // Add your code to process the imported file (e.g., database update) here
            
            // Redirect to displayTable.php upon successful upload
            $subdir = urlencode($selectedDatabase); // URL encode the database name
            $redirectUrl = "displayTable.php?pos=0&db={$subdir}&sort_by=SCHEMA_NAME&sort_order=desc&token=0f5bbd6da033b62c3872f6f1155703a8";
            header("Location: {$redirectUrl}");
            exit;
        } else {
            echo "Error moving uploaded file.";
        }
    } else {
        echo "File upload failed.";
    }
} else {
    // Handle the case where the script is accessed directly
    echo "Invalid request.";
}
?>
