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
        case 'getPost':
            $response = array('post' => $dbh->getHelpPost($id)[0]);
            header('Content-Type: application/json');
            echo json_encode($response);
            break;
        case 'participants':
            $participants = $dbh->getParticipants($id);
            $idUsers = array_column($participants, 'idUser');
            $users = $dbh->addDescription($idUsers);
            ob_start();
            foreach ($users as $user) {
                require  '../template/profilePreview.php';
            }
            $response = array('participants' =>  ob_get_clean());
            header('Content-Type: application/json');
            echo json_encode($response);
            break;
        case 'morePosts':
            $startId = isset($_POST['startId']) ? $_POST['startId'] : '';
            $address = isset($_POST['address']) ? $_POST['address'] : '';
            $range = isset($_POST['range']) ? $_POST['range'] : '';
            if ($address == "") {
                $addressLine = $dbh->getAddress();
                $lat = $addressLine["AddressLat"];
                $long = $addressLine["AddressLong"];
            } else {
                $coordinates = getCoordinates($address);
                $lat = $coordinates["latitude"];
                $long = $coordinates["longitude"];
            }
            $articles = $dbh->getHelpPosts($START_POST_NUMBER, $startId, $lat, $long, $range);
            if (count($articles) > 0) {
                ob_start();
                foreach ($articles as $post) {
                    include '../template/article.php';
                }
                $response = array('articles' =>  ob_get_clean());
            }
            else{
                $response = array('articles' => '<p>Nessun post trovato</p>');
            }
            header('Content-Type: application/json');
            echo json_encode($response);
            break;
    }
} else {
    http_response_code(405);
    echo 'Invalid request method';
}
