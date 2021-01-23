<?php
    include('./header.php');
?>
<div class="row pb-3">
    <a href="../index.php" class="btn btn-primary float-left">Back</a>
</div>
<div class="row mb-5">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h3>Admin Login</h3>
            </div>
            <div class="card-body">
                <div id="message"></div>
                <form action="post" id="admin_login_form">
                    <div class="form-group">
                        <label class="float-left">Enter Email Address</label>
                        <input type="text" name="email" id="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="float-left">Enter Password</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                    <div class="form-group clearfix mt-4">
                        <input type="hidden" name="page" value="login">
                        <input type="hidden" name="action" value="admin_login">
                        <input type="submit" name="admin_login" id="admin_login" class="float-left btn btn-info" value="Login">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Custom js -->
<script src="classes/Validate.js"></script>
<script>
    $(document).ready(() => {
        let validate = new Validate();
        let message = $('#message');

        $('#admin_login').click((e) => {
            e.preventDefault();

            let email = $('#email').val();
            let password = $('#password').val();

            //Check if fields are empty
            validate.isEmpty(email, password);
            validate.showErrors();
            console.log($('#admin_login_form').serialize());
            //If data is valid send it to the ajax_action.php 
            if(validate.isValid) {
                $.ajax({
                    url: "ajax_action.php",
                    method: "POST",
                    data: $('#admin_login_form').serialize(),
                    dataType: "json",
                    beforeSend: () => {
                        $('#admin_login').attr('disabled', 'disabled');
                        $('#admin_login').val('Please wait...');
                    },
                    success: (data) => {
                        if(data.success) {
                            validate.redirect('./index.php');
                        }
                        else {
                            validate.errors = data.error;
                            validate.showErrors(message);
                            $('#admin_login').attr('disabled', false);
                            $('#admin_login').val('Login');
                        }
                    },
                    error: (xhr, ajaxOptions, thrownError) => {
                        console.log(xhr.status);
                        console.log(thrownError);
                        message.html('<div class="alert alert-danger">There was an error while trying to login...</div>');
                    },
                });
            }
        })
    });
</script>
<?php
    include('./footer.php');
?>