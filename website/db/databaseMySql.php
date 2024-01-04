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
        $result = $result->fetch_all(MYSQLI_ASSOC);
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
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
        return $result->fetch_all(MYSQLI_ASSOC)[0];
    }
    public function getAuthorFromInfoPost($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM User, PostComunicazioni WHERE PostComunicazioni.idUser = User.idUser AND idPostComunicazione = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC)[0];
    }
    public function getHelpPost($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM PostInterventi WHERE idPostIntervento = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC)[0];
    }
    public function getInfoPost($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM PostComunicazioni WHERE idPostComunicazione = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC)[0];
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
                $stmt = $this->db->prepare("SELECT * FROM User WHERE " . $notInClause . " idUser <> ? AND idUser NOT IN (SELECT idSeguito FROM Seguiti WHERE idSeguace = ?) ORDER BY RAND() LIMIT ?");
                $params = array_merge(array_column($suggestedUsers, 'idUser'), [$_SESSION["idUser"], $_SESSION["idUser"], $limitValue]);
                $types = str_repeat('s', count($suggestedUsers)) . 'iii';
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

                $stmtNotf = $this->db->prepare("INSERT INTO Notifica (idNotifica, idUser, TestoNotifica, Letta, DataCreazione, idUserGeneratore) VALUES (?,?,?,?,?,?)");
                $newid = $this->getNewNotificationId();
                $post = $this->getHelpPost($id);
                $now = date('Y-m-d H:i:s');
                $autore = $post['Autore_idUser'];
                $testo = "Ha salvato un tuo annuccio";
                $letta = 0;
                $idRelativo = $_SESSION["idUser"];
                $stmtNotf->bind_param('iisisi', $newid, $autore, $testo, $letta, $now, $idRelativo);
                $stmtNotf->execute();
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

                $stmtNotf = $this->db->prepare("INSERT INTO Notifica (idNotifica, idUser, TestoNotifica, Letta, DataCreazione, idUserGeneratore) VALUES (?,?,?,?,?,?)");
                $newid = $this->getNewNotificationId();
                $post = $this->getHelpPost($id);
                $now = date('Y-m-d H:i:s');
                $autore = $post['Autore_idUser'];
                $testo = "Parteciperà all'evento";
                $letta = 0;
                $idRelativo = $_SESSION["idUser"];
                $stmtNotf->bind_param('iisisi', $newid, $autore, $testo, $letta, $now, $idRelativo);
                $stmtNotf->execute();
            } else {
                $stmt = $this->db->prepare("DELETE FROM  Interventi WHERE idPostInterventi = ? AND idUser = ?");
                $stmt->bind_param('ii', $id, $_SESSION["idUser"]);
                $stmt->execute();

                $stmtNotf = $this->db->prepare("INSERT INTO Notifica (idNotifica, idUser, TestoNotifica, Letta, DataCreazione, idUserGeneratore) VALUES (?,?,?,?,?,?)");
                $newid = $this->getNewNotificationId();
                $post = $this->getHelpPost($id);
                $now = date('Y-m-d H:i:s');
                $autore = $post['Autore_idUser'];
                $testo = "Non parteciperà più all'evento";
                $letta = 0;
                $idRelativo = $_SESSION["idUser"];
                $stmtNotf->bind_param('iisisi', $newid, $autore, $testo, $letta, $now, $idRelativo);
                $stmtNotf->execute();
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
                $userData = $this->getUserFromId($user);
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
    public function getParticipations($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM PostInterventi,Interventi WHERE idPostIntervento = idPostInterventi AND idUser = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function saveToken($user_id, $token)
    {
        $now = time();
        $stmt = $this->db->prepare("INSERT INTO Token (idUser, TokenValue, CreationTime) VALUES (?, ?, '$now')");
        $stmt->bind_param('is', $user_id, $token);
        $stmt->execute();
    }
    public function loginWithToken($token)
    {
        $stmt = $this->db->prepare("SELECT * FROM Token WHERE TokenValue = ?");
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $lines = $result->fetch_all(MYSQLI_ASSOC);
        if (count($lines) == 1) {
            $user_browser = $_SERVER['HTTP_USER_AGENT']; // Recupero il parametro 'user-agent' relativo all'utente corrente.
            $_SESSION['idUser'] = $lines[0]["idUser"];
            $_SESSION['username'] = $this->getUserFromId($lines[0]["idUser"])["Username"];
            $_SESSION['login_string'] = hash('sha512', $this->getUserFromId($lines[0]["idUser"])["Password"] . $user_browser);
        }
    }
    public function checkToken()
    {
        if (!isLogged()) {
            if (isset($_COOKIE['remember_token'])) {
                $this->loginWithToken($_COOKIE['remember_token']);
            }
        }
    }
    public function getNotifications()
    {
        if (isLogged()) {
            $stmt = $this->db->prepare("SELECT * FROM Notifica WHERE idUser = ? ORDER BY idNotifica DESC ");
            $stmt->bind_param('i', $_SESSION["idUser"]);
            $stmt->execute();
            $result = $stmt->get_result();
            $this->markNotificationsAsRead($_SESSION["idUser"]);
            return $result->fetch_all(MYSQLI_ASSOC);
        } else return false;
    }
    private function markNotificationsAsRead($id)
    {
        $updateStmt = $this->db->prepare("UPDATE Notifica SET Letta = 1 WHERE idUser = ?");
        $updateStmt->bind_param('i', $id);
        $updateStmt->execute();
    }
    public function getPostsFromUser($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM PostInterventi WHERE Autore_idUser = ? ORDER BY idPostIntervento DESC ");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $postInterventi = $result->fetch_all(MYSQLI_ASSOC);
        $stmtCom = $this->db->prepare("SELECT * FROM HilfeDb.PostComunicazioni WHERE idUser = ? ORDER BY idPostComunicazione DESC ");
        $stmtCom->bind_param('i', $id);
        $stmtCom->execute();
        $result = $stmtCom->get_result();
        $postComunicazioni = $result->fetch_all(MYSQLI_ASSOC);
        $allPosts = array_merge($postInterventi, $postComunicazioni);
        function compareByDate($a, $b)
        {
            $dateA = strtotime($a['DataPubblicazione']);
            $dateB = strtotime($b['DataPubblicazione']);

            return $dateB - $dateA;
        }

        usort($allPosts, 'compareByDate');
        return $allPosts;
    }
    public function isFollowing($id)
    {
        if (isLogged()) {
            $stmt = $this->db->prepare("SELECT * FROM Seguiti WHERE idSeguace = ? AND idSeguito = ? ");
            $stmt->bind_param('ii',  $_SESSION["idUser"], $id);
            $stmt->execute();
            $result = $stmt->get_result();
            return count($result->fetch_all(MYSQLI_ASSOC)) == 1;
        } else {
            return false;
        }
    }
    public function follow($id)
    {
        if (isLogged()) {
            if (!$this->isFollowing($id)) {
                $stmt = $this->db->prepare("INSERT INTO Seguiti (idSeguace, idSeguito) VALUES (?, ?)");
                $stmt->bind_param('ii',  $_SESSION["idUser"], $id);
                $stmt->execute();
            } else {
                $stmt = $this->db->prepare("DELETE FROM  Seguiti WHERE idSeguace = ? AND idSeguito = ?");
                $stmt->bind_param('ii',  $_SESSION["idUser"], $id);
                $stmt->execute();
            }
            return $this->isFollowing($id);
        } else {
            return false;
        }
    }
    public function registerUser($nome, $cognome, $username, $data, $email, $password, $profilePic, $phone, $addr, $bio)
    {
        $stmt = $this->db->prepare("INSERT INTO User (idUser, Name, Surname, Username, Birthday, salt, Email, Password, FotoProfilo, PhoneNumber, Address, Bio) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");

        $salt = generateRandomString(5);
        $newid = $this->getNewUserId();
        $password = hash('sha512', $password . $salt);

        // Use variables for parameters that might be NULL
        $profilePicValue = ($profilePic === null) ? null : $profilePic;
        $phoneValue = ($phone === null) ? null : $phone;
        $addrValue = ($addr === null) ? null : $addr;
        $bioValue = ($bio === null) ? null : $bio;

        // Bind the parameters using variables
        $stmt->bind_param(
            'isssssssssss',
            $newid,
            $nome,
            $cognome,
            $username,
            $data,
            $salt,
            $email,
            $password,
            $profilePicValue,
            $phoneValue,
            $addrValue,
            $bioValue
        );

        return $stmt->execute();
    }
    public function updateUser($nome, $cognome, $username, $data, $email, $password, $profilePic, $phone, $addr, $bio)
    {
        $stmt = $this->db->prepare("UPDATE User SET Name=?, Surname=?, Username=?, Birthday=?, salt=?, Email=?, Password=?, FotoProfilo=?, PhoneNumber=?, Address=?, Bio=? WHERE Username = ?");
        $salt = generateRandomString(5);
        $password = hash('sha512', $password . $salt);

        // Use variables for parameters that might be NULL
        $profilePicValue = ($profilePic === null) ? null : $profilePic;
        $phoneValue = ($phone === null) ? null : $phone;
        $addrValue = ($addr === null) ? null : $addr;
        $bioValue = ($bio === null) ? null : $bio;

        // Bind the parameters using variables
        $stmt->bind_param(
            'ssssssssssss',
            $nome,
            $cognome,
            $username,
            $data,
            $salt,
            $email,
            $password,
            $profilePicValue,
            $phoneValue,
            $addrValue,
            $bioValue,
            $username
        );

        return $stmt->execute();
    }
    private function getNewCommentId()
    {
        $stmt = $this->db->prepare("SELECT idCommento FROM Commento ORDER BY idCommento DESC LIMIT 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $result = $result->fetch_all(MYSQLI_ASSOC);
        if (count($result) > 0) {
            return $result[0]["idCommento"] + 1;
        } else {
            return 1;
        }
    }
    private function getNewUserId()
    {
        $stmt = $this->db->prepare("SELECT idUser FROM User ORDER BY idUser DESC LIMIT 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $result = $result->fetch_all(MYSQLI_ASSOC);
        if (count($result) > 0) {
            return $result[0]["idUser"] + 1;
        } else {
            return 1;
        }
    }
    private function getNewNotificationId()
    {
        $stmt = $this->db->prepare("SELECT idNotifica FROM Notifica ORDER BY idNotifica DESC LIMIT 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $result = $result->fetch_all(MYSQLI_ASSOC);
        if (count($result) > 0) {
            return $result[0]["idNotifica"] + 1;
        } else {
            return 1;
        }
    }
    private function getNewPostHelpId()
    {
        $stmt = $this->db->prepare("SELECT idPostIntervento FROM PostInterventi ORDER BY idPostIntervento DESC LIMIT 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $result = $result->fetch_all(MYSQLI_ASSOC);
        if (count($result) > 0) {
            return $result[0]["idPostIntervento"] + 1;
        } else {
            return 1;
        }
    }
    private function newMateraileId()
    {
        $stmt = $this->db->prepare("SELECT idMateriale FROM Materiale ORDER BY idMateriale DESC LIMIT 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $result = $result->fetch_all(MYSQLI_ASSOC);
        if (count($result) > 0) {
            return $result[0]["idMateriale"] + 1;
        } else {
            return 1;
        }
    }
    public function checkRepetitions($username, $email)
    {
        $stmt = $this->db->prepare("SELECT * FROM User WHERE Email = ? OR Username = ?");
        $stmt->bind_param('ss', $email, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $result = $result->fetch_all(MYSQLI_ASSOC);
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function updatePostHelp($id, $titolo, $testo, $indirizzo, $giorno, $ora, $personeRichieste, $oggetto, $quantita)
    {
        $stmt = $this->db->prepare("UPDATE PostInterventi SET TitoloPost=?, DescrizionePost=?, DataIntervento=?, PosizioneLongitudine=?, PosizioneLatitudine=?, Indirizzo=?, PersoneRichieste=?, DataPubblicazione =? WHERE idPostIntervento=?");

        $data = $giorno . " " . $ora;
        $coordinates = getCoordinates($indirizzo);
        $now = date('Y-m-d H:i:s');
        $stmt->bind_param(
            'sssddsiii',
            $titolo,
            $testo,
            $data,
            $coordinates["latitude"],
            $coordinates["longitude"],
            $indirizzo,
            $personeRichieste,
            $now,
            $id
        );

        $stmt->execute();

        $stmt = $this->db->prepare("DELETE FROM  Materiale WHERE idPostIntervento = ?");
        $stmt->bind_param('i', $id);
        $outcome = $stmt->execute();

        $oggettoValue = ($oggetto === null) ? null : $oggetto;
        $quanittaValue = ($quantita === null) ? null : $quantita;
        $idmateriale = $this->newMateraileId();
        if ($oggettoValue !== null) {

            foreach ($oggettoValue as $key => $value) {
                $stmt = $this->db->prepare("INSERT INTO Materiale (idMateriale,DescrizioneMateriale,Unita,idPostIntervento) VALUES (?,?,?,?)");
                $stmt->bind_param(
                    'isii',
                    $idmateriale,
                    $oggettoValue[$key],
                    $quanittaValue[$key],
                    $id
                );
                $stmt->execute();
                $idmateriale++;
            }
        }

        foreach (array_column($this->getParticipants($id), 'idUser') as $user) {
            $stmtNotf = $this->db->prepare("INSERT INTO Notifica (idNotifica, idUser, TestoNotifica, Letta, DataCreazione, idUserGeneratore) VALUES (?,?,?,?,?,?)");
            $newid = $this->getNewNotificationId();
            $now = date('Y-m-d H:i:s');
            $idDestinatario = $_SESSION["idUser"];
            $testo = "Ha modificato il post a cui partecipi";
            $letta = 0;
            $idRelativo = $user;
            $stmtNotf->bind_param('iisisi', $newid, $idDestinatario, $testo, $letta, $now, $idRelativo);
            $stmtNotf->execute();
        }

        return $outcome;
    }
    public function newPostHelp($titolo, $testo, $indirizzo, $giorno, $ora, $personeRichieste, $oggetto, $quantita)
    {
        $stmt = $this->db->prepare("INSERT INTO PostInterventi (idPostIntervento, TitoloPost,DescrizionePost,DataIntervento,DataPubblicazione,PersoneRichieste,PosizioneLongitudine,PosizioneLatitudine,Indirizzo,Autore_idUser) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $data = $giorno . " " . $ora;
        $oggettoValue = ($oggetto === null) ? null : $oggetto;
        $quanittaValue = ($quantita === null) ? null : $quantita;
        $id = $this->getNewPostHelpId();
        $now = date('Y-m-d H:i:s');
        $autore = $_SESSION["idUser"];
        $coordinates = getCoordinates($indirizzo);
        // Bind the parameters using variables
        $stmt->bind_param(
            'issssiddsi',
            $id,
            $titolo,
            $testo,
            $data,
            $now,
            $personeRichieste,
            $coordinates["latitude"],
            $coordinates["longitude"],
            $indirizzo,
            $autore
        );

        $outcome = $stmt->execute();

        $idmateriale = $this->newMateraileId();
        if ($oggettoValue !== null) {
            for ($i = 0; $i < count($oggettoValue); $i++) {
                $valO = $oggettoValue[$i];
                $valQ = $quanittaValue[$i];
                $stmt = $this->db->prepare("INSERT INTO Materiale (idMateriale,DescrizioneMateriale,Unita,idPostIntervento) VALUES (?,?,?,?)");
                $stmt->bind_param(
                    'isii',
                    $idmateriale,
                    $valO,
                    $valQ,
                    $id
                );
                $stmt->execute();
                $idmateriale++;
            }
        }
        return $outcome;
    }
    public function updatePostInfo($id, $titolo, $testo, $postImg)
    {
        $stmt = $this->db->prepare("UPDATE HilfeDb.PostComunicazioni SET TitoloPost=?, DescrizionePost=?, Foto=?, DataPubblicazione=? WHERE idPostComunicazione=?");

        $now = date('Y-m-d H:i:s');

        $stmt->bind_param(
            'ssssi',
            $titolo,
            $testo,
            $postImg,
            $now,
            $id
        );

        return $stmt->execute();
    }
    public function createPostInfo($titolo, $testo, $postImg)
    {
        $stmt = $this->db->prepare("INSERT INTO HilfeDb.PostComunicazioni (idPostComunicazione,idUser,TitoloPost,DescrizionePost,Foto,DataPubblicazione) VALUES (?,?,?,?,?,?)");
        $id = $this->getNewPostInfoId();
        $now = date('Y-m-d H:i:s');
        $autore = $_SESSION["idUser"];
        $stmt->bind_param(
            'iissss',
            $id,
            $autore,
            $titolo,
            $testo,
            $postImg,
            $now
        );

        return $stmt->execute();
    }

    private function getNewPostInfoId()
    {
        $stmt = $this->db->prepare("SELECT idPostComunicazione FROM PostComunicazioni ORDER BY idPostComunicazione DESC LIMIT 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $result = $result->fetch_all(MYSQLI_ASSOC);
        if (count($result) > 0) {
            return $result[0]["idPostComunicazione"] + 1;
        } else {
            return 1;
        }
    }
    public function getInfoPosts($number, $startId, $iduser)
    {
        $follows = $this->getFollowing($iduser);
        if (count($follows) == 0) return [];
        $selectedUserIdsString = implode(',', array_fill(0, count($follows), '?'));
        $stmt = $this->db->prepare("SELECT * FROM HilfeDb.PostComunicazioni WHERE idPostComunicazione > ? AND idUser IN ($selectedUserIdsString) LIMIT ?");
        $params = array_merge([$startId], array_column($follows, 'idSeguito'), [$number]);
        $types = 'i' . str_repeat('s', count($follows)) . 'i';
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function isLiking($id)
    {
        if (isLogged()) {
            $stmt = $this->db->prepare("SELECT * FROM `Like` WHERE idEmettitore = ? AND PostRelativo = ? ");
            $stmt->bind_param('ii',  $_SESSION["idUser"], $id);
            $stmt->execute();
            $result = $stmt->get_result();
            return count($result->fetch_all(MYSQLI_ASSOC)) >= 1;
        } else {
            return false;
        }
    }
    public function like($id)
    {
        if (isLogged()) {
            $set = !$this->isLiking($id);
            if ($set == true) {
                $stmt = $this->db->prepare("INSERT INTO `Like` (PostRelativo, idEmettitore) VALUES (?, ?)");
                $stmt->bind_param('ii', $id, $_SESSION["idUser"]);
                $stmt->execute();

                $stmtNotf = $this->db->prepare("INSERT INTO Notifica (idNotifica, idUser, TestoNotifica, Letta, DataCreazione, idUserGeneratore) VALUES (?,?,?,?,?,?)");
                $newid = $this->getNewNotificationId();
                $post = $this->getInfoPost($id);
                $now = date('Y-m-d H:i:s');
                $autore = $post['idUser'];
                $testo = "Piace il tuo post";
                $letta = 0;
                $idRelativo = $_SESSION["idUser"];
                $stmtNotf->bind_param('iisisi', $newid, $autore, $testo, $letta, $now, $idRelativo);
                $stmtNotf->execute();
            } else {
                $stmt = $this->db->prepare("DELETE FROM  `Like` WHERE PostRelativo = ? AND idEmettitore = ?");
                $stmt->bind_param('ii', $id, $_SESSION["idUser"]);
                $stmt->execute();
            }
            return $this->isLiking($id);
        } else {
            return false;
        }
    }
    public function getComments($id)
    {
        if (isLogged()) {
            $stmt = $this->db->prepare("SELECT * FROM Commento WHERE RelativoA = ? ");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }
    public function postComment($id, $text)
    {
        if (isLogged()) {
            $stmt = $this->db->prepare("INSERT INTO Commento (Autore,RelativoA,Testo,idCommento,DataPubblicazione) VALUES (?,?,?,?,?)");
            $autore = $_SESSION["idUser"];
            $newid = $this->getNewCommentId();
            $now = date('Y-m-d H:i:s');
            $stmt->bind_param(
                'iisis',
                $autore,
                $id,
                $text,
                $newid,
                $now
            );
            $res = $stmt->execute();

            $stmtNotf = $this->db->prepare("INSERT INTO Notifica (idNotifica, idUser, TestoNotifica, Letta, DataCreazione, idUserGeneratore) VALUES (?,?,?,?,?,?)");
            $newid = $this->getNewNotificationId();
            $post = $this->getInfoPost($id);
            $now = date('Y-m-d H:i:s');
            $autore = $post['idUser'];
            $testo = "Ha commentato il tuo post";
            $letta = 0;
            $idRelativo = $_SESSION["idUser"];
            $stmtNotf->bind_param('iisisi', $newid, $autore, $testo, $letta, $now, $idRelativo);
            $stmtNotf->execute();
            return $res;
        } else {
            return false;
        }
    }
    public function getParticipatingPosts($lastLoadedHelp)
    {
        if (isLogged()) {
            $stmt = $this->db->prepare("SELECT * FROM PostInterventi,Interventi WHERE idPostIntervento = idPostInterventi AND idPostIntervento > ? AND idUser = ?");
            $stmt->bind_param('ii', $lastLoadedHelp,  $_SESSION['idUser']);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }
    public function getSavedPosts()
    {
        if (isLogged()) {
            $stmt = $this->db->prepare("SELECT * FROM PostInterventi,PostSalvati WHERE idPostIntervento = idPostInterventi AND idUser = ?");
            $stmt->bind_param('i',  $_SESSION['idUser']);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }
    public function deleteComment($id)
    {
        if (isLogged() && $this->getCommentFromId($id)["Autore"] == $_SESSION["idUser"]) {
            $stmt = $this->db->prepare("DELETE FROM  Commento WHERE idCommento = ?");
            $stmt->bind_param('i', $id);
            return $stmt->execute();
        } else {
            return false;
        }
    }
    public function deleteUser($id)
    {
        if (isLogged() && $this->getUserFromId($id)["idUser"] == $_SESSION["idUser"]) {
            $stmt = $this->db->prepare("DELETE FROM  User WHERE idUser = ?");
            $stmt->bind_param('i', $id);
            return $stmt->execute();
        } else {
            return false;
        }
    }
    public function deleteHelpPost($id)
    {
        if (isLogged() && $this->getHelpPost($id)["Autore_idUser"] == $_SESSION["idUser"]) {
            $stmt = $this->db->prepare("DELETE FROM PostInterventi WHERE idPostIntervento = ?");
            $stmt->bind_param('i', $id);
            return $stmt->execute();
        } else {
            return "user not logged or not owner";
        }
    }
    public function deleteInfoPost($id)
    {
        if (isLogged() && $this->getInfoPost($id)["idUser"] == $_SESSION["idUser"]) {
            $stmt = $this->db->prepare("DELETE FROM HilfeDb.PostComunicazioni WHERE idPostComunicazione = ?");
            $stmt->bind_param('i', $id);
            return $stmt->execute();
        } else {
            return false;
        }
    }
    public function getCommentFromId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM  Commento WHERE idCommento = ?");
        $stmt->bind_param('i',  $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC)[0];
    }
    public function getAllUsers()
    {
        $stmt = $this->db->prepare("SELECT * FROM  User");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}




$dbh = new DatabaseHelperMySql("localhost", "root", "", "HilfeDb", 3306);
