<?php
class Reposoir
{
    // Attributs
    private $_IdReposoir;
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
    public function getIdReposoir()
    {
        return $this->_IdReposoir;
    }

    public function getPlaceDispo()
    {
        return $this->_PlaceDispo;
    }

    // Setters
    public function setIdReposoir($idReposoir)
    {
        $this->_IdReposoir = $idReposoir;
    }

    public function setPlaceDispo($placeDispo)
    {
        $this->_PlaceDispo = $placeDispo;
    }
}
