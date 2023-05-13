<?php
class ColumbariumManager
{
    private $_db;

    public function __construct($db)
    {
        $this->setDB($db);
    }

    // ADD
    public function add(Columbarium $columbarium)
    {
        $q = $this->_db->prepare('INSERT INTO COLUMBARIUM (IdConcession, IdCasier) VALUES (:IdConcession, :IdCasier)');
        $q->bindValue(':IdConcession', $columbarium->getIdConcession());
        $q->bindValue(':IdCasier', $columbarium->getIdCasier());
        $q->execute();
        $id = $this->_db->lastInsertId();
        return $id;
    }

    // UPDATE
    public function update(Columbarium $columbarium)
    {
        $q = $this->_db->prepare("UPDATE COLUMBARIUM SET IdCasier = :IdCasier WHERE IdConcession = :IdConcession");

        $q->bindValue(':IdConcession', $columbarium->getIdConcession());
        $q->bindValue(':IdCasier', $columbarium->getIdCasier());

        $q->execute();
    }

    // DELETE
    public function delete(Columbarium $columbarium)
    {
        $this->_db->exec("DELETE FROM COLUMBARIUM WHERE IdConcession = " . $columbarium->getIdConcession());
    }

    public function VerifColumbariumByIdConcession(int $id)
    {
        $q = $this->_db->query("SELECT COLUMBARIUM.IdConcession, IdCasier FROM COLUMBARIUM, CONCESSION WHERE COLUMBARIUM.IdConcession = CONCESSION.IdConcession AND CONCESSION.IdConcession = $id;");
        if ($q->rowCount() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function VerifColumbariumByIdCasier(int $id)
    {
        $q = $this->_db->query("SELECT COLUMBARIUM.IdConcession, IdCasier FROM COLUMBARIUM, CONCESSION WHERE COLUMBARIUM.IdConcession = CONCESSION.IdConcession AND IdCasier = $id;");
        if ($q->rowCount() > 0) {
            return 1;
        } else {
            return 0;
        }
    }


    public function VerifColumbariumAcheteur(int $id)
    {
        $q = $this->_db->query("SELECT COLUMBARIUM.IdConcession, IdCasier FROM COLUMBARIUM, CONCESSION, APPARTENIR WHERE COLUMBARIUM.IdConcession = CONCESSION.IdConcession AND APPARTENIR.IdConcession = CONCESSION.IdConcession AND APPARTENIR.IdAcheteur = $id;");
        if ($q->rowCount() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getListByIdColumbarium($id)
    {
        $id = (int) $id;
        $concessions = [];

        $q = $this->_db->query("SELECT IdCasier, APPARTENIR.IdConcession FROM CONCESSION, COLUMBARIUM, APPARTENIR WHERE CONCESSION.IdConcession = COLUMBARIUM.IdConcession AND CONCESSION.IdConcession = APPARTENIR.IdConcession AND IdAcheteur = $id ORDER BY IdCasier ASC");

        while ($donnees = $q->fetch(PDO::FETCH_ASSOC)) {
            $concessions[] = new Columbarium($donnees);
        }

        return $concessions;
    }

    public function getInfoByIdConcession($id)
    {
        $req = $this->_db->query("SELECT COLUMBARIUM.IdConcession, IdCasier FROM CONCESSION, COLUMBARIUM WHERE CONCESSION.IdConcession = COLUMBARIUM.IdConcession AND CONCESSION.IdConcession = $id;");
        $donnees = $req->fetch(PDO::FETCH_ASSOC);
        return new Columbarium($donnees);
    }

    public function getInfoByIdAcheteur($id)
    {
        $req = $this->_db->query("SELECT COLUMBARIUM.IdConcession, IdCasier FROM COLUMBARIUM, CONCESSION, APPARTENIR WHERE COLUMBARIUM.IdConcession = CONCESSION.IdConcession AND APPARTENIR.IdConcession = CONCESSION.IdConcession AND APPARTENIR.IdAcheteur = $id;");
        $donnees = $req->fetch(PDO::FETCH_ASSOC);
        return new Columbarium($donnees);
    }

    public function getInfoByIdCasier($id)
    {
        $req = $this->_db->query("SELECT CONCESSION.IdConcession, IdCasier FROM CONCESSION, COLUMBARIUM WHERE CONCESSION.IdConcession = COLUMBARIUM.IdConcession AND IdCasier = $id;");
        $donnees = $req->fetch(PDO::FETCH_ASSOC);
        return new Columbarium($donnees);
    }

    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }
}

$db = new PDO('mysql:host=localhost;dbname=cimetiere_bdd', 'root', '');
$columbariumManager = new ColumbariumManager($db);
