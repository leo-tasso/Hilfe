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
        $stmt->bind_param('iddddi', $startId,  $latMinus, $latPlus, $longMinus, $longPlus, $n);
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
    public function getHelpPost($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM PostInterventi WHERE idPostIntervento = ?");
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
                $notInClause = empty($selectedUserIdsString) ? '' : "AND u.idUser NOT IN ($selectedUserIdsString)";
                $stmt = $this->db->prepare("
                    SELECT u.idUser, u.Name, u.Surname, COUNT(s1.idSeguito) AS NumSeguitiInComune
                    FROM User u
                    JOIN Seguiti s1 ON u.idUser = s1.idSeguito
                    JOIN Seguiti s2 ON s1.idSeguito = s2.idSeguace AND s2.idSeguito IN (SELECT idSeguito FROM Seguiti WHERE idSeguace = ?)
                    WHERE u.idUser <> ?
                    AND u.idUser NOT IN (SELECT idSeguito FROM Seguiti WHERE idSeguace = ?) 
                    " . $notInClause . " 
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
                $notInClause = empty($selectedUserIdsString) ? '' : "idUser NOT IN ($selectedUserIdsString) AND";
                $stmt = $this->db->prepare("SELECT * FROM User WHERE " . $notInClause . " idUser <> ? ORDER BY RAND() LIMIT ?");
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
                $userData = $this->getUserFromId($user)[0];
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
            $result["Address"] = "Roma, Piazza del Colosseo 1";
            $result["AddressLat"] = 0.0;
            $result["AddressLong"] = 0.0;
            return $result;
        }
    }
    public function login($email, $password, $remember)
    {
        // Usando statement sql 'prepared' non sarà possibile attuare un attacco di tipo SQL injection.
        if ($stmt = $this->db->prepare("SELECT idUser, Username, Password, Salt FROM User WHERE Email = ? OR Username = ? LIMIT 1")) {
            $stmt->bind_param('ss', $email, $email);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($user_id, $username, $db_password, $salt);
            $stmt->fetch();
            $password = hash('sha512', $password . $salt);
            if ($stmt->num_rows == 1) { // se l'utente esiste
                // verifichiamo che non sia disabilitato in seguito all'esecuzione di troppi tentativi di accesso errati.
                if ($this->checkbrute($user_id) == true) {
                    // Account disabilitato
                    // Invia un e-mail all'utente avvisandolo che il suo account è stato disabilitato.
                    return false;
                } else {
                    if ($db_password == $password) { // Verifica che la password memorizzata nel database corrisponda alla password fornita dall'utente.
                        // Password corretta!            
                        $user_browser = $_SERVER['HTTP_USER_AGENT']; // Recupero il parametro 'user-agent' relativo all'utente corrente.

                        $user_id = preg_replace("/[^0-9]+/", "", $user_id); // ci proteggiamo da un attacco XSS
                        $_SESSION['idUser'] = $user_id;
                        $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username); // ci proteggiamo da un attacco XSS
                        $_SESSION['username'] = $username;
                        $_SESSION['login_string'] = hash('sha512', $password . $user_browser);
                        if ($remember) {
                            $token = generateUniqueToken();
                            setcookie('remember_token', $token, time() + (365 * 24 * 3600), '/'); 
                            $this->saveToken($user_id, $token);
                        }
                        // Login eseguito con successo.
                        return true;
                    } else {
                        // Password incorretta.
                        // Registriamo il tentativo fallito nel database.
                        $now = time();
                        $this->db->query("INSERT INTO Accessi (idUser, tempo) VALUES ('$user_id', '$now')");
                        return false;
                    }
                }
            } else {
                // L'utente inserito non esiste.
                return false;
            }
        }
    }
    public function checkbrute($user_id)
    {
        // Recupero il timestamp
        $now = time();
        // Vengono analizzati tutti i tentativi di login a partire dalle ultime due ore.
        $valid_attempts = $now - (2 * 60 * 60);
        if ($stmt = $this->db->prepare("SELECT Tempo FROM Accessi WHERE idUser = ? AND Tempo > '$valid_attempts'")) {
            $stmt->bind_param('i', $user_id);
            // Eseguo la query creata.
            $stmt->execute();
            $stmt->store_result();
            // Verifico l'esistenza di più di 5 tentativi di login falliti.
            if ($stmt->num_rows > 5) {
                return true;
            } else {
                return false;
            }
        }
    }
    public function login_check()
    {
        // Verifica che tutte le variabili di sessione siano impostate correttamente
        if (isset($_SESSION['idUser'], $_SESSION['username'], $_SESSION['login_string'])) {
            $user_id = $_SESSION['idUser'];
            $login_string = $_SESSION['login_string'];
            $username = $_SESSION['username'];
            $user_browser = $_SERVER['HTTP_USER_AGENT']; // reperisce la stringa 'user-agent' dell'utente.
            if ($stmt = $this->db->prepare("SELECT Password FROM User WHERE idUser = ? LIMIT 1")) {
                $stmt->bind_param('i', $user_id); // esegue il bind del parametro '$user_id'.
                $stmt->execute(); // Esegue la query creata.
                $result = $stmt->get_result();
                $password = $result->fetch_all(MYSQLI_ASSOC)["Password"];
                if ($stmt->num_rows == 1) { // se l'utente esiste
                    $stmt->bind_result($password); // recupera le variabili dal risultato ottenuto.
                    $stmt->fetch();
                    $login_check = hash('sha512', $password . $user_browser);
                    if ($login_check == $login_string) {
                        // Login eseguito!!!!
                        return true;
                    } else {
                        //  Login non eseguito
                        return false;
                    }
                } else {
                    // Login non eseguito
                    return false;
                }
            } else {
                // Login non eseguito
                return false;
            }
        } else {
            // Login non eseguito
            return false;
        }
    }
    public function getFollowing($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM Seguiti WHERE idSeguace = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getFollower($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM Seguiti WHERE idSeguito = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function saveToken($user_id, $token){
        $stmt = $this->db->prepare("INSERT INTO Token (idUser, TokenValue) VALUES (?, ?)");
        $stmt->bind_param('is', $user_id, $token);
        $stmt->execute();
    }
    public function loginWithToken($token){
        $stmt = $this->db->prepare("SELECT * FROM Token WHERE TokenValue = ?");
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $lines = $result->fetch_all(MYSQLI_ASSOC);
        if(count($lines) == 1){
            $user_browser = $_SERVER['HTTP_USER_AGENT']; // Recupero il parametro 'user-agent' relativo all'utente corrente.
            $_SESSION['idUser'] = $lines[0]["idUser"];
            $_SESSION['username'] = $this->getUserFromId($lines[0]["idUser"])[0]["Username"];
            $_SESSION['login_string'] = hash('sha512', $this->getUserFromId($lines[0]["idUser"])[0]["Password"] . $user_browser);
        }
    }
    public function checkToken() {
        if (!isLogged()) {
            if (isset($_COOKIE['remember_token'])) {
                $this->loginWithToken($_COOKIE['remember_token']);
            }
        }
    }
}



$dbh = new DatabaseHelperMySql("localhost", "root", "", "HilfeDb", 3306);
