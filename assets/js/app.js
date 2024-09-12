function checkAccount(){
    var emailInput = document.getElementById("email");
    if(emailInput.value === ""){
        alert("Email Address is required.");
        return false;
    }else{
        return true;
    }
}

function checkFeedback(){
    var messageInput = document.getElementById("message");
    if(messageInput.value === ""){
        alert("Feedback message is required.");
        return false;
    }else{
        return true;
    }
}

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

function searchForm(){
    var searchInput = document.getElementById("search");
    if(searchInput.value === ""){
        alert("Please enter search term.\nIt can be a date or word.");
        return false;
    }else{
        return true;
    }
}

function checkRegisterForm(){
    var firstNameInput = document.getElementById("firstname");
    var lastNameInput = document.getElementById("lastname");
    var emailInput = document.getElementById("email");
    var passwordInput = document.getElementById("password");
    if(firstNameInput.value ==="" || lastNameInput.value === "" || emailInput.value === "" || passwordInput.value === ""){
        alert("All fields are required.");
        return false;
    }else if(passwordInput.value.length < 6){
        alert("Password cannot be length than 6 characters.");
        return false;
    } else {
        return true;
    }
}

function checkContactForm(){
    var email = document.getElementById("email");
    var subject = document.getElementById("subject");
    var message = document.getElementById("message");

    if( (email!=undefined && email.value === "") || subject.value === "" || message.value === "" ){
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


function passwordReset(){
    var codeInput = document.getElementById("code");
    var passwordInput = document.getElementById("password");
    var cPasswordInput = document.getElementById("cpassword");
    if(codeInput.value === ""  ||  passwordInput.value === "" || cPasswordInput.value === "" ){
        alert("All fields are required.");
        return false;
    }else if( passwordInput.value.length < 6 || cPasswordInput.value.length <6  ){
        alert("Password cannot be less than 6 characters.");
        return false;
    }else if( passwordInput.value != cPasswordInput.value ){
        alert("Passwords do not match.");
        return false;
    }else{
        return true;
    }
}

// check is code is empty
function activateAccount(){
    var codeInput = document.getElementById("code");
    if(codeInput.value === ""   ){
        alert("Activation code is  required.");
        return false;
    }else{
        return true;
    }
}

// param 1 is the feedback id to be deleted
// param 2 is the board id from which feedback is found.
function deleteFeedback(id, bID){
    var result = confirm("Do you want to delete this feedback ?");
    if(result){
        window.location = "delete-feedback.php?id="+id+"&boardID="+bID;
    }
}

function checkUpdateForm(){
    var firstnameInput = document.getElementById('firstname');
    var lastnameInput = document.getElementById('lastname');
    var passwordInput = document.getElementById('password');
    var confirmPassword = document.getElementById('cpassword');

    if(firstnameInput.value == "" || lastnameInput.value == ""){
        alert("Name is required.\nBoth firstname and lastname.");
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