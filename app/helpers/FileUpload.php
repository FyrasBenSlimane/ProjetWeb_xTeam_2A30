<?php
class FileUpload {
    private $uploadPath;
    private $allowedTypes;
    private $maxSize;
    private $error;

    public function __construct($uploadPath = 'public/uploads/') {
        $this->uploadPath = ROOT_PATH . '/' . $uploadPath;
        
        // Expanded allowed file types to include PDF and other document formats
        $this->allowedTypes = [
            'image/jpeg',
            'image/png', 
            'image/gif', 
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/zip',
            'application/x-zip-compressed',
            'text/plain',
            'text/csv',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];
        
        // Increased max size to 10MB
        $this->maxSize = 10 * 1024 * 1024; // 10MB
        
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
            $this->error = 'File type not allowed. Please upload JPG, PNG, GIF, PDF, DOC, DOCX, ZIP, TXT, CSV, XLS or XLSX';
            return false;
        }

        if ($file['size'] > $this->maxSize) {
            $this->error = 'File size too large. Maximum size is 10MB';
            return false;
        }

        // Generate a more reliable unique filename
        $fileName = $prefix . '_' . time() . '_' . sprintf('%04d', rand(0, 9999)) . '_' . $this->sanitizeFileName(basename($file['name']));
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

    /**
     * Sanitize the filename to remove special characters and ensure it's safe
     */
    private function sanitizeFileName($fileName) {
        // Replace spaces with underscores
        $fileName = str_replace(' ', '_', $fileName);
        
        // Remove any character that is not a letter, a digit, underscore, dot, or hyphen
        $fileName = preg_replace("/[^A-Za-z0-9_.-]/", '', $fileName);
        
        // Make sure the file has a valid extension
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        if (empty($extension)) {
            $fileName .= '.txt';
        }
        
        return $fileName;
    }

    /**
     * Get the file extension
     */
    public function getFileExtension($fileName) {
        return strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    }

    /**
     * Get file type/icon based on extension
     */
    public function getFileTypeIcon($fileName) {
        $extension = $this->getFileExtension($fileName);
        
        switch($extension) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                return 'fa-file-image';
            case 'pdf':
                return 'fa-file-pdf';
            case 'doc':
            case 'docx':
                return 'fa-file-word';
            case 'xls':
            case 'xlsx':
            case 'csv':
                return 'fa-file-excel';
            case 'zip':
            case 'rar':
                return 'fa-file-archive';
            case 'txt':
                return 'fa-file-alt';
            default:
                return 'fa-file';
        }
    }
}