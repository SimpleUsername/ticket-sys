<?php
class Model_Users extends Model {
    public $users_table = "users";
    public $user_types_table = "user_types";

    public function get_user_types () {
        $sql = "SELECT * FROM $this->user_types_table";
        $query = $this->db->sql($sql);
        return  $query;
    }
    public function get_users($data = null) {
        $sql = "SELECT user_id , user_login, user_password, user_type_id,
                user_type, user_hash, user_ip
        FROM $this->users_table u, $this->user_types_table t
        WHERE u.user_type_id = t.type_id";

        if (!empty($data['user_login'])) {
            $sql .= " AND user_login LIKE '".$data['user_login']."'";
        }
        if (!empty($data['user_id'])) {
            $sql .= " AND user_id = ".(int)$data['user_id'];
        }
        if (!empty($data['user_type_id'])) {
            $sql .= " AND user_type_id = ".(int)$data['user_id'];
        }

        $query = $this->db->sql($sql);
        if ($data == null) {
            return  $query;
        } else {
            return $query[0];
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
}
