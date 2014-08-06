<?php
namespace application\core;

use Conf;
use Exception;
use application\entity\User;
use application\core\Session;
use application\models\Model_User;

class AuthorityException extends Exception{}

class Authority {
    /* @var $_model Model_User */
    private $_model;
    /* @var $_session Session */
    private $_session;
    private $_acceptedUserType;

    public function checkAuthority()
    {
        if ($this->isLoggedIn()) {
            $user = $this->_model->getUser($this->_session['user_id']);
            if (time() - $this->_session['last_activity'] > 30*60) {
                $this->showLoginPage('Истёк срок дейсвия сессии!');
            }
            if (session_id() != $user->getSessionID()) {
                $this->showLoginPage('Не актуальная сессия!');
            }
            if ($_SERVER['REMOTE_ADDR'] != $user->getIP()) {
                $this->showLoginPage('Сменился IP адрес!');
            }
            if (!($this->_session['user_type'] & $this->_acceptedUserType)) {
                //TODO error message
                Route::ErrorPage404();
            }
        } else {
            if ($_SERVER['REQUEST_URI'] != '/user/login') {
                Route::redirect('user/login');
            }
        }
        $this->_session['last_activity'] = time();
    }

    public function setModel(Model_User $model)
    {
        $this->_model = $model;
    }
    public function setSession(Session $session)
    {
        $this->_session = $session;
    }

    public function setAcceptedUserType($userType)
    {
        $this->_acceptedUserType = $userType;
    }

    public static function isLoggedIn()
    {
        $session = Session::getInstance();
        return isset($session['authorized']) && $session['authorized'] == 1;
    }
    public function login(User $user)
    {
        if ($user->getPassword() == md5(md5($_POST['password'] . Conf::SECURE_SALT))) {
            $user->setSessionID($this->_session->getID());
            $user->setIP($_SERVER['REMOTE_ADDR']);
            $this->_model->setUser($user->getID(), $user);
            $this->_session['authorized'] = 1;
            $this->_session['user_id'] = $user->getID();
            $this->_session['user_login'] = $user->getLogin();
            $this->_session['user_name'] = $user->getLogin();
            $this->_session['user_type'] = $user->getType();
            $this->_session['user_admin'] = $user->getType() & User::ADMIN;
            $this->_session['user_manager'] = $user->getType() & User::MANAGER;
            $this->_session['user_seller'] = $user->getType() & User::SELLER;
        } else {
            throw new AuthorityException('Неправильный пароль');
        }
    }

    public function logout(User $user)
    {
        if ($user->getSessionID() == $this->_session->getID()) {
            $user->setSessionID('');
            $this->_model->setUser($user->getID(), $user);
        }
        unset($this->_session['user_id']);
        unset($this->_session['user_name']);
        unset($this->_session['user_type']);
        unset($this->_session['user_admin']);
        unset($this->_session['user_manager']);
        unset($this->_session['user_seller']);
        $this->_session['authorized'] = 0;
    }

    public static function isA($userType)
    {
        $session = Session::getInstance();
        if (self::isLoggedIn()) {
            return $session['user_type'] | $userType;
        } else {
            return false;
        }
    }

    private function showLoginPage($error = null) {
        if ($error != null) {
            $this->_session['error'] = $error;
        }
        $this->_session['authorized'] = 0;
        Route::redirect('user/login');
    }
} 