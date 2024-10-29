<?php
function sqli_checker($conn, $input) {
    //mysqli input escape
    //return mysqli_real_escape_string($conn, $input);
    return $input;
}

function xss_checker($input){
    //xss html escape
    //return nl2br(htmlspecialchars($input));
    return $input;
}

// function normalize_input($input) {
//     $input = strtolower($input);
//     $input = urldecode($input);
//     $input = html_entity_decode($input, ENT_QUOTES | ENT_HTML5);
//     $input = @iconv("UTF-8", "UTF-8//IGNORE", $input);

//     return $input;
// }

// function input_check($input) {
//     $input = normalize_input($input);

//     $blacklist_patterns = array(
//         "/\/select.*from\//",
//         "/\/union.*select\//",
//         "/\/alter.*table\//",
//         "/\/drop.*table\//",
//     );

//     $whitelist_pattern = "/^[a-zA-Z0-9\s?!'\"-]+$/";

//     foreach ($blacklist_patterns as $pattern) {
//         if (preg_match($pattern, $input)) {
//             return true;
//         }
//     }

//     if (!preg_match($whitelist_pattern, $input)) {
//         return true;
//     }

//     return false;
// }

?>
