<?php
class TombeCommunaleManager
{
    private $_db;

    public function __construct($db)
    {
        $this->setDB($db);
    }

    // ADD
    public function add(TombeCommunale $tombe)
    {
        $q = $this->_db->prepare('INSERT INTO TombeCommunale (IdCommunale, PlaceDispo) VALUES (:IdCommunale, :PlaceDispo)');
        $q->bindValue(':IdCommunale', $tombe->getIdCommunale());
        $q->bindValue(':PlaceDispo', $tombe->getPlaceDispo());
        $q->execute();
    }

    // UPDATE
    public function update(TombeCommunale $tombe)
    {
        $q = $this->_db->prepare("UPDATE TOMBECOMMUNALE SET PlaceDispo = :PlaceDispo WHERE IdCommunale = :IdCommunale");
        $q->bindValue(':IdCommunale', $tombe->getIdCommunale());
        $q->bindValue(':PlaceDispo', $tombe->getPlaceDispo());
        $q->execute();
    }

    // DELETE
    public function delete(TombeCommunale $tombe)
    {
        $this->_db->exec("DELETE FROM TOMBECOMMUNALE WHERE IdCommunale = " . $tombe->getIdCommunale());
    }

    public function getInfoByIdCommunale(int $id)
    {
        $id = (int) $id;

        $q = $this->_db->query("SELECT IdCommunale, IFNULL(PlaceDispo, '--') AS PlaceDispo FROM TOMBECOMMUNALE WHERE IdCommunale = $id");
        $donnees = $q->fetch(PDO::FETCH_ASSOC);

        return new TombeCommunale($donnees);
    }

    public function VerifCommunaleByIdCommunale(int $id)
    {
        $id = (int) $id;

        $q = $this->_db->query("SELECT IdCommunale, IFNULL(PlaceDispo, '--') AS PlaceDispo FROM TOMBECOMMUNALE WHERE IdCommunale = $id;");
        if ($q->rowCount() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function VerifCommunaleByIdMort(int $id)
    {
        $q = $this->_db->query("SELECT MORT.IdCommunale FROM TOMBECOMMUNALE, MORT WHERE TOMBECOMMUNALE.IdCommunale = MORT.IdCommunale AND IdMort = $id;");
        if ($q->rowCount() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getInfoByIdMort(int $id)
    {
        $id = (int) $id;

        $q = $this->_db->query("SELECT TOMBECOMMUNALE.IdCommunale, IFNULL(PlaceDispo, '--') AS PlaceDispo FROM TOMBECOMMUNALE, MORT WHERE TOMBECOMMUNALE.IdCommunale = MORT.IdCommunale AND IdMort = $id");
        $donnees = $q->fetch(PDO::FETCH_ASSOC);

        return new TombeCommunale($donnees);
    }

    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }
}

$db = new PDO('mysql:host=localhost;dbname=cimetiere_bdd', 'root', '');
$tombeCommunaleManager = new TombeCommunaleManager($db);
