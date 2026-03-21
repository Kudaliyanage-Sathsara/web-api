<?php
function generate_secure_token()
{
    return bin2hex(random_bytes(32));
}

function validate_university_email($email)
{
    // Only accept university emails
    // @university.edu
    // @alumni.university.edu
    
    $domain = substr(strrchr($email, "@"), 1);
    $allowed = ["university.edu", "alumni.university.edu"];
    return in_array($domain, $allowed);
}