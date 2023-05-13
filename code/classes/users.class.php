<?php
class Users
{
    // Attributs
    private $_IdUser;
    private $_Username;
    private $_Poste;
    private $_MdpHash;

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

    public function getIdUser()
    {
        return $this->_IdUser;
    }

    public function getUsername()
    {
        return $this->_Username;
    }

    public function getPoste()
    {
        return $this->_Poste;
    }

    public function getMdpHash()
    {
        return $this->_MdpHash;
    }

    public function setIdUser($idUser)
    {
        $this->_IdUser = $idUser;
    }

    public function setUsername($username)
    {
        if (is_string($username)) {
            $this->_Username = $username;
        }
    }

    public function setPoste($poste)
    {
        if (is_string($poste)) {
            $this->_Poste = $poste;
        }
    }

    public function setMdpHash($mdpHash)
    {
        if (is_string($mdpHash)) {
            $this->_MdpHash = $mdpHash;
        }
    }
}
