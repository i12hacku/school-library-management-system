<?php
function uploadCoverImage($file) {
    $target_dir = "../uploads/covers/";
    $imageFileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '.' . $imageFileType;
    $target_file = $target_dir . $new_filename;
    
    // Check if image file is an actual image
    $check = getimagesize($file['tmp_name']);
    if ($check === false) {
        return ['error' => 'File is not an image'];
    }
    
    // Check file size (max 2MB)
    if ($file['size'] > 2000000) {
        return ['error' => 'File is too large (max 2MB)'];
    }
    
    // Allow certain file formats
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        return ['error' => 'Only JPG, JPEG, PNG & GIF files are allowed'];
    }
    
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return ['filename' => $new_filename];
    } else {
        return ['error' => 'Error uploading file'];
    }
}

function uploadPdfFile($file) {
    $target_dir = "../uploads/pdfs/";
    $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '.' . $fileType;
    $target_file = $target_dir . $new_filename;
    
    // Check file size (max 5MB)
    if ($file['size'] > 5000000) {
        return ['error' => 'File is too large (max 5MB)'];
    }
    
    // Allow only PDF
    if ($fileType != 'pdf') {
        return ['error' => 'Only PDF files are allowed'];
    }
    
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return ['filename' => $new_filename];
    } else {
        return ['error' => 'Error uploading file'];
    }
}
?>