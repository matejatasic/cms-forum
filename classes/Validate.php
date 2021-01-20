<?php
    class Validate {
        public $errors = [];
        public $allowed_extensions = ['gif', 'jpeg', 'jpg', 'png'];
        public $isValid = false;
        public $msg;

        //Check if error is already in the errors array
        public function checkError($message) {
            if(!in_array($message, $this->errors)) array_push($this->errors, $message);
        }
        
        //Check on serverside if fields are empty
        public function isEmpty(...$fields) {
            $emptyFields = 0;
            foreach($fields as $field) {
                if(trim($field) === '') $emptyFields++; 
            }

            if($emptyFields > 0) {
                $this->msg = '<div class="alert alert-danger">Please fill out all the fileds!</div>';
                $this->checkError($this->msg);
            }
        }

        //Check on serverside if password has valid length
        public function passwordLength($password) {
            if(strlen($password) < 6) {
                $this->msg = '<div class="alert alert-danger">Password must be minimum 6 characters long!</div>';
                $this->checkError($this->msg);
            }
        }

        //Check on serverside if password and password confirmation match
        public function passwordsMatch($password, $confirm_password) {
            if($password !== $confirm_password) {
                $this->msg = '<div class="alert alert-danger">Passwords do not match!</div>';
                $this->checkError($this->msg);
            }
        }

        //Check on serverside if image is uploaded
        public function imageUploaded($image) {
            if(!file_exists($image['tmp_name']) || !is_uploaded_file($image['tmp_name'])) {
                $this->msg = '<div class="alert alert-danger">Choose Image to upload!</div>';
                $this->checkError($this->msg);
            }
        }

        //Check if extension is in the allowed extension array
        public function allowedExtension($file) {
            $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            if(!in_array($file_ext, $this->allowed_extensions)) {
                $this->msg = '<div class="alert alert-danger">That file extension is not allowed!</div>';
                $this->checkError($this->msg);
            }
        }

        //Check if file size is smaller than 5mb
        public function checkImageSize($file) {
            $sizeInMb = number_format($file['size'] / 1024 / 1024, 1);
            if($sizeInMb > 5) {
                $this->msg = '<div class="alert alert-danger">Image size must be below 5MB!</div>';
                $this->checkError($this->msg);
            }
        }

        //Check if there are any errors in errors array
        public function errorsExist() {
            if(count($this->errors) > 0) {
                $this->isValid = false;
            }
            else {
                $this->isValid = true;
            }
        }
    }
?>
