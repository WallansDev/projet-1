<?php
class Mort
{
    // Attributs
    private $_IdMort;
    private $_NomMort;
    private $_NomJeuneMort;
    private $_PrenomMort;
    private $_SexeMort;
    private $_DateNaissance;
    private $_DateMort;
    private $_DateObseques;
    private $_IdConcession;
    private $_IdCommunale;
    private $_IdReposoir;

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
    public function getIdMort()
    {
        return $this->_IdMort;
    }

    public function getNomMort()
    {
        return $this->_NomMort;
    }

    public function getNomJeuneMort()
    {
        return $this->_NomJeuneMort;
    }

    public function getPrenomMort()
    {
        return $this->_PrenomMort;
    }

    public function getSexeMort()
    {
        return $this->_SexeMort;
    }

    public function getDateNaissance()
    {
        return $this->_DateNaissance;
    }

    public function getDateMort()
    {
        return $this->_DateMort;
    }

    public function getDateObseques()
    {
        return $this->_DateObseques;
    }

    public function getIdConcession()
    {
        return $this->_IdConcession;
    }

    public function getIdCommunale()
    {
        return $this->_IdCommunale;
    }

    public function getIdReposoir()
    {
        return $this->_IdReposoir;
    }

    // Setters
    public function setIdMort($idMort)
    {
        $this->_IdMort = $idMort;
    }

    public function setNomMort($nomMort)
    {
        $this->_NomMort = $nomMort;
    }

    public function setNomJeuneMort($nomJeuneMort)
    {
        $this->_NomJeuneMort = $nomJeuneMort;
    }


    public function setPrenomMort($prenomMort)
    {
        $this->_PrenomMort = $prenomMort;
    }

    public function setSexeMort($sexeMort)
    {
        $this->_SexeMort = $sexeMort;
    }

    public function setDateNaissance($dateNaissance)
    {
        $this->_DateNaissance = $dateNaissance;
    }

    public function setDateMort($dateMort)
    {
        $this->_DateMort = $dateMort;
    }

    public function setDateObseques($dateObseques)
    {
        $this->_DateObseques = $dateObseques;
    }

    public function setIdConcession($idConcession)
    {
        $this->_IdConcession = $idConcession;
    }

    public function setIdCommunale($idCommunale)
    {
        $this->_IdCommunale = $idCommunale;
    }

    public function setIdReposoir($idReposoir)
    {
        $this->_IdReposoir = $idReposoir;
    }
}
