<?php
    include('./classes/CMS.php');
    include('./classes/Validate.php');

    $cms = new CMS;
    $validate = new Validate();
    $output = array();

    if(isset($_POST['page'])) {
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
                        //Get data relevant for moving file into images folder and
                        //sending filepath to the database
                        $cms->filedata = $_FILES['user_image'];

                        //Validate data on serverside
                        $validate->isEmpty($email, $_POST['password'], $_POST['conf_password'], $_POST['username']);
                        $validate->passwordLength($_POST['password']);
                        $validate->passwordsMatch($_POST['password'], $_POST['conf_password']);
                        $validate->imageUploaded($cms->filedata);
                        $validate->allowedExtension($cms->filedata);
                        $validate->checkImageSize($cms->filedata);
                        $validate->errorsExist();

                        if($validate->isValid) {
                            //Sanitize data, encrypt the password, store gender in a variable, move image to images folder
                            //and return new image name
                            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
                            $gender = $_POST['gender'];
                            $user_image = $cms->move_user_image('user_images');

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
    
                $validate->errors = [];

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

            if($_POST['action'] === 'admin_login') {
                $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
                if($email) {
                    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
                    
                    $cms->data = array(
                        ':email' => $email,
                    );
    
                    $cms->query = 'SELECT * FROM admin_table WHERE admin_email = :email';
    
                    $total_rows = $cms->total_rows();
                    
                    if($total_rows > 0) {
                        $result = $cms->result();
                        $result = $result[0];
    
                        if(password_verify($_POST['password'], $result['admin_password'])) {
                            $_SESSION['admin_id'] = $result['id'];
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

        //Execute this block of code if data is coming from a add_news page
        //and we check this by checking if the hidden value for page is add_news
        if($_POST['page'] === 'add_news') {
            //Execute this block of code if data is coming from the add_news page
            //and we check this by checking if the hidden value for action is add_news
            if($_POST['action'] == 'add_news') {
                //Get data relevant for moving file into images folder and
                //sending filepath to the database
                $cms->filedata = $_FILES['news_image'];

                //Validate data on serverside
                $validate->isEmpty($_POST['news_title'], $_POST['news_body']);
                $validate->imageUploaded($cms->filedata);
                $validate->allowedExtension($cms->filedata);
                $validate->checkImageSize($cms->filedata);
                $validate->errorsExist();

                if($validate->isValid) {
                    //Get the username of the user that is submitting the news
                    $cms->data = array(
                        ':user_id' => $_SESSION['user_id'],
                    );
                    
                    $cms->query = 'SELECT * FROM users_table WHERE id = :user_id';
                    
                    $result = $cms->result();
                    
                    $username = $result[0]['username'];
                
                    //Sanitize data, encrypt the password, store gender in a variable, move image to images folder
                    //and return new image name
                    $news_title = filter_var($_POST['news_title'], FILTER_SANITIZE_STRING);
                    $news_body = filter_var($_POST['news_body'], FILTER_SANITIZE_STRING);
                    $news_image = $cms->move_user_image('news_images');

                    //Insert data into the database
                    $cms->data = array(
                        ':news_title' => $news_title,
                        ':news_body' => $news_body,
                        ':news_author' => $username,
                        ':news_image' => $news_image,
                        ':approved' => 0,
                    );

                    $cms->query = 'INSERT INTO news_table(news_title, news_body, news_author, news_image, approved) 
                    VALUES (:news_title, :news_body, :news_author, :news_image, :approved)';

                    $cms->execute_query();

                    $output = array(
                        'success' => 'Successfully addedd the news!',
                    );
                }
                else {
                    $output = array(
                        'error' => $validate->errors,
                    );
                }
                
                $validate->errors = [];

                echo json_encode($output);
            }

            //Execute this block of code if the accept news button is pressed
            if($_POST['action'] == 'accept') {
                $cms->data = array(
                    ':approved' => 1,
                    ':id' => $_POST['id'],
                );

                $cms->query = 'UPDATE news_table SET approved = :approved WHERE id = :id';

                $cms->execute_query();

                $output = array(
                    'success' => true,
                );

                $_SESSION['msg'] = '<div class="alert alert-success">Successfully confirmed the news!</div>';

                echo json_encode($output);
            }
            
            //Execute this block of code if the reject news button is pressed
            if($_POST['action'] == 'reject') {
                $cms->data = array(
                    ':id' => $_POST['id'],
                );

                $cms->query = 'SELECT * FROM news_table WHERE id = :id';

                $result = $cms->result();
                $result = $result[0];
                
                $path = dirname(__FILE__) . '\\' . $result['news_image'];
                $path = str_replace('/', '\\', $path);

                $cms->query = 'DELETE FROM news_table WHERE id = :id';

                $cms->execute_query();

                if(file_exists($path)) unlink($path);

                $output = array(
                    'success' => true,
                );

                $_SESSION['msg'] = '<div class="alert alert-success">Successfully rejected the news!</div>';

                echo json_encode($output);
            }
        }
    }
?>
