<?php
class TombeCommunale
{
    // Attributs
    private $_IdCommunale;
    private $_PlaceDispo;

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
    public function getIdCommunale()
    {
        return $this->_IdCommunale;
    }

    public function getPlaceDispo()
    {
        return $this->_PlaceDispo;
    }

    // Setters
    public function setIdCommunale($idCommunale)
    {
        $this->_IdCommunale = $idCommunale;
    }

    public function setPlaceDispo($placeDispo)
    {
        $this->_PlaceDispo = $placeDispo;
    }
}
