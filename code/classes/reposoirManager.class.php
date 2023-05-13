<?php
class ReposoirManager
{
    private $_db;

    public function __construct($db)
    {
        $this->setDB($db);
    }

    // ADD
    public function add(Reposoir $reposoir)
    {
        $q = $this->_db->prepare('INSERT INTO REPOSOIR (IdReposoir, PlaceDispo) VALUES (:IdReposoir, :PlaceDispo)');
        $q->bindValue(':IdReposoir', $reposoir->getIdReposoir());
        $q->bindValue(':PlaceDispo', $reposoir->getPlaceDispo());
        $q->execute();
    }

    // UPDATE
    public function update(Reposoir $reposoir)
    {
        $q = $this->_db->prepare("UPDATE REPOSOIR SET PlaceDispo = :PlaceDispo WHERE IdReposoir = :IdReposoir");
        $q->bindValue(':IdReposoir', $reposoir->getIdReposoir());
        $q->bindValue(':PlaceDispo', $reposoir->getPlaceDispo());
        $q->execute();
    }

    // DELETE
    public function delete(Reposoir $reposoir)
    {
        $this->_db->exec("DELETE FROM REPOSOIR WHERE IdReposoir = " . $reposoir->getIdReposoir());
    }

    public function getInfoByIdReposoir(int $id)
    {
        $id = (int) $id;

        $q = $this->_db->query("SELECT IdReposoir, IFNULL(PlaceDispo, '--') AS PlaceDispo FROM REPOSOIR WHERE IdReposoir = $id");
        $donnees = $q->fetch(PDO::FETCH_ASSOC);

        return new Reposoir($donnees);
    }

    public function VerifReposoirByIdReposoir(int $id)
    {
        $id = (int) $id;

        $q = $this->_db->query("SELECT IdReposoir, IFNULL(PlaceDispo, '--') AS PlaceDispo FROM REPOSOIR WHERE IdReposoir = $id;");
        if ($q->rowCount() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function VerifReposoirByIdMort(int $id)
    {
        $q = $this->_db->query("SELECT MORT.IdReposoir FROM REPOSOIR, MORT WHERE REPOSOIR.IdReposoir = MORT.IdReposoir AND IdMort = $id;");
        if ($q->rowCount() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getInfoByIdMort(int $id)
    {
        $id = (int) $id;

        $q = $this->_db->query("SELECT REPOSOIR.IdReposoir, IFNULL(PlaceDispo, '--') AS PlaceDispo FROM REPOSOIR, MORT WHERE REPOSOIR.IdReposoir = MORT.IdReposoir AND IdMort = $id");
        $donnees = $q->fetch(PDO::FETCH_ASSOC);

        return new Reposoir($donnees);
    }

    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }
}

$db = new PDO('mysql:host=localhost;dbname=cimetiere_bdd', 'root', '');
$reposoirManager = new ReposoirManager($db);
