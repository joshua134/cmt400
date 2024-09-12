function checkLoginForm(){
    var emailInput = document.getElementById("email");
    var passwordInput = document.getElementById("password");
    if(emailInput.value === "" || passwordInput.value === ""){
        alert("All fields are required.");
        return false;
    }else{
        return true;
    }
}



function departmentCreate(){
    var nameInput = document.getElementById("title");
    if(nameInput.value === "" ){
        alert("Department Name is required.");
        return false;
    }else{
        return true;
    }
}

function departmentUpdate(){
    var nameInput = document.getElementById("title");
    if(nameInput.value === "" ){
        alert("Department Name is required.");
        return false;
    }else{
        return true;
    }
}

function confirmDelete(departmentID){
    // show confirmation dialog
    var result = confirm("Are you sure you want to delete this department ?");
    // if user clicks yes/ok/true
    // send him to delete-department page
    if(result){
        window.location = "delete-department.php?departmentID="+departmentID;
    }
}

function confirmNoticeDelete(noticeID, notice){
    // show confirmation dialog
    var result = confirm("Are you sure you want to delete this notice ?");
    // if user clicks yes/ok/true
    // send him to delete-department page
    if(result){
        if(notice){
            window.location = "delete-notice.php?noticeID="+noticeID;
        }
    }
}


function checkUpdateForm(){
    var firstnameInput = document.getElementById("firstname");
    var lastnameInput = document.getElementById("lastname");

    var passwordInput = document.getElementById('password');
    var confirmPassword = document.getElementById('cpassword');

    if(firstnameInput.value === "" || lastnameInput.value === ""){
        alert("Both name is required.");
        return false;
    } else if( (passwordInput.value === "" && confirmPassword.value != "") || (passwordInput.value != "" && confirmPassword.value === "")  ){
        alert("Both password and confirm password are required.");
        return false;
    } else if ((passwordInput.value != "" && passwordInput.value.length < 6) || (confirmPassword.value != "" && confirmPassword.value.length < 6) ){
        alert("Password cannot be less than 6 characters.");
        return false;
    } else if(passwordInput.value != confirmPassword.value){
        alert("Password do not match.");
        return false;
    }else{
        return true;
    }
}

function confirmDeleteUser(userID){
    // show confirmation dialog
    var result = confirm("Are you sure you want to delete this user account ?");
    // if user clicks yes/ok/true
    // send him to delete-department page
    if(result){
        window.location = "delete-user.php?userID="+userID;
    }
}


function createNotice(){
    var title = document.getElementById("title");
    var content = document.getElementById("content");

    if(title.value === "" || content.value === ""){
        alert("Title and Content are required.");
        return false;
    }else{
        return true;
    }
}

function noticeUpdate(){
    var title = document.getElementById("title");
    var content = document.getElementById("content");

    if(title.value === "" || content.value === ""){
        alert("Title and Content are required.");
        return false;
    }else{
        return true;
    }
}

function lecturerAccountCreate(){
    // var department = document.getElementById("department");
    // console.log(department.value);
    var firstnameInput = document.getElementById("firstname");
    var lastnameInput = document.getElementById("lastname");
    var emailInput = document.getElementById("email");
    var passwordInput = document.getElementById("password");

    if( firstnameInput.value === "" || lastnameInput.value === "" || emailInput.value === "" || passwordInput.value === "" ){
        alert("All fields are required.");
        return false;
    }else{
        return true;
    }
}

function showPassword(){
    var passwordInput = document.getElementById("password");
    var spanPwd = document.getElementById("span-pwd");
    
    if(passwordInput.type == "password"){
        spanPwd.textContent = "Hide";
        passwordInput.type = "text";
    }else{
        spanPwd.textContent = "Show";
        passwordInput.type = "password";
    }
}

function showCPassword(){
    var passwordInput = document.getElementById("cpassword");
    var spanPwd = document.getElementById("span-cpwd");
    
    if(passwordInput.type == "password"){
        spanPwd.textContent = "Hide";
        passwordInput.type = "text";
    }else{
        spanPwd.textContent = "Show";
        passwordInput.type = "password";
    }
}
