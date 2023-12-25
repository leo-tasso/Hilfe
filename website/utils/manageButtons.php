<?php
require_once '../bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $saved = $dbh->savePost($id, !$dbh->isPostSaved($id));
    if ($saved) {
        $response = array('status' => 'saved');
    } else {
        $response = array('status' => 'unsaved');
    }
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    http_response_code(405);
    echo 'Invalid request method';
}
?>