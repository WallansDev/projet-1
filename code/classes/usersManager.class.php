<?php
class UsersManager
{
    private $_db;

    public function __construct($db)
    {
        $this->setDB($db);
    }

    public function add(Users $user)
    {
        $q = $this->_db->prepare('INSERT INTO USERS(Username, Poste, MdpHash) VALUES (:Username, :Poste, :MdpHash)');
        $q->bindValue(':Username', $user->getUsername());
        $q->bindValue(':Poste', $user->getPoste());
        $q->bindValue(':MdpHash', $user->getMdpHash());
        $q->execute();
    }

    public function getUser($inputUsername)
    {
        $q = $this->_db->query('SELECT IdUser, Username, IdUser, Poste, MdpHash FROM USERS WHERE Username = "' . $inputUsername . '"');
        $userInfo = $q->fetch(PDO::FETCH_ASSOC);
        if ($userInfo) {
            return new Users($userInfo);
        } else {
            return $userInfo;
        }
    }

    public function getIdUsers($username)
    {

        $req = $this->_db->query("SELECT IdUser FROM USERS WHERE Username = '$username'");

        $donnees = $req->fetch(PDO::FETCH_ASSOC);

        foreach ($donnees as $row) {
            echo ($row['IdUser']);
        }
    }

    public function count()
    {
        return $this->_db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }

    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }
}

$db = new PDO('mysql:host=localhost;dbname=cimetiere_bdd', 'root', '');
$userManager = new UsersManager($db);
