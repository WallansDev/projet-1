<?php
class TombeCivileManager
{
    private $_db;

    public function __construct($db)
    {
        $this->setDB($db);
    }

    // ADD
    public function add(TombeCivile $tombe)
    {
        $q = $this->_db->prepare('INSERT INTO TOMBECIVILE (IdConcession, NumeroPlan) VALUES (:IdConcession, :NumeroPlan)');
        $q->bindValue(':IdConcession', $tombe->getIdConcession());
        $q->bindValue(':NumeroPlan', $tombe->getNumeroPlan());
        $q->execute();
        $id = $this->_db->lastInsertId();
        return $id;
    }

    // UPDATE
    public function update(TombeCivile $tombe)
    {
        $q = $this->_db->prepare("UPDATE TOMBECIVILE SET NumeroPlan = :NumeroPlan WHERE IdConcession = :IdConcession");
        $q->bindValue(':IdConcession', $tombe->getIdConcession());
        $q->bindValue(':NumeroPlan', $tombe->getNumeroPlan());

        $q->execute();
    }

    // DELETE
    public function delete(TombeCivile $tombe)
    {
        $this->_db->exec("DELETE FROM TOMBECIVILE WHERE IdConcession = " . $tombe->getIdConcession());
    }

    public function VerifCivileByIdConcession(int $id)
    {
        $q = $this->_db->query("SELECT TOMBECIVILE.IdConcession, NumeroPlan FROM TOMBECIVILE, CONCESSION WHERE TOMBECIVILE.IdConcession = CONCESSION.IdConcession AND CONCESSION.IdConcession = $id;");
        if ($q->rowCount() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function VerifCivileByNumeroPlan(int $id)
    {
        $q = $this->_db->query("SELECT TOMBECIVILE.IdConcession, NumeroPlan FROM TOMBECIVILE, CONCESSION WHERE TOMBECIVILE.IdConcession = CONCESSION.IdConcession AND numeroPlan = $id;");
        if ($q->rowCount() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function VerifCivileAcheteur(int $id)
    {
        $q = $this->_db->query("SELECT TOMBECIVILE.IdConcession, NumeroPlan FROM TOMBECIVILE, CONCESSION, APPARTENIR WHERE TOMBECIVILE.IdConcession = CONCESSION.IdConcession AND APPARTENIR.IdConcession = CONCESSION.IdConcession AND APPARTENIR.IdAcheteur = $id;");
        if ($q->rowCount() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getListById($id)
    {
        $id = (int) $id;
        $concessions = [];

        $q = $this->_db->query("SELECT IdConcession FROM APPARTENIR WHERE IdAcheteur = $id ORDER BY IdConcession ASC");

        while ($donnees = $q->fetch(PDO::FETCH_ASSOC)) {
            $concessions[] = new TombeCivile($donnees);
        }

        return $concessions;
    }

    public function getListByIdCivile($id)
    {
        $id = (int) $id;
        $concessions = [];

        $q = $this->_db->query("SELECT NumeroPlan, APPARTENIR.IdConcession FROM CONCESSION, TOMBECIVILE, APPARTENIR WHERE CONCESSION.IdConcession = TOMBECIVILE.IdConcession AND CONCESSION.IdConcession = APPARTENIR.IdConcession AND IdAcheteur = $id ORDER BY NumeroPlan ASC");

        while ($donnees = $q->fetch(PDO::FETCH_ASSOC)) {
            $concessions[] = new TombeCivile($donnees);
        }

        return $concessions;
    }

    public function getInfoByIdConcession($id)
    {
        $req = $this->_db->query("SELECT TOMBECIVILE.IdConcession, NumeroPlan FROM CONCESSION, TOMBECIVILE WHERE CONCESSION.IdConcession = TOMBECIVILE.IdConcession AND CONCESSION.IdConcession = $id;");
        $donnees = $req->fetch(PDO::FETCH_ASSOC);
        return new TombeCivile($donnees);
    }

    public function getInfoByIdAcheteur($id)
    {
        $req = $this->_db->query("SELECT TOMBECIVILE.IdConcession, NumeroPlan FROM TOMBECIVILE, CONCESSION, APPARTENIR WHERE TOMBECIVILE.IdConcession = CONCESSION.IdConcession AND APPARTENIR.IdConcession = CONCESSION.IdConcession AND APPARTENIR.IdAcheteur = $id;");
        $donnees = $req->fetch(PDO::FETCH_ASSOC);
        return new TombeCivile($donnees);
    }

    public function getInfoByNumeroPlan($id)
    {
        $req = $this->_db->query("SELECT CONCESSION.IdConcession, NumeroPlan FROM CONCESSION, TOMBECIVILE WHERE CONCESSION.IdConcession = TOMBECIVILE.IdConcession AND NumeroPlan = $id;");
        $donnees = $req->fetch(PDO::FETCH_ASSOC);
        return new TombeCivile($donnees);
    }

    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }
}

$db = new PDO('mysql:host=localhost;dbname=cimetiere_bdd', 'root', '');
$tombeCivileManager = new TombeCivileManager($db);
