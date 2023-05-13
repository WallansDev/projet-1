<?php
class MortManager
{
    private $_db;

    public function __construct($db)
    {
        $this->setDB($db);
    }

    // ADD
    public function add(Mort $mort)
    {
        $q = $this->_db->prepare('INSERT INTO MORT (NomMort, NomJeuneMort, PrenomMort, SexeMort, DateNaissance, DateMort, DateObseques, IdConcession, IdCommunale, IdReposoir) VALUES (:NomMort, :NomJeuneMort, :PrenomMort, :SexeMort, :DateNaissance, :DateMort, :DateObseques, :IdConcession, :IdCommunale, :IdReposoir)');
        $q->bindValue(':NomMort', $mort->getNomMort());
        $q->bindValue(':NomJeuneMort', $mort->getNomJeuneMort());
        $q->bindValue(':PrenomMort', $mort->getPrenomMort());
        $q->bindValue(':SexeMort', $mort->getSexeMort());
        $q->bindValue(':DateNaissance', $mort->getDateNaissance());
        $q->bindValue(':DateMort', $mort->getDateMort());
        $q->bindValue(':DateObseques', $mort->getDateObseques());
        $q->bindValue(':IdConcession', $mort->getIdConcession());
        $q->bindValue(':IdCommunale', $mort->getIdCommunale());
        $q->bindValue(':IdReposoir', $mort->getIdReposoir());
        $q->execute();
        $id = $this->_db->lastInsertId();
        return $id;
    }

    // UPDATE
    public function update(Mort $mort)
    {
        $q = $this->_db->prepare("UPDATE MORT SET NomMort = :NomMort, NomJeuneMort = :NomJeuneMort, PrenomMort = :PrenomMort, SexeMort = :SexeMort, DateNaissance = :DateNaissance, DateMort = :DateMort, DateObseques = :DateObseques, IdConcession = :IdConcession, IdCommunale = :IdCommunale, IdReposoir = :IdReposoir WHERE IdMort = :IdMort");
        $q->bindValue(':IdMort', $mort->getIdMort());
        $q->bindValue(':NomMort', $mort->getNomMort());
        $q->bindValue(':NomJeuneMort', $mort->getNomJeuneMort());
        $q->bindValue(':PrenomMort', $mort->getPrenomMort());
        $q->bindValue(':SexeMort', $mort->getSexeMort());
        $q->bindValue(':DateNaissance', $mort->getDateNaissance());
        $q->bindValue(':DateMort', $mort->getDateMort());
        $q->bindValue(':DateObseques', $mort->getDateObseques());
        $q->bindValue(':IdConcession', $mort->getIdConcession());
        $q->bindValue(':IdCommunale', $mort->getIdCommunale());
        $q->bindValue(':IdReposoir', $mort->getIdReposoir());
        $q->execute();
    }

    // DELETE
    public function deleteMort(Mort $mort)
    {
        $this->_db->exec('DELETE FROM MORT WHERE IdMort = ' . $mort->getIdMort());
    }

    public function deleteMortConcession(Mort $mort)
    {
        $this->_db->exec('DELETE FROM MORT WHERE IdConcession = ' . $mort->getIdConcession());
    }

    public function getList()
    {
        $morts = [];

        $req = $this->_db->query("SELECT IdMort, IFNULL(UPPER(NomMort), '--') AS NomMort, IFNULL(UPPER(NomJeuneMort), '--') AS NomJeuneMort, initCap(IFNULL(PrenomMort, '--')) AS PrenomMort, initCap(IFNULL(SexeMort, '--')) AS SexeMort, IFNULL(DateNaissance, '--') AS DateNaissance, IFNULL(DateMort, '--') AS DateMort, IFNULL(DateObseques, '--') AS DateObseques, IdConcession FROM MORT ORDER BY IdMort ASC;");

        while ($donnees = $req->fetch(PDO::FETCH_ASSOC)) {
            $morts[] = new Mort($donnees);
        }

        return $morts;
    }

    public function getListByIdConcession($id)
    {
        $id = (int) $id;
        $morts = [];

        $q = $this->_db->query("SELECT IdMort, IdConcession FROM MORT WHERE IdConcession = $id ORDER BY IdMort ASC");

        while ($donnees = $q->fetch(PDO::FETCH_ASSOC)) {
            $morts[] = new Mort($donnees);
        }

        return $morts;
    }

    public function getListByIdCommunale($id)
    {
        $id = (int) $id;
        $morts = [];

        $q = $this->_db->query("SELECT IdMort, IdCommunale FROM MORT WHERE IdCommunale = $id ORDER BY IdMort ASC");

        while ($donnees = $q->fetch(PDO::FETCH_ASSOC)) {
            $morts[] = new Mort($donnees);
        }

        return $morts;
    }

    public function getListByIdReposoir($id)
    {
        $id = (int) $id;
        $morts = [];

        $q = $this->_db->query("SELECT IdMort, IdReposoir FROM MORT WHERE IdReposoir = $id ORDER BY IdMort ASC");

        while ($donnees = $q->fetch(PDO::FETCH_ASSOC)) {
            $morts[] = new Mort($donnees);
        }

        return $morts;
    }

    public function getId($id)
    {
        $id = (int) $id;

        $q = $this->_db->query('SELECT IdMort, IFNULL(UPPER(NomMort), "--") AS NomMort, initCap(IFNULL(NomJeuneMort, "--")) AS NomJeuneMort, initCap(IFNULL(PrenomMort, "--")) AS PrenomMort, IFNULL(SexeMort, "--") AS SexeMort, IFNULL(DateNaissance, "--") AS DateNaissance, IFNULL(DateMort, "--") AS DateMort, IFNULL(DateObseques, "--") AS DateObseques, IdConcession, IdCommunale, IdReposoir FROM MORT WHERE IdMort = ' . $id);
        $donnees = $q->fetch(PDO::FETCH_ASSOC);

        return new Mort($donnees);
    }

    public function getByIdConcession($id)
    {
        $id = (int) $id;

        $q = $this->_db->query("SELECT IdMort FROM MORT, CONCESSION WHERE MORT.IdConcession = CONCESSION.IdConcession AND MORT.IdConcession = $id;");
        $donnees = $q->fetch(PDO::FETCH_ASSOC);

        return new Mort($donnees);
    }

    public function VerifMort(int $id)
    {
        $q = $this->_db->query("SELECT IdMort FROM MORT WHERE IdMort = $id;");
        if ($q->rowCount() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function VerifMortByIdConcession(int $id)
    {
        $q = $this->_db->query("SELECT IdMort FROM MORT, CONCESSION WHERE MORT.IdConcession = CONCESSION.IdConcession AND MORT.IdConcession = $id;");
        if ($q->rowCount() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function VerifMortByIdCommunale(int $id)
    {
        $q = $this->_db->query("SELECT TOMBECOMMUNALE.IdCommunale, PlaceDispo FROM TOMBECOMMUNALE, MORT WHERE TOMBECOMMUNALE.IdCommunale = MORT.IdCommunale AND TOMBECOMMUNALE.IdCommunale = $id;");
        if ($q->rowCount() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function VerifMortByIdReposoir(int $id)
    {
        $q = $this->_db->query("SELECT REPOSOIR.IdReposoir FROM REPOSOIR, MORT WHERE REPOSOIR.IdReposoir = MORT.IdReposoir AND MORT.IdReposoir = $id;");
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
$mortManager = new MortManager($db);
