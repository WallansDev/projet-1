<?php
class Concession
{
    // Attributs
    private $_IdConcession;
    private $_PrixConcession;
    private $_PlaceDispo;
    private $_TailleConcession;
    private $_TempsConcession;

    public function __construct(array $donnees)
    {
        $this->hydrate($donnees);
    }

    public function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value) {
            $method = 'set' . ucfirst($key);

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    // Getters
    public function getIdConcession()
    {
        return $this->_IdConcession;
    }

    public function getPrixConcession()
    {
        return $this->_PrixConcession;
    }

    public function getPlaceDispo()
    {
        return $this->_PlaceDispo;
    }

    public function getTailleConcession()
    {
        return $this->_TailleConcession;
    }

    public function getTempsConcession()
    {
        return $this->_TempsConcession;
    }

    public function setIdConcession($idConcession)
    {
        $this->_IdConcession = $idConcession;
    }

    public function setPrixConcession($prix)
    {
        $this->_PrixConcession = $prix;
    }

    public function setPlaceDispo($placeDispo)
    {
        $this->_PlaceDispo = $placeDispo;
    }

    public function setTailleConcession($taille)
    {
        $this->_TailleConcession = $taille;
    }

    public function setTempsConcession($temps)
    {
        $this->_TempsConcession = $temps;
    }
}
