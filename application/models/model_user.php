<?php
/**
 * Created by PhpStorm.
 * User: Ilia
 * Date: 22.06.14
 * Time: 12:20
 */
class Model_User extends Model {
    public $users_table = "users";
    public $user_types_table = "user_types";

    public function get_user($user_login, $user_password) {
        $sql = "SELECT user_id, user_login, user_type_id FROM $this->users_table
            WHERE user_login LIKE '$user_login' AND user_password LIKE '$user_password'";
        return $this->db->sql($sql)[0];
    }
    public function set_user_login_data($user_id, $user_hash, $user_ip) {
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