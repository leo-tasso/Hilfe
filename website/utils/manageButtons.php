<?php
require_once '../bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    switch ($action) {
        case 'salva':
            $saved = $dbh->savePost($id, !$dbh->isPostSaved($id));
            if ($saved) {
                $response = array('status' => 'saved');
            } else {
                $response = array('status' => 'unsaved');
            }
            header('Content-Type: application/json');
            echo json_encode($response);
            break;

        case 'partecipa':
            $participate = $dbh->participatePost($id, !$dbh->isparticipating($id));
            $participants = count($dbh->getParticipants($id));
            if ($participate) {
                $response = array('status' => 'partecipa', 'participants' => $participants);
            } else {
                $response = array('status' => 'nonPartecipa', 'participants' => $participants);
            }
            header('Content-Type: application/json');
            echo json_encode($response);
            break;
        case 'update':
            $participants = count($dbh->getParticipants($id));
            $response = array('statusParticipate' => $dbh->isparticipating($id) ? 'partecipa' : 'nonPartecipa', 'statusSaved' => $dbh->isPostSaved($id) ? 'saved' : 'unsaved', 'participants' => $participants);
            header('Content-Type: application/json');
            echo json_encode($response);
            break;
    }
} else {
    http_response_code(405);
    echo 'Invalid request method';
}
