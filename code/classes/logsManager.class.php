<?php
class LogsManager
{
    private $_db;

    public function __construct($db)
    {
        $this->setDB($db);
    }

    // ADD
    public function add(Logs $logs)
    {
        $q = $this->_db->prepare('INSERT INTO LOGS (IdUtilisateur, DateLogs, HeureLogs, MessageLogs, StatusLogs) VALUES (:IdUtilisateur, :DateLogs, :HeureLogs, :MessageLogs, :StatusLogs)');
        $q->bindValue(':IdUtilisateur', $logs->getIdUtilisateur());
        $q->bindValue(':DateLogs', date("Y-m-d"));
        $q->bindValue(':HeureLogs', date("H:i:s"));
        $q->bindValue(':MessageLogs', $logs->getMessageLogs());
        $q->bindValue(':StatusLogs', $logs->getStatusLogs());
        $q->execute();
    }

    public function getList()
    {
        $req = $this->_db->query('SELECT initCap(Username) AS Username, DateLogs, HeureLogs, MessageLogs, StatusLogs FROM LOGS, USERS WHERE USERS.IdUser = LOGS.IdUtilisateur ORDER BY IdLogs DESC LIMIT 23;');

        $donnees = $req->fetchAll(PDO::FETCH_ASSOC);
        $reversed = array_reverse($donnees);

        foreach ($reversed as $row) {
            echo ('<span class=' . $row["StatusLogs"] . '>' . $row["DateLogs"] . " " . $row["HeureLogs"] . " : " . $row["Username"] . " " . $row["MessageLogs"] . '</span><br>');
        }
    }

    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }
}

$db = new PDO('mysql:host=localhost;dbname=cimetiere_bdd', 'root', '');
$logsManager = new LogsManager($db);
