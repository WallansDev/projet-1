<?php
class TombeCivile
{
    // Attributs
    private $_IdConcession;
    private $_NumeroPlan;

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

    public function getNumeroPlan()
    {
        return $this->_NumeroPlan;
    }

    // Setters
    public function setIdConcession($idConcession)
    {
        $this->_IdConcession = $idConcession;
    }

    public function setNumeroPlan($numeroPlan)
    {
        $this->_NumeroPlan = $numeroPlan;
    }
}
