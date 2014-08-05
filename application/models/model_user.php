<?php
namespace application\models;

use application\core\Model;
use application\core\ModelException;
use application\entity\User;

class Model_User extends Model {
    protected $users_table = "users";

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
        $affectedUser = $this->getUser($ID);
        $affectedUser->setName($user->getName());
        $affectedUser->setPassword($user->getPassword());
        $affectedUser->setSessionID($user->getSessionID());
        $affectedUser->setIP($user->getIP());
        $this->update(
            $this->users_table,
            array(
                'user_name' => $affectedUser->getName(),
                'user_password' => $affectedUser->getPassword(),
                'user_hash' => $affectedUser->getSessionID(),
                'user_ip' => $affectedUser->getIP()
            ),
            ' user_id = :user_id ', array(':user_id' => (int)$ID
            )
        );
        return $affectedUser;
    }
    protected function getUserFromArray(array $userRow)
    {
        $user = new User();
        $user->setID((int)$userRow['user_id']);
        $user->setLogin($userRow['user_login']);
        $user->setName($userRow['user_name']);
        $user->setPassword($userRow['user_password']);
        $user->setType((int)$userRow['user_type']);
        $user->setSessionID($userRow['user_hash']);
        $user->setIP($userRow['user_ip']);
        return $user;
    }
}