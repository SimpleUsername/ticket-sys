<?php
namespace application\core;

use application\core\Session;
use application\models\Model_Users;

class Authority {
    /* @var $_model Model_Users */
    private $_model;
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
        }
        $this->_session['last_activity'] = time();
    }
    public function setModel(Model_Users $model)
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