<?php
namespace application\models;

use PDOException;
use application\core\ModelException;
use application\entity\User;
use application\core\Model;
use application\models\Model_User;

class Model_Users extends Model_User {
    private $user_types_table = "user_types";

    public function get_user_types () {
        $select = $this->db->select($this->user_types_table);
        return  $select;
    }
    public function getAllUsers()
    {
        $usersRows = $this->db->get_records($this->users_table);
        $users = array();
        foreach ($usersRows as $userRow) {
            $users[] = $this->getUserFromArray($userRow);
        }
        return $users;
    }
    public function addUser(User $user)
    {
        try {
            $insert = $this->insert($this->users_table,
                array(
                    'user_login' => $user->getLogin(),
                    'user_name' => $user->getName(),
                    'user_type' => $user->getType(),
                    'user_password' => $user->getPassword()
                ));
            return $insert;
        } catch (PDOException $e) {
            //TODO: explain
            throw new ModelException('Ошибка добавления пользователя');
        }
    }
    public function deleteUser($ID)
    {
        $delete = $this->delete($this->users_table, ' user_id = :user_id ', array(':user_id' => $ID));
        return $delete;
    }
}
