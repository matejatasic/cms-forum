class Validate {
    errors = [];
    isValid = false;
    msg;

    //Checks if the error is already in errors array
    checkError(message) {
        if(!this.errors.includes(message)) this.errors.push(message);
    }

    //Checks if fields are empty
    isEmpty(...fields) {
        let empty = 0;
        fields.forEach((field) => field.trim() === '' ? empty++ : empty);
        
        if(empty > 0) {
            this.msg = '<div class="alert alert-danger">Please fill out all the fields!</div>';
            this.checkError(this.msg);
        }
    };

    //Checks if password is minimum 6 characters long
    passwordLength(password) {
        if(password.length < 6) {
            this.msg = '<div class="alert alert-danger">Password must be minimum 6 characters long!</div>';
            this.checkError(this.msg);
        }
    }

    //Checks if password and confirmation password are the same
    passwordsMatch(password, confirm_password) {
        if(password !== confirm_password) {
            this.msg = '<div class="alert alert-danger">Passwords do not match!</div>';
            this.checkError(this.msg);
        }
    }

    //Check if file input is empty
    imageUploaded(image) {
        if(image.files.length == 0) {
            this.msg = '<div class="alert alert-danger">Chose Image to upload!</div>';
            this.checkError(this.msg);
        }
    }

    //Shows all errors if there are any or gives the 
    //valid property boolean true value
    showErrors(message) {
        if(this.errors.length > 0) {
            let error = this.errors.join('');
            message.html(error);
            this.isValid = false;
            this.errors = [];
        }
        else {
            this.isValid = true;
        }
    }

    //Show success message
    showSuccess(messageDiv, message) {
        this.isValid = true
        messageDiv.html('<div class="alert alert-success">'+message+'</div>');
        this.errors = [];
    }

    //Redirect to another page
    redirect($path) {
        window.location.replace($path);
    }
}

