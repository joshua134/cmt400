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

function confirmNoticeDelete(noticeID, notice, announcement){
    // show confirmation dialog
    var result = confirm("Are you sure you want to delete this notice ?");
    // if user clicks yes/ok/true
    // send him to delete-department page
    if(result){
        if(notice){
            window.location = "delete-notice.php?noticeID="+noticeID;
        }else{
            window.location = "delete-announcement.php?noticeID="+noticeID;
        }
    }
}

function confirmAnnouncementDelete(announcementID, announcement){
    // show confirmation dialog
    var result = confirm("Are you sure you want to delete this announcement ?");
    // if user clicks yes/ok/true
    // send him to delete-department page
    if(result){
        if(announcement){
            window.location = "delete-announcement.php?announcementID="+announcementID;
        }
    }
}

function deleteFeedback(fid, bid){
    var result = confirm("Do you want to delete this feedback ?");
    if(result){
        window.location = "delete-feedback.php?fid="+fid+"&bid="+bid;
    }
}

function blockFeedback(fid, bid){
    var result = confirm("Do you want to block this feedback ? \nOnce blocked only the creator and lecturers can see the feedback.");
    if(result){
        window.location = "block-feedback.php?fid="+fid+"&bid="+bid;
    }
}

function unBlockFeedback(fid, bid){
    var result = confirm("Do you want to unblock this feedback ? ");
    if(result){
        window.location = "unblock-feedback.php?fid="+fid+"&bid="+bid;
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

function createAnnouncement(){
    var title = document.getElementById("title");
    var content = document.getElementById("content");

    if(title.value === "" || content.value === ""){
        alert("Title and Content are required.");
        return false;
    }else{
        return true;
    }
}

function announcementUpdate(){
    var title = document.getElementById("title");
    var content = document.getElementById("content");

    if(title.value === "" || content.value === ""){
        alert("Title and Content are required.");
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

