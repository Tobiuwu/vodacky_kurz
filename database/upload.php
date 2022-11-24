<?php

if (!empty($_POST['apply'])) {
    require_once('authenticate.class.php');
    $DB = new Authenticate();
    //set variables
    $nick = trim(strip_tags($_POST['nick']));
    $birthdate = trim(strip_tags($_POST['birthdate']));
    $is_swimmer = trim(strip_tags($_POST['is_swimmer']));
    $canoe_kamarad = trim(strip_tags($_POST['canoe_kamarad']));

    // test for null/empty inputs, which can't exist
    $FormData = array($nick, $birthdate, $is_swimmer);
    // Test for empty inputs
    if ($DB->IsEmpty($FormData)) {
        // exception
        http_response_code(400);
        die();
    }

    if (!$DB->isSwimmerTrue($is_swimmer)) {
        http_response_code(400);
        die();
    } else if ($canoe_kamarad != '') {
        if (!$DB->isValidNick($canoe_kamarad)) {
            http_response_code(400);
            die();
        }
    } else if (!$DB->isValidDate($birthdate)) {
        http_response_code(400);
        die();
    } else if ($DB->isDuplicateOrInvalidNick($nick)) {
        http_response_code(400);
        die();
    }
    $DB->type = 'CreateUser';
    array_push($FormData, $canoe_kamarad);
    $DB->param = $FormData;

    if ($DB->Insert()) {
        http_response_code(200);
        die();
    } else {
        http_response_code(500);
        die();
    }
}