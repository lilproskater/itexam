function Validate_form() {
    var form_name = document.getElementById("name");
    var form_surname = document.getElementById("surname");
    var form_username = document.getElementById("username");
    var form_password = document.getElementById("password");
    var form_confirm_password = document.getElementById("confirm_password");
    var sur_name_pattern = /^[а-яА-ЯёЁ]+$/;
    var usr_pass_pattern = /^[a-zA-Z0-9]+$/;
    if (!sur_name_pattern.test(form_name.value)) {
        form_name.classList.add('invalid');
        return false;
    }
    else {
        form_name.classList.remove('invalid');
    }
    if (!sur_name_pattern.test(form_surname.value)) {
        form_surname.classList.add('invalid');
        return false;
    }
    else {
        form_surname.classList.remove('invalid');
    }
    if (!usr_pass_pattern.test(form_username.value)) {
        form_username.classList.add('invalid');
        return false;
    }
    else {
        form_username.classList.remove('invalid');
    }
    if (!usr_pass_pattern.test(form_password.value)) {
        form_password.classList.add('invalid');
        return false;
    }
    else {
        form_password.classList.remove('invalid');
    }
    if (!usr_pass_pattern.test(form_confirm_password.value)) {
        form_confirm_password.className += " invalid";
        return false;
    }
    else {
        form_confirm_password.classList.remove('invalid');
    }
}