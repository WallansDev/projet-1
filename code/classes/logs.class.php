<?php
class Logs
{
    private $_IdLogs;
    private $_IdUtilisateur;
    private $_DateLogs;
    private $_HeureLogs;
    private $_MessageLogs;
    private $_StatusLogs;

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
    public function getIdLogs()
    {
        return $this->_IdLogs;
    }

    public function getIdUtilisateur()
    {
        return $this->_IdUtilisateur;
    }

    public function getDateLogs()
    {
        return $this->_DateLogs;
    }

    public function getHeureLogs()
    {
        return $this->_HeureLogs;
    }

    public function getMessageLogs()
    {
        return $this->_MessageLogs;
    }

    public function getStatusLogs()
    {
        return $this->_StatusLogs;
    }

    // Setters
    public function setIdUtilisateur($idUtilisateur)
    {
        $this->_IdUtilisateur = $idUtilisateur;
    }

    public function setDateLogs($dateLogs)
    {
        $this->_DateLogs = $dateLogs;
    }

    public function setHeureLogs($heureLogs)
    {
        $this->_HeureLogs = $heureLogs;
    }

    public function setMessageLogs($messageLogs)
    {
        $this->_MessageLogs = $messageLogs;
    }

    public function setStatusLogs($status)
    {
        $this->_StatusLogs = $status;
    }
}
