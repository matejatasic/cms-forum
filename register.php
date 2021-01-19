<?php
    include('header.php');
?>
<div class="row pb-3">
    <a href="index.php" class="btn btn-primary float-left">Back</a>
</div>
<div class="row mb-5">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h3>Register</h3>
            </div>
            <div class="card-body">
                <div id="message"></div>
                <form action="post" id="register_form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="float-left">Email Address</label>
                        <input type="text" name="email" id="email" class="form-control" placeholder="Enter Email Address">
                    </div>
                    <div class="form-group">
                        <label class="float-left">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password">
                    </div>
                    <div class="form-group">
                        <label class="float-left">Confirm Password</label>
                        <input type="password" name="conf_password" id="conf_password" class="form-control" placeholder="Confirm Password">
                    </div>
                    <div class="form-group">
                        <label class="float-left">Username</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Enter Username">
                    </div>
                    <div class="form-group">
                        <label class="float-left">Gender</label>
                        <select class="form-control" name="gender" id="gender">
                            <option>Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="float-left">User Image</label>
                        <input type="file" class="form-control-file" name="user_image" id="user_image">
                        <small class="float-left form-text text-muted">Supported file extensions: gif, jpeg, jpg, png</small>
                    </div>
                    <div class="form-group clearfix mt-5">
                        <input type="hidden" name="page" value="register">
                        <input type="hidden" name="action" value="user_register">
                        <input type="submit" name="user_register" id="user_register" class="float-left btn btn-info" value="Register">
                    </div>
                </form>
                <div class="text-center clear-both mt-2">
                    <a href="login.php" class="btn-default font-2">Login</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom js -->
<script src="./classes/Validate.js"></script>
<script>
    $(document).ready(() => {
        let validate = new Validate();
        let message = $('#message');

        $('#user_register').click((e) => {
            e.preventDefault();

            email = $('#email').val();
            password = $('#password').val();
            conf_password = $('#conf_password').val();
            username = $('#username').val();
            gender = $('#gender').val();
            image = $('#user_image');

            //Check if all fields are valid
            validate.isEmpty(email, password, conf_password, username, gender);
            validate.passwordLength(password);  
            validate.passwordsMatch(password, conf_password);
            validate.imageUploaded(image);
            validate.showErrors(message);  

            //If all fields are valid, send the data to the ajax_action.php
            if(validate.isValid) {
                $.ajax({
                    url: "ajax_action.php",
                    method: "POST",
                    data: $('#register_form').serialize(),
                    dataType: "json",
                    beforeSend: () => {
                        $('#user_register').attr('disabled', 'disabled');
                        $('#user_register').val('Please wait...');
                    },
                    success: (data) => {
                        if(data.success) {
                            validate.showSuccess(message, data.success);
                        }
                        else {
                            if(typeof(data.error) === 'string') {
                                message.html('<div class="alert alert-danger">'+data.error+'</div>');
                            }
                            else {
                                validate.errors = data.error;
                                validate.showErrors(message);
                            }
                        }
                        $('#user_register').attr('disabled', false);
                        $('#user_register').val('Register');
                    },
                    error: (xhr, ajaxOptions, thrownError) => {
                        console.log(xhr.status);
                        console.log(thrownError);
                        message.html('<div class="alert alert-danger">There was an error while trying to register...</div>');
                    },
                });
            }
        });
    });
</script>
<?php
    include('footer.php');
?>