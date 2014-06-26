<?php
/**
 * Created by PhpStorm.
 * User: Ilia
 * Date: 22.06.14
 * Time: 12:20
 */
class Model_User extends Model {
    private $users_table = "users";

    public function get_user_by_login_data($user_login, $user_password) {
        $select = $this->db->select($this->users_table,
            'user_login LIKE :user_login AND user_password LIKE :user_password',
            array(":user_login"=>$user_login, ":user_password"=>$user_password));
        return $select[0];
    }
    public function get_user($user_login, $user_password) {
        $select = $this->db->select($this->users_table,
            'user_login LIKE :user_login AND user_password LIKE :user_password',
            array(":user_login"=>$user_login, ":user_password"=>$user_password));
        return $select[0];
    }
    public function set_user_login_data($user_id, $user_hash=null, $user_ip='0.0.0.0') {
        $update = $this->update($this->users_table, array("user_hash" => $user_hash, "user_ip" => $user_ip),
            ' user_id = :user_id ', array(':user_id' => (int)$user_id));
        return $update;
    }
    public function set_user_password($user_id, $user_password) {
        $update = $this->update($this->users_table, array("user_password" => $user_password),
            ' user_id = :user_id ', array(':user_id' => (int)$user_id));
        return $update;
    }
}