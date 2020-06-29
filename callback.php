<?php

require 'class.facebook.php';

header('Content-Type: application/json');

if (isset($_REQUEST['access_token'], $_REQUEST['active']) && $_REQUEST['access_token'] != '') {
    $fb = new Facebook;
    $fb->access_token = $_REQUEST['access_token'];
    $fb->is_shielded = filter_var($_REQUEST['active'], FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
    $result = $fb->requestShield();
    if ($result['success']) {
        $status = $result['is_shielded'] ? 'profile picture successfully activated' : 'profile picture successfully deactivated';
        echo json_encode(['type' => 'success', 'title' => 'Successfully', 'message' => '' . $status . ''], JSON_PRETTY_PRINT);
    } else {
        echo json_encode(['type' => 'error', 'title' => 'Failed', 'message' => 'Invalid token.'], JSON_PRETTY_PRINT);
    }
} else {
    echo json_encode(['type' => 'error', 'title' => 'Error', 'message' => 'Token cannot be empty'], JSON_PRETTY_PRINT);
}