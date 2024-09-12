<?php

    // function to remove/sanitize email address(remove unwanted chars, and some white spaces)
    function sanitizeEmailInput($email){
        $sanitizedEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        return $sanitizedEmail;
    }

    // function to remove/sanitize strings (remove unwanted chars, and some white spaces)
    function sanitizeStringInput($input){
        $sanitizedInput = filter_var($input, FILTER_SANITIZE_STRING);
        // $sanitizedInput = filter_var($input, FILTER_SANITIZE_SPECIAL_CHARS);
        return $sanitizedInput;
    }

    // function to remove/sanitize number values
    // removes chars and some specials chars
    function sanitizeNumberInput($number){
        // $sanitizedNumber = filter_var($number, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
        $sanitizedNumber = filter_var($number, FILTER_SANITIZE_NUMBER_INT);
        return $sanitizedNumber;
    }

    function cleanInput($data){
        // remove white spaces left and right of data
        $data = trim($data);
        // remove slashes
        $data = stripslashes($data);
        // remove html tags and elements
        $data = htmlspecialchars($data);
        return $data;
    }

    function removeQuoteHash($data){
        $data = trim($data, " ' ");
        $data = trim($data, " # ");
        return $data;
    }

?>