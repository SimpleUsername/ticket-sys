<?php
namespace application\models;

use application\core\Model;

class Model_User extends Model {
    private $users_table = "users";

    public function get_user_by_login($user_login) {
        $select = $this->db->select($this->users_table, 'user_login=:user_login', array(":user_login"=>$user_login));
        return end($select);
    }
    public function get_user($user_login, $user_password) {
        $select = $this->db->select($this->users_table,
            'user_login=:user_login AND user_password=:user_password',
            array(":user_login"=>$user_login, ":user_password"=>$user_password));
        return end($select);
    }
    public function set_user_login_data($user_id, $user_hash=null, $user_ip='0.0.0.0') {
        $update = $this->update($this->users_table, array("user_hash" => $user_hash, "user_ip" => $user_ip),
            ' user_id = :user_id ', array(':user_id' => (int)$user_id));
        return $update;
    }
    public function get_user_by_id($user_id) {
        $select = $this->select($this->users_table, 'user_id=:user_id', array(':user_id' => (int)$user_id));
        return end($select);
    }
    public function set_user_password($user_id, $user_password) {
        $update = $this->update($this->users_table, array("user_password" => $user_password),
            ' user_id = :user_id ', array(':user_id' => (int)$user_id));
        return $update;
    }



}