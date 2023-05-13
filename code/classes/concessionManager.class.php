<?php
class ConcessionManager
{
    private $_db;

    public function __construct($db)
    {
        $this->setDB($db);
    }

    // ADD
    public function add(Concession $concession)
    {
        $q = $this->_db->prepare('INSERT INTO CONCESSION (PrixConcession, PlaceDispo, TailleConcession, TempsConcession) VALUES (:PrixConcession, :PlaceDispo, :TailleConcession, :TempsConcession)');
        $q->bindValue(':PrixConcession', $concession->getPrixConcession());
        $q->bindValue(':PlaceDispo', $concession->getPlaceDispo());
        $q->bindValue(':TailleConcession', $concession->getTailleConcession());
        $q->bindValue(':TempsConcession', $concession->getTempsConcession());
        $q->execute();
        $id = $this->_db->lastInsertId();
        return $id;
    }

    // UPDATE
    public function update(Concession $concession)
    {
        $q = $this->_db->prepare("UPDATE CONCESSION SET PrixConcession = :Prix, PlaceDispo = :PlaceDispo, TailleConcession = :TailleConcession, TempsConcession = :TempsConcession WHERE IdConcession = :IdConcession");

        $q->bindValue(':IdConcession', $concession->getIdConcession());
        $q->bindValue(':Prix', $concession->getPrixConcession());
        $q->bindValue(':PlaceDispo', $concession->getPlaceDispo());
        $q->bindValue(':TailleConcession', $concession->getTailleConcession());
        $q->bindValue(':TempsConcession', $concession->getTempsConcession());

        $q->execute();
    }

    // DELETE
    public function delete(Concession $concession)
    {
        $this->_db->exec("DELETE FROM CONCESSION WHERE IdConcession = " . $concession->getIdConcession());
    }

    public function getList()
    {
        $concession = [];

        $req = $this->_db->query("SELECT IdConcession, IFNULL(PrixConcession, '--') AS PrixConcession, IFNULL(PlaceDispo, '--') AS PlaceDispo, initCap(IFNULL(TailleConcession, '--')) AS TailleConcession, IFNULL(TempsConcession, '--') AS TempsConcession FROM CONCESSION ORDER BY IdConcession ASC;");

        while ($donnees = $req->fetch(PDO::FETCH_ASSOC)) {
            $concession[] = new Concession($donnees);
        }

        return $concession;
    }

    public function getInfoById($id)
    {

        $req = $this->_db->query("SELECT IdConcession, IFNULL(PrixConcession, '--') AS PrixConcession, IFNULL(PlaceDispo, '--') AS PlaceDispo, initCap(IFNULL(TailleConcession, '--')) AS TailleConcession, IFNULL(TempsConcession, '--') AS TempsConcession FROM CONCESSION WHERE IdConcession = $id ORDER BY IdConcession ASC;");
        $donnees = $req->fetch(PDO::FETCH_ASSOC);
        return new Concession($donnees);
    }

    public function VerifConcession(int $id)
    {
        $q = $this->_db->query("SELECT IdConcession FROM Concession, Appartenir WHERE Concession.IdConcession = Appartenir.IdConcession AND Concession.IdConcession = $id;");
        if ($q->rowCount() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function VerifConcessionByIdMort(int $id)
    {
        $q = $this->_db->query("SELECT CONCESSION.IdConcession FROM CONCESSION, MORT WHERE MORT.IdConcession = CONCESSION.IdConcession AND IdMort = $id;");
        if ($q->rowCount() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getInfoByIdMort($id)
    {

        $req = $this->_db->query("SELECT CONCESSION.IdConcession, IFNULL(PrixConcession, '--') AS PrixConcession, IFNULL(PlaceDispo, '--') AS PlaceDispo, initCap(IFNULL(TailleConcession, '--')) AS TailleConcession, IFNULL(TempsConcession, '--') AS TempsConcession FROM CONCESSION, MORT WHERE CONCESSION.IdConcession = MORT.IdConcession AND IdMort = $id;");
        $donnees = $req->fetch(PDO::FETCH_ASSOC);
        return new Concession($donnees);
    }

    public function isCivile($id)
    {
        $q = $this->_db->query("SELECT TOMBECIVILE.IdConcession, NumeroPlan FROM CONCESSION, TOMBECIVILE WHERE TOMBECIVILE.IdConcession = CONCESSION.IdConcession AND CONCESSION.IdConcession = $id");
        if ($q->rowCount() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function isColumbarium($id)
    {
        $q = $this->_db->query("SELECT COLUMBARIUM.IdConcession, IdCasier FROM CONCESSION, COLUMBARIUM WHERE COLUMBARIUM.IdConcession = CONCESSION.IdConcession AND CONCESSION.IdConcession = $id");
        if ($q->rowCount() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }
}

$db = new PDO('mysql:host=localhost;dbname=cimetiere_bdd', 'root', '');
$concessionManager = new ConcessionManager($db);
