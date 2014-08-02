<?php
namespace application\models;

use application\core\Model;

class Model_Users extends Model {
    private $users_table = "users";
    private $user_types_table = "user_types";

    public function get_user_types () {
        $select = $this->db->select($this->user_types_table);
        return  $select;
    }
    public function get_users($data = null) {
        $select = $this->db->get_records($this->users_table, $data);
        if ($data == null) {
            return  $select;
        } else {
            return end($select);
        }
    }
    public function get_user_by_id($user_id) {
        return $this->get_users(array("user_id" => $user_id));
    }
    public function get_user_by_login($user_login) {
        return $this->get_users(array("user_login" => $user_login));
    }
    public function create_user($data) {
        if (!$this->get_user_by_login($data['user_login'])) {
            $insert = $this->insert($this->users_table, $data);
            return  $insert;
        } else {
            return null;
        }
    }
    public function edit_user($user_id, $data) {
        $update = $this->update($this->users_table, $data,' user_id = :user_id ', array(':user_id' => (int)$user_id));
        return $update;
    }
    public function delete_user($user_id) {
        $delete = $this->delete($this->users_table, ' user_id = :user_id ', array(':user_id' => (int)$user_id));
        return $delete;
    }
    public function clear_user_session_data($user_id) {
        $update = $this->update($this->users_table, array("user_hash" => null, "user_ip" => '0.0.0.0'),
            ' user_id = :user_id ', array(':user_id' => (int)$user_id));
        return $update;
    }
}
