<?php
class Columbarium
{
    // Attributs
    private $_IdConcession;
    private $_IdCasier;

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

    public function getIdCasier()
    {
        return $this->_IdCasier;
    }

    // Setters
    public function setIdConcession($idConcession)
    {
        $this->_IdConcession = $idConcession;
    }

    public function setIdCasier($idCasier)
    {
        $this->_IdCasier = $idCasier;
    }
}
