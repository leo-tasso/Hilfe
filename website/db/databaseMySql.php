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

    public function getHelpPosts($n)
    {
        $stmt = $this->db->prepare("SELECT * FROM PostInterventi LIMIT ?");
        $stmt->bind_param('i', $n);
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
            $stmt = $this->db->prepare("SELECT * FROM Postsalvati WHERE idUser = ? AND idPostInterventi = ?");
            $stmt->bind_param('ii', $_SESSION["idUser"], $id);
            $stmt->execute();
            $result = $stmt->get_result();
            return count($result->fetch_all(MYSQLI_ASSOC)) > 0;
        } else {
            return false;
        }
    }
    public function isPartecipating($id)
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
                $stmt = $this->db->prepare("INSERT INTO Postsalvati (idPostInterventi, idUser) VALUES (?, ?)");
                $stmt->bind_param('ii', $id, $_SESSION["idUser"]);
                $stmt->execute();
            } else {
                $stmt = $this->db->prepare("DELETE FROM  Postsalvati WHERE idPostInterventi = ? AND idUser = ?");
                $stmt->bind_param('ii', $id, $_SESSION["idUser"]);
                $stmt->execute();
            }
            return $this->isPostSaved($id);
        } else {
            return false;
        }
    }
}


$dbh = new DatabaseHelperMySql("localhost", "root", "", "HilfeDb", 3306);
