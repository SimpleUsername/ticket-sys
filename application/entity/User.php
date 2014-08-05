<?php
namespace application\entity;

use Exception;

class UserException extends Exception{}

class User {

    const ADMIN = 4;
    const MANAGER = 2;
    const SELLER = 1;

    private $_ID;
    private $_login;
    private $_name;
    private $_password;
    private $_type;
    private $_sessionID;
    private $_IP;

    private $_patternIP = '~^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$~';
    private $_patternLogin = '/^[a-z-\.]+$/i';
    private $_patternPassword = '/^[0-9a-f]{32}$/i';

    /**
     * @return int
     * @throws UserException
     */
    public function getID()
    {
        if (!isset($this->_ID)) {
            throw new UserException('Undefined property ID');
        }
        return $this->_ID;
    }

    /**
     * @param int $ID
     * @throws UserException
     */
    public function setID($ID)
    {
        if (is_int($ID)) {
            $this->_ID = $ID;
        } else {
            throw new UserException('Expected Argument 1 to be Integer');
        }
    }

    /**
     * @return string
     * @throws UserException
     */
    public function getIP()
    {
        if (!isset($this->_IP)) {
            throw new UserException('Undefined property IP');
        }
        return $this->_IP;
    }

    /**
     * @param string $IP
     * @throws UserException
     */
    public function setIP($IP)
    {
        if (!is_string($IP)) {
            throw new UserException('Expected Argument 1 to be String');
        }
        if (!preg_match($this->_patternIP, $IP)) {
            throw new UserException('Invalid IP');
        }
        $this->_IP = $IP;
    }

    /**
     * @return string
     * @throws UserException
     */
    public function getLogin()
    {
        if (!isset($this->_login)) {
            throw new UserException('Undefined property login');
        }
        return $this->_login;
    }

    /**
     * @param string $login
     * @throws UserException
     */
    public function setLogin($login)
    {
        if (!is_string($login)) {
            throw new UserException('Expected Argument 1 to be String');
        }
        if (strlen($login) < 5) {
            throw new UserException('Логин должен быть длинее 5 символов');
        }
        if (strlen($login) > 30) {
            throw new UserException('Логин должен быть короче 30 символов');
        }
        if (!preg_match($this->_patternLogin, $login)) {
            throw new UserException('Логин может состоять только из латинских символов, точки или дефиса');
        }
        $this->_login = $login;
    }

    /**
     * @return string
     * @throws UserException
     */
    public function getName()
    {
        if (!isset($this->_name)) {
            throw new UserException('Undefined property name');
        }
        return $this->_name;
    }

    /**
     * @param string $name
     * @throws UserException
     */
    public function setName($name)
    {
        //TODO: validations
        if (is_string($name)) {
            $this->_name = $name;
        } else {
            throw new UserException('Expected Argument 1 to be String');
        }
    }

    /**
     * @return string
     * @throws UserException
     */
    public function getPassword()
    {
        if (!isset($this->_password)) {
            throw new UserException('Undefined property password');
        }
        return $this->_password;
    }

    /**
     * @param string $password
     * @throws UserException
     */
    public function setPassword($password)
    {
        if (!is_string($password)) {
            throw new UserException('Expected Argument 1 to be String');
        }
        if (!preg_match($this->_patternPassword, $password)) {
            throw new UserException('Invalid MD5 hash string');
        }
        $this->_password = $password;
    }

    /**
     * @return string
     * @throws UserException
     */
    public function getSessionID()
    {
        if (!isset($this->_sessionID)) {
            throw new UserException('Undefined property sessionID');
        }
        return $this->_sessionID;
    }

    /**
     * @param string $sessionID
     * @throws UserException
     */
    public function setSessionID($sessionID)
    {
        if (is_string($sessionID)) {
            $this->_sessionID = $sessionID;
        } else {
            throw new UserException('Expected Argument 1 to be String');
        }
    }

    /**
     * @return int
     * @throws UserException
     */
    public function getType()
    {
        if (!isset($this->_type)) {
            throw new UserException('Undefined property type');
        }
        return $this->_type;
    }

    /**
     * @param int $type
     * @throws UserException
     */
    public function setType($type)
    {
        if (!is_int($type)) {
            throw new UserException('Expected Argument 1 to be Integer');
        }
        if ($type < 0 || $type > self::ADMIN + self::MANAGER + self::SELLER) {
            throw new UserException('Неверно указан тип пользователя');
        }
        $this->_type = $type;
    }


} 