<?php
class Model_Users extends Model {
    private $users_table = "users";
    private $user_types_table = "user_types";

    public function get_user_types () {
        $select = $this->db->select($this->user_types_table);
        return  $select;
    }
    public function get_users($data = null) {
        $where = $this->users_table.".user_type_id = ".$this->user_types_table.".type_id";
        $params = array();
        if (!empty($data['user_login'])) {
            $where .= " AND user_login LIKE :user_login";
            $params[':user_login'] = $data['user_login'];
        }
        if (!empty($data['user_id'])) {
            $where .= " AND user_id = :user_id";
            $params[':user_id'] = $data['user_id'];
        }
        if (!empty($data['user_type_id'])) {
            $where .= " AND user_type_id = :user_type_id";
            $params[':user_type_id'] = $data['user_type_id'];
        }

        $select = $this->db->select($this->users_table.", ".$this->user_types_table, $where, $params);

        if ($data == null) {
            return  $select;
        } else {
            if (!empty($select)) {
                return $select[0];
            } else {
                return null;
            }
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
