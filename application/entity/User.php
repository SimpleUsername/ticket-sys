<?php
/*
 * TODO: validation
 */
namespace application\entity;

use Exception;

class UserTypeException extends Exception{}

class UserType {
    const ADMIN = 4;
    const MANAGER = 2;
    const SELLER = 1;
}

class UserException extends Exception{}

class User {
    private $_ID;
    private $_login;
    private $_name;
    private $_password;
    private $_type;
    private $_sessionID;
    private $_IP;

    /**
     * @return int
     */
    public function getID()
    {
        return $this->_ID;
    }

    /**
     * @param int $ID
     */
    public function setID($ID)
    {
        $this->_ID = $ID;
    }

    /**
     * @return string
     */
    public function getIP()
    {
        return $this->_IP;
    }

    /**
     * @param string $IP
     */
    public function setIP($IP)
    {
        $this->_IP = $IP;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->_login;
    }

    /**
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->_login = $login;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->_password = $password;
    }

    /**
     * @return string
     */
    public function getSessionID()
    {
        return $this->_sessionID;
    }

    /**
     * @param string $sessionID
     */
    public function setSessionID($sessionID)
    {
        $this->_sessionID = $sessionID;
    }

    /**
     * @return int
     * @throws UserException
     */
    public function getType()
    {
        if (isset($this->_type)) {
            return $this->_type;
        } else {
            throw new UserException('Тип пользователя не указан');
        }
    }

    /**
     * @param int $type
     * @throws UserException
     */
    public function setType($type)
    {
        if ($type >= 0 || $type <= UserType::ADMIN+UserType::MANAGER+UserType::SELLER) {
            $this->_type = $type;
        } else {
            throw new UserException('Неверно указан тип пользователя');
        }
    }


} 