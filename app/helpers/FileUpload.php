<?php
class FileUpload {
    private $uploadPath;
    private $allowedTypes;
    private $maxSize;
    private $error;

    public function __construct($uploadPath = 'public/uploads/') {
        $this->uploadPath = ROOT_PATH . '/' . $uploadPath;
        $this->allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $this->maxSize = 5 * 1024 * 1024; // 5MB
        
        // Create upload directory if it doesn't exist
        if (!file_exists($this->uploadPath)) {
            mkdir($this->uploadPath, 0777, true);
        }
    }

    public function upload($file, $prefix = '') {
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            $this->error = 'No file was uploaded';
            return false;
        }

        if (!in_array($file['type'], $this->allowedTypes)) {
            $this->error = 'File type not allowed. Please upload JPG, PNG or GIF';
            return false;
        }

        if ($file['size'] > $this->maxSize) {
            $this->error = 'File size too large. Maximum size is 5MB';
            return false;
        }

        $fileName = $prefix . '_' . time() . '_' . basename($file['name']);
        $targetPath = $this->uploadPath . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $fileName;
        }

        $this->error = 'Failed to upload file';
        return false;
    }

    public function delete($fileName) {
        $filePath = $this->uploadPath . $fileName;
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }

    public function getError() {
        return $this->error;
    }
}