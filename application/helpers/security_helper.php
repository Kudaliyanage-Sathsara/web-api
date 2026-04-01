<?php
function generate_secure_token()
{
    return bin2hex(random_bytes(32));
}

function validate_university_email($email)
{
    // Only accept approved university emails
    // - @my.westminster.ac.uk
    // - @iit.ac.lk
    // keep fallback for previous allowed domains if needed

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $domain = strtolower(substr(strrchr($email, "@"), 1));
    $allowed = ["my.westminster.ac.uk", "iit.ac.lk"];
    return in_array($domain, $allowed);
}