<?php
namespace application\models;

use application\core\Model;
use application\core\ModelException;
use application\entity\User;
use application\entity\UserType;

class Model_User extends Model {
    private $users_table = "users";

    /* @deprecated */
    public function get_user_by_login($user_login) {
        $select = $this->db->select($this->users_table, 'user_login=:user_login', array(":user_login"=>$user_login));
        return end($select);
    }
    /* @deprecated */
    public function get_user($user_login, $user_password) {
        $select = $this->db->select($this->users_table,
            'user_login=:user_login AND user_password=:user_password',
            array(":user_login"=>$user_login, ":user_password"=>$user_password));
        return end($select);
    }
    /* @deprecated */
    public function set_user_login_data($user_id, $user_hash=null, $user_ip='0.0.0.0') {
        $update = $this->update($this->users_table, array("user_hash" => $user_hash, "user_ip" => $user_ip),
            ' user_id = :user_id ', array(':user_id' => (int)$user_id));
        return $update;
    }
    /* @deprecated */
    public function get_user_by_id($user_id) {
        $select = $this->select($this->users_table, 'user_id=:user_id', array(':user_id' => (int)$user_id));
        return end($select);
    }
    /* @deprecated */
    public function set_user_password($user_id, $user_password) {
        $update = $this->update($this->users_table, array("user_password" => $user_password),
            ' user_id = :user_id ', array(':user_id' => (int)$user_id));
        return $update;
    }

    /**
     * @param string $login
     * @throws ModelException
     * @return User
     */
    public function getUserByLogin($login)
    {
        $select = $this->db->select($this->users_table, 'user_login=:user_login', array(":user_login"=>$login));
        $userRow = end($select);
        if ($userRow) {
            return $this->getUserFromArray(end($select));
        } else {
            throw new ModelException('Пользователь не найден');
        }
    }
    /**
     * @param int $ID
     * @throws ModelException
     * @return User
     */
    public function getUser($ID)
    {
        $select = $this->select($this->users_table, 'user_id=:user_id', array(':user_id' => (int)$ID));
        $userRow = end($select);
        if ($userRow) {
            return $this->getUserFromArray(end($select));
        } else {
            throw new ModelException('Пользователь не найден');
        }
    }
    public function setUser($ID, User $user)
    {
        //TODO exception if no user
        $update = $this->update(
            $this->users_table,
            array(
                'user_id' => $user->getID(),
                'user_login' => $user->getLogin(),
                'user_name' => $user->getName(),
                'user_password' => $user->getPassword(),
                'user_type' => $user->getType(),
                'user_hash' => $user->getSessionID(),
                'user_ip' => $user->getIP()
            ),
            ' user_id = :user_id ', array(':user_id' => (int)$ID));
        return $update;
    }
    protected function getUserFromArray(array $userRow)
    {
        $user = new User();
        $user->setID($userRow['user_id']);
        $user->setLogin($userRow['user_login']);
        $user->setName($userRow['user_name']);
        $user->setPassword($userRow['user_password']);
        $user->setType($userRow['user_type']);
        $user->setSessionID($userRow['user_hash']);
        $user->setIP($userRow['user_ip']);
        return $user;
    }
}