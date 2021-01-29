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
        //or related to the requests from it and we check this by checking if the hidden value for page is add_news
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
                        ':user_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : $_SESSION['admin_id'],
                    );
                    
                    $cms->query = isset($_SESSION['user_id']) ? 'SELECT * FROM users_table WHERE id = :user_id' : 'SELECT * FROM admin_table WHERE id = :user_id';
                    
                    $result = $cms->result();

                    $result = $result[0];

                    $username = array_key_exists('username', $result) ? $result['username'] : $result['admin_username'];
                
                    //Sanitize data, move image to images folder and return new image name
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

        //Execute this block of code if data is coming from a add_blog page 
        //or related to the requests from it and we check this by checking if the hidden value for page is add_blog
        if($_POST['page'] === 'add_blog') {
            //Execute this block of code if data is coming from the add_blog page
            //and we check this by checking if the hidden value for action is add_blog
            if($_POST['action'] === 'add_blog') {
                //Get data relevant for moving file into images folder and
                //sending filepath to the database
                $cms->filedata = $_FILES['post_image'];

                //Validate data on serverside
                $validate->isEmpty($_POST['post_title'], $_POST['post_body']);
                $validate->imageUploaded($cms->filedata);
                $validate->allowedExtension($cms->filedata);
                $validate->checkImageSize($cms->filedata);
                $validate->errorsExist();

                if($validate->isValid) {
                    //Get the username of the user that is submitting the blog post
                    $cms->data = array(
                        ':user_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : $_SESSION['admin_id'],
                    );
                    
                    $cms->query = isset($_SESSION['user_id']) ? 'SELECT * FROM users_table WHERE id = :user_id' : 'SELECT * FROM admin_table WHERE id = :user_id';
                    
                    $result = $cms->result();
                    
                    $result = $result[0];

                    $username = array_key_exists('username', $result) ? $result['username'] : $result['admin_username'];
                
                    //Sanitize data, move image to images folder and return new image name
                    $post_title = filter_var($_POST['post_title'], FILTER_SANITIZE_STRING);
                    $post_body = filter_var($_POST['post_body'], FILTER_SANITIZE_STRING);
                    $post_image = $cms->move_user_image('blog_images');

                    //Insert data into the database
                    $cms->data = array(
                        ':post_title' => $post_title,
                        ':post_body' => $post_body,
                        ':post_author' => $username,
                        ':post_image' => $post_image,
                        ':approved' => 0,
                    );

                    $cms->query = 'INSERT INTO blog_table(post_title, post_body, post_author, post_image, approved) 
                    VALUES (:post_title, :post_body, :post_author, :post_image, :approved)';

                    $cms->execute_query();

                    $output = array(
                        'success' => 'Successfully addedd the blog post!',
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

                $cms->query = 'UPDATE blog_table SET approved = :approved WHERE id = :id';

                $cms->execute_query();

                $output = array(
                    'success' => true,
                );

                $_SESSION['msg'] = '<div class="alert alert-success">Successfully confirmed the post!</div>';

                echo json_encode($output);
            }
            
            //Execute this block of code if the reject post button is pressed
            if($_POST['action'] == 'reject') {
                $cms->data = array(
                    ':id' => $_POST['id'],
                );

                $cms->query = 'SELECT * FROM blog_table WHERE id = :id';

                $result = $cms->result();
                $result = $result[0];
                
                $path = dirname(__FILE__) . '\\' . $result['post_image'];
                $path = str_replace('/', '\\', $path);

                $cms->query = 'DELETE FROM blog_table WHERE id = :id';

                $cms->execute_query();

                if(file_exists($path)) unlink($path);

                $output = array(
                    'success' => true,
                );

                $_SESSION['msg'] = '<div class="alert alert-success">Successfully rejected the post!</div>';

                echo json_encode($output);
            }
        }
        
        //Execute this block of code if data is coming from a add_category page 
        //or related to the requests from it and we check this by checking if the hidden value for page is add_category
        if($_POST['page'] === 'add_category') {
            //Execute this block of code if data is coming from the add_category page
            //and we check this by checking if the hidden value for action is add_category
            if($_POST['action'] === 'add_category') {
                //Validate data on serverside
                $validate->isEmpty($_POST['category_title'], $_POST['category_desc']);
                $validate->errorsExist();

                if($validate->isValid) {
                    //Sanitize data
                    $category_title = filter_var($_POST['category_title'], FILTER_SANITIZE_STRING);
                    $category_desc = filter_var($_POST['category_desc'], FILTER_SANITIZE_STRING);

                    //Insert data into the database
                    $cms->data = array(
                        ':category_title' => $category_title,
                        ':category_desc' => $category_desc,
                    );

                    $cms->query = 'INSERT INTO forum_categories(cat_title, cat_description) 
                    VALUES (:category_title, :category_desc)';

                    $cms->execute_query();

                    $output = array(
                        'success' => 'Successfully addedd forum category!',
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
        }
        
        //Execute this block of code if data is coming from a add_topic page 
        //or related to the requests from it and we check this by checking if the hidden value for page is add_topic
        if($_POST['page'] === 'add_topic') {
            //Execute this block of code if data is coming from the add_topic page
            //and we check this by checking if the hidden value for action is add_topic
            if($_POST['action'] === 'add_topic') {
                //Validate data on serverside
                $validate->isEmpty($_POST['topic_title'], $_POST['post_content']);
                $validate->errorsExist();

                if($validate->isValid) {
                    //Get the username of the user that is submitting the topic
                    $cms->data = array(
                        ':user_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : $_SESSION['admin_id'],
                    );
                    
                    $cms->query = isset($_SESSION['user_id']) ? 'SELECT * FROM users_table WHERE id = :user_id' : 'SELECT * FROM admin_table WHERE id = :user_id';
                    
                    $result = $cms->result();
                    
                    $result = $result[0];

                    $username = array_key_exists('username', $result) ? $result['username'] : $result['admin_username'];

                    //Sanitize data
                    $topic_title = filter_var($_POST['topic_title'], FILTER_SANITIZE_STRING);
                    $post_content = filter_var($_POST['post_content'], FILTER_SANITIZE_STRING);

                    //Insert data into the topic table and get the id of that row
                    $cms->data = array(
                        ':topic_title' => $topic_title,
                        ':topic_category' => $_POST['category_id'],
                        ':topic_author' => $username,
                        ':approved' => 0,
                    );

                    $cms->query = 'INSERT INTO forum_topics(topic_title, topic_category, topic_author, approved) 
                    VALUES (:topic_title, :topic_category, :topic_author, :approved)';

                    $topic_id = $cms->lastId();

                    //Insert data into posts table
                    $cms->data = array(
                        ':post_content' => $post_content,
                        ':post_topic' => $topic_id,
                        ':post_author' => $username,
                    );

                    $cms->query = 'INSERT INTO forum_posts(post_content, post_topic, post_author) 
                    VALUES (:post_content, :post_topic, :post_author)';

                    $cms->execute_query();

                    $output = array(
                        'success' => 'Successfully addedd forum topic!',
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

            //Execute this block of code if the accept topic button is pressed
            if($_POST['action'] === 'accept') {
                $cms->data = array(
                    ':approved' => 1,
                    ':id' => $_POST['id'],
                );

                $cms->query = 'UPDATE forum_topics SET approved = :approved WHERE id = :id';

                $cms->execute_query();

                $output = array(
                    'success' => true,
                );

                $_SESSION['msg'] = '<div class="alert alert-success">Successfully confirmed the topic!</div>';

                echo json_encode($output);
            }

            //Execute this block of code if the reject topic button is pressed
            if($_POST['action'] == 'reject') {
                $cms->data = array(
                    ':id' => $_POST['id'],
                );

                $cms->query = 'DELETE FROM blog_table WHERE id = :id';

                $cms->execute_query();

                $output = array(
                    'success' => true,
                );

                $_SESSION['msg'] = '<div class="alert alert-success">Successfully rejected the topic!</div>';

                echo json_encode($output);
            }
        }
        
        //Execute this block of code if data is coming from a add_post page 
        //or related to the requests from it and we check this by checking if the hidden value for page is add_post
        if($_POST['page'] === 'add_post') {
            //Execute this block of code if data is coming from the add_post page
            //and we check this by checking if the hidden value for action is add_post
            if($_POST['action'] === 'add_post') {
                //Validate data on serverside
                $validate->isEmpty($_POST['post_content']);
                $validate->errorsExist();

                if($validate->isValid) {
                    //Get the username of the user that is submitting the post
                    $cms->data = array(
                        ':user_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : $_SESSION['admin_id'],
                    );
                    
                    $cms->query = isset($_SESSION['user_id']) ? 'SELECT * FROM users_table WHERE id = :user_id' : 'SELECT * FROM admin_table WHERE id = :user_id';
                    
                    $result = $cms->result();
                    
                    $result = $result[0];

                    $username = array_key_exists('username', $result) ? $result['username'] : $result['admin_username'];

                    //Sanitize data
                    $post_content = filter_var($_POST['post_content'], FILTER_SANITIZE_STRING);

                    //Insert data into the post table
                    $cms->data = array(
                        ':post_content' => $post_content,
                        ':post_topic' => $_POST['topic_id'],
                        ':post_author' => $username,
                    );

                    $cms->query = 'INSERT INTO forum_posts(post_content, post_topic, post_author) 
                    VALUES (:post_content, :post_topic, :post_author)';

                    $cms->execute_query();

                    $output = array(
                        'success' => 'Successfully addedd forum post!',
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
        }
    }
?>
