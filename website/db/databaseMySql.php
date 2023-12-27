<?php
require("databaseHelper.php");
class DatabaseHelperMySql implements DatabaseHelper
{
    private $db;

    public function __construct($servername, $username, $password, $dbname)
    {
        $this->db = new mysqli($servername, $username, $password, $dbname);
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    public function getHelpPosts($n, $startId, $lat, $long, $radius)
    {
        $earthRadius = 6371;
        $radius = ($radius > 99) ? $radius * 10000 : $radius; //if greater than 99 becomes unlimited
        $latRange = rad2deg($radius / $earthRadius);
        $longRange = rad2deg($radius / ($earthRadius * cos(deg2rad($lat))));
        $stmt = $this->db->prepare("SELECT * FROM PostInterventi WHERE idPostIntervento > ? AND PosizioneLongitudine BETWEEN ? AND ? AND PosizioneLatitudine BETWEEN ? AND ? LIMIT ?");
        $longMinus = $long - $longRange;
        $longPlus = $long + $longRange;
        $latMinus = $lat - $latRange;
        $latPlus = $lat + $latRange;
        $stmt->bind_param('iiiiii', $startId, $longMinus, $longPlus, $latMinus, $latPlus, $n);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserFromId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM User WHERE idUser = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getProfilePicPathFromId($id)
    {
        $stmt = $this->db->prepare("SELECT FotoProfilo FROM User WHERE idUser = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAuthorFromHelpPost($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM User, PostInterventi WHERE idUser = Autore_idUser AND idPostIntervento = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getMaterialFromHelpPost($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM Materiale WHERE idPostIntervento = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    private function getPicFromId($id)
    {
        $stmt = $this->db->prepare("SELECT FotoProfilo FROM User WHERE idUser = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $lines = $result->fetch_all(MYSQLI_ASSOC);
        if (count($lines) == 0 || $lines[0]["FotoProfilo"] == null) {
            return DEFAULT_PIC_PATH . DEFAULT_PIC;
        } else {
            return DEFAULT_PIC_PATH . $lines[0]["FotoProfilo"];
        }
    }

    public function getProfilePic($id)
    {
        if ($id == null) {
            return DEFAULT_PIC_PATH . DEFAULT_PIC;
        }
        return $this->getPicFromId($id);
    }

    public function getSelfProfilePic()
    {
        if (isLogged()) {
            return $this->getPicFromId($_SESSION["idUser"]);
        }
        return DEFAULT_PIC_PATH . DEFAULT_PIC;
    }

    public function getNotification()
    {
        if (isLogged()) {
            $stmt = $this->db->prepare("SELECT * FROM Notifica WHERE idUser = ? AND Letta = 0");
            $stmt->bind_param('i', $_SESSION["idUser"]);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }
    public function getSuggestedUsers($n)
    {
        $suggestedUsers = [];

        if (isLogged()) {
            $stmt = $this->db->prepare("SELECT * FROM User, Seguiti WHERE idUser NOT IN (SELECT idSeguito FROM User, Seguiti WHERE idSeguace = ?) AND idSeguito = ? AND idSeguace=idUser LIMIT ?");
            $stmt->bind_param('iii', $_SESSION["idUser"], $_SESSION["idUser"], $n);
            $stmt->execute();
            $result = $stmt->get_result();
            $entries = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($entries as $key => $entry) {
                $entries[$key]["seguace"] = 1;
            }

            $suggestedUsers = array_merge($suggestedUsers, $entries);

            if (count($suggestedUsers) < $n) {
                $selectedUserIdsString = implode(',', array_fill(0, count($suggestedUsers), '?'));
                $stmt = $this->db->prepare("
                    SELECT u.idUser, u.Name, u.Surname, COUNT(s1.idSeguito) AS NumSeguitiInComune
                    FROM User u
                    JOIN Seguiti s1 ON u.idUser = s1.idSeguito
                    JOIN Seguiti s2 ON s1.idSeguito = s2.idSeguace AND s2.idSeguito IN (SELECT idSeguito FROM Seguiti WHERE idSeguace = ?)
                    WHERE u.idUser <> ?
                    AND u.idUser NOT IN (SELECT idSeguito FROM Seguiti WHERE idSeguace = ?) 
                    AND u.idUser NOT IN ($selectedUserIdsString) 
                    GROUP BY u.idUser, u.Name, u.Surname
                    ORDER BY NumSeguitiInComune DESC
                    LIMIT ?");
                $limitValue = $n - count($suggestedUsers);
                $params = array_merge([$_SESSION["idUser"], $_SESSION["idUser"], $_SESSION["idUser"]], array_column($suggestedUsers, 'idUser'));
                $params = array_merge($params, [$limitValue]);
                $types = 'iii' . str_repeat('s', count($suggestedUsers)) . 'i';
                $stmt->bind_param($types, ...$params);
                $stmt->execute();
                $result = $stmt->get_result();
                $suggestedUsers = array_merge($suggestedUsers, $result->fetch_all(MYSQLI_ASSOC));
            }

            if (count($suggestedUsers) < $n) {
                $limitValue = $n - count($suggestedUsers);
                $selectedUserIdsString = implode(',', array_fill(0, count($suggestedUsers), '?'));
                $stmt = $this->db->prepare("SELECT * FROM User WHERE idUser NOT IN ($selectedUserIdsString) AND idUser <> ? ORDER BY RAND() LIMIT ?");
                $params = array_merge(array_column($suggestedUsers, 'idUser'), [$_SESSION["idUser"], $limitValue]);
                $types = str_repeat('s', count($suggestedUsers)) . 'ii';
                $stmt->bind_param($types, ...$params);

                $stmt->execute();
                $result = $stmt->get_result();
                $entries = $result->fetch_all(MYSQLI_ASSOC);

                foreach ($entries as $key => $entry) {
                    $entries[$key]["Motivazione"] = CUTE_PHRASES[array_rand(CUTE_PHRASES)];
                }

                $suggestedUsers = array_merge($suggestedUsers, $entries);
            }

            return $suggestedUsers;
        }

        if (count($suggestedUsers) < $n) {
            $stmt = $this->db->prepare("SELECT * FROM User ORDER BY RAND() LIMIT ?");
            $stmt->bind_param('i', $n);
            $stmt->execute();
            $result = $stmt->get_result();
            $entries = $result->fetch_all(MYSQLI_ASSOC);
            foreach ($entries as $key => $entry) {
                $entries[$key]["Motivazione"] = CUTE_PHRASES[array_rand(CUTE_PHRASES)];
            }
            $suggestedUsers = array_merge($suggestedUsers, $entries);
            return $suggestedUsers;
        }
    }
    public function isPostSaved($id)
    {
        if (isLogged()) {
            $stmt = $this->db->prepare("SELECT * FROM PostSalvati WHERE idUser = ? AND idPostInterventi = ?");
            $stmt->bind_param('ii', $_SESSION["idUser"], $id);
            $stmt->execute();
            $result = $stmt->get_result();
            return count($result->fetch_all(MYSQLI_ASSOC)) > 0;
        } else {
            return false;
        }
    }
    public function isParticipating($id)
    {
        if (isLogged()) {
            $stmt = $this->db->prepare("SELECT * FROM Interventi WHERE idUser = ? AND idPostInterventi = ?");
            $stmt->bind_param('ii', $_SESSION["idUser"], $id);
            $stmt->execute();
            $result = $stmt->get_result();
            return count($result->fetch_all(MYSQLI_ASSOC)) > 0;
        } else {
            return false;
        }
    }
    public function savePost($id, $set)
    {
        if (isLogged()) {
            if ($set == true) {
                $stmt = $this->db->prepare("INSERT INTO PostSalvati (idPostInterventi, idUser) VALUES (?, ?)");
                $stmt->bind_param('ii', $id, $_SESSION["idUser"]);
                $stmt->execute();
            } else {
                $stmt = $this->db->prepare("DELETE FROM  PostSalvati WHERE idPostInterventi = ? AND idUser = ?");
                $stmt->bind_param('ii', $id, $_SESSION["idUser"]);
                $stmt->execute();
            }
            return $this->isPostSaved($id);
        } else {
            return false;
        }
    }
    public function participatePost($id, $set)
    {
        if (isLogged()) {
            if ($set == true) {
                $stmt = $this->db->prepare("INSERT INTO Interventi (idPostInterventi, idUser) VALUES (?, ?)");
                $stmt->bind_param('ii', $id, $_SESSION["idUser"]);
                $stmt->execute();
            } else {
                $stmt = $this->db->prepare("DELETE FROM  Interventi WHERE idPostInterventi = ? AND idUser = ?");
                $stmt->bind_param('ii', $id, $_SESSION["idUser"]);
                $stmt->execute();
            }
            return $this->isParticipating($id);
        } else {
            return false;
        }
    }
    public function getParticipants($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM Interventi where idPostInterventi = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addDescription($users)
    {
        $suggestedUsers = [];

        if (isLogged()) {
            foreach ($users as $user) {
                $userData = $this->getUserFromId($user)[0];
                if ($user == $_SESSION["idUser"]) {
                    $userData["seiTu"] = 1;
                } else if ($this->followsMe($user)) {
                    $userData["seguace"] = 1;
                } else if ($this->followInCommon($user) > 0) {
                    $userData["NumSeguitiInComune"] = $this->followInCommon($user);
                } else {
                    $userData["Motivazione"] = CUTE_PHRASES[array_rand(CUTE_PHRASES)];
                }
                $suggestedUsers[] = $userData;
            }
            return $suggestedUsers;
        } else {
            foreach ($users as $user) {
                $userData = $this->getUserFromId($user);
                $userData["Motivazione"] = CUTE_PHRASES[array_rand(CUTE_PHRASES)];
                $suggestedUsers[] = $userData;
            }
            return $suggestedUsers;
        }
    }

    public function followsMe($otherUserId)
    {
        if (isLogged()) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM Seguiti WHERE idSeguito = ? AND idSeguace = ?");
            $stmt->bind_param('ii', $_SESSION["idUser"], $otherUserId);
            $stmt->execute();
            $result = $stmt->get_result();
            $count = $result->fetch_row()[0];
            return $count > 0;
        } else {
            return false;
        }
    }

    public function followInCommon($otherUserId)
    {
        if (isLogged()) {
            $stmt = $this->db->prepare("
                SELECT COUNT(s1.idSeguito) AS NumSeguitiInComune
                FROM Seguiti s1
                JOIN Seguiti s2 ON s1.idSeguito = s2.idSeguito AND s1.idSeguace = s2.idSeguace
                WHERE s1.idSeguace = ? AND s2.idSeguace = ?
            ");
            $stmt->bind_param('ii', $_SESSION["idUser"], $otherUserId);
            $stmt->execute();
            $result = $stmt->get_result();
            $count = $result->fetch_row()[0];
            return $count;
        } else {
            return 0;
        }
    }
    public function getAddress()
    {
        if (isLogged()) {
            $stmt = $this->db->prepare("SELECT Address, AddressLat, AddressLong FROM User WHERE idUser = ?");
            $stmt->bind_param('i', $_SESSION["idUser"]);
            $stmt->execute();
            $result = $stmt->get_result();
            $lines = $result->fetch_all(MYSQLI_ASSOC);
            return $lines[0];
        } else {
            return "Roma, Piazza del Colosseo 1";
        }
    }
}


$dbh = new DatabaseHelperMySql("localhost", "root", "", "HilfeDb", 3306);
