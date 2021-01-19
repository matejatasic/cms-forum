<?php
    include('./classes/CMS.php');
    include('./classes/Validate.php');

    $cms = new CMS;
    $validate = new Validate();
    $output = array();

    if($_POST['page']) {
        //Execute this block of code if the page from which the data is coming is from a register page
        //and check that by checking if the hidden value for page is register
        if($_POST['page'] === 'register') {
            //Execute this block of code if if data is coming from a register page for normal users
            //whick we check by checking if the hidden value for action is user_register
            if($_POST['action'] === 'user_register') {
                $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
                $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    
                if($email) {
                    $cms->data = array(
                        ':email' => $email,
                    );
                    $cms->query = "SELECT * FROM users_table WHERE email = :email";
                    $total_rows = $cms->total_rows();
    
                    if($total_rows > 0) {
                        $output = array(
                            'error' => 'Email already exists!'
                        );
                    }
                    else {
                        //Get all data relevant for moving file into images folder and
                        //sending filepath to the database
                        $cms->filedata = $_FILES['user_image'];

                        //Validate data on serverside
                        $validate->isEmpty($email, $_POST['password'], $_POST['conf_password'], $_POST['username']);
                        $validate->passwordLength($_POST['password']);
                        $validate->passwordsMatch($_POST['password'], $_POST['conf_password']);
                        $validate->imageUploaded($cms->filedata);
                        $validate->extensionAllowed($cms->filedata);
                        $validate->checkImageSize($cms->filedata);
                        $validate->errorsExist();

                        if($validate->isValid) {
                            //Sanitize data, encrypt the password, store gender in a variable, move image to images folder
                            //and return new image name
                            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
                            $gender = $_POST['gender'];
                            $user_image = $cms->move_user_image();

                            $cms->data = array(
                                ':email' => $email,
                                ':password' => $password,
                                ':username' => $username,
                                ':gender' => $gender,
                                ':user_image' => $user_image
                            );
    
                            $cms->query = "INSERT INTO users_table(email, password, username, gender, user_image) 
                            VALUES(:email, :password, :username, :gender, :user_image)";
    
                            $cms->execute_query();
                        
                            $output = array(
                                'success' => 'Succesfully Registered',
                            );
                        }
                        else {
                            $output = array(
                                'error' => $validate->errors,
                            );
                        } 
                    }
                }
                else {
                    $output = array(
                        'error' => 'Email is not valid!',
                    );
                }
    
                echo json_encode($output);
    
            }
        }
        
        //Execute this block of code if data is coming from a login page
        //and we check this by checking if the hidden value for page is login
        if($_POST['page'] === 'login') {
            //Execute this block of code if data is coming from a login page for normal users
            //and we check this by checking if the hidden value for action is user_login
            if($_POST['action'] === 'user_login') {
                $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
                if($email) {
                    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
                    
                    $cms->data = array(
                        ':email' => $email,
                    );
    
                    $cms->query = 'SELECT * FROM users_table WHERE email = :email';
    
                    $total_rows = $cms->total_rows();
                    
                    if($total_rows > 0) {
                        $result = $cms->result();
                        $result = $result[0];
    
                        if(password_verify($_POST['password'], $result['password'])) {
                            $_SESSION['user_id'] = $result['id'];
                            $output = array(
                                'success' => true,
                            );
                        }
                        else {
                            $output = array(
                                'error' => 'Wrong Password!',
                            );
                        }
                    }
                    else {
                        $output = array(
                            'error' => 'Wrong Email Address!',
                        );
                    }
                }
                else {
                    $output = array(
                        'error' => 'Email is not valid!',
                    );
                }
    
                echo json_encode($output);
            }
        }
    }
?>