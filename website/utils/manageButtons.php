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
        case 'follow':
            $dbh->follow($id);
            $count = count($dbh->getFollower($id));
            if ($dbh->isFollowing($id)) {
                $response = array('status' => 'follows', 'counter' => $count);
            } else {
                $response = array('status' => 'notFollows', 'counter' => $count);
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
            $response = array('post' => $dbh->getHelpPost($id));
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
            } else {
                $response = array('articles' => '');
            }
            header('Content-Type: application/json');
            echo json_encode($response);
            break;
        case 'logout':
            $_SESSION = array();
            $params = session_get_cookie_params();
            setcookie('remember_token', '', time() - 3600, '/');
            session_destroy();
            $response = array('result' => 'success');
            header('Content-Type: application/json');
            echo json_encode($response);
            break;
        case 'updateLike':
            $response = array('statusLike' => $dbh->isLiking($id) ? 'liking' : 'notLiking');
            header('Content-Type: application/json');
            echo json_encode($response);
            break;
        case 'like':
            $dbh->like($id);
            $response = array('status' => $dbh->isLiking($id));
            header('Content-Type: application/json');
            echo json_encode($response);
            break;
        case 'getComments':
            $comments = $dbh->getComments($id);
            if (count($comments) > 0) {
                ob_start();
                foreach ($comments as $comment) {
                    include '../template/comment.php';
                }
                $response = array('comments' =>  ob_get_clean());
            } else {
                $response = array('comments' => '');
            }
            header('Content-Type: application/json');
            echo json_encode($response);
            break;
        case 'postComment':
            $text = $_POST['comment'];
            $dbh->postComment($id, $text);
            $response = array('status' => 'ok');
            header('Content-Type: application/json');
            echo json_encode($response);
            break;
        case 'deleteComment':
            $dbh->deleteComment($id);
            $response = array('status' => 'ok');
            header('Content-Type: application/json');
            echo json_encode($response);
            break;
        case 'deleteHelpPost':
            $dbh->deleteHelpPost($id);
            $response = array('status' => 'ok');
            header('Content-Type: application/json');
            echo json_encode($response);
            break;
        case 'deleteInfoPost':
            $dbh->deleteInfoPost($id);
            $response = array('status' => 'ok');
            header('Content-Type: application/json');
            echo json_encode($response);
            break;
        case 'deleteUser':
            $dbh->deleteUser($id);
            $response = array('status' => 'ok');
            header('Content-Type: application/json');
            echo json_encode($response);
            break;
        case 'moreInfoPosts':
            $startId = isset($_POST['startId']) ? $_POST['startId'] : '';
            $articles = $dbh->getInfoPosts($START_POST_NUMBER, $startId, $_SESSION["idUser"]);
            if (count($articles) > 0) {
                ob_start();
                foreach ($articles as $post) {
                    include '../template/article.php';
                }
                $response = array('articles' =>  ob_get_clean());
            } else {
                $response = array('articles' => '');
            }
            header('Content-Type: application/json');
            echo json_encode($response);
            break;
    }
} else {
    http_response_code(405);
    echo 'Invalid request method';
}
