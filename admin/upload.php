<?php
header('Content-Type: application/json'); // Ensure response is JSON

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // Check for upload errors
    if ($file['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($file['name']);

        // Attempt to move the uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            echo json_encode(['url' => $uploadFile]); // Return the file URL
        } else {
            echo json_encode(['error' => 'Failed to upload file.']);
        }
    } else {
        echo json_encode(['error' => 'File upload error: ' . $file['error']]);
    }
} else {
    echo json_encode(['error' => 'No file uploaded.']);
}
?>
