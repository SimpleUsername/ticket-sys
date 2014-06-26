<?php
/**
 * Created by PhpStorm.
 * User: Ilia
 * Date: 26.06.14
 * Time: 0:32
 */
class Model_Seller extends Model {

    private $customers_table = "customer";
    private $events_table = "events";
    private $event_status_table = "event_status";
    private $tickets_table = "tickets";
    private $sector_table = "sector";
    private $place_table = "place";

    public function get_customers_by_name($customer_name){
        return $this->db->get_records($this->customers_table, array('customer_name' => '%'.$customer_name.'%'));
    }
    public function get_customer_by_id($customer_id){
        return $this->db->get_records($this->customers_table, array('customer_id' => (int)$customer_id));
    }
    public function get_events($status_id = null) {
        if ($status_id == null){
            return $this->db->get_records($this->events_table);
        } else {
            return $this->db->get_records($this->events_table, array('event_status' => (int)$status_id));
        }
    }
    public function get_event_statuses() {
        return $this->db->get_records($this->event_status_table);
    }
    public function get_sectors() {
        return $this->db->get_records($this->sector_table);
    }
    public function get_rows($sector_id = null) {
        if ($sector_id == null) {
            return $this->db->sql('select distinct row_no from '.$this->place_table);
        } else {
            $rs = $this->db->dbh->prepare('select distinct row_no from '.$this->place_table.' where sector_id=:sector_id');
            $result = array();
            if ($rs->execute(array(':sector_id' => $sector_id))) {
                while ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
                    $result[] = $row;
                }
            };
            return $result;
        }
    }
    public function get_places($filter = null) {
        if($filter==null) {
            return $this->db->get_records($this->place_table);
        } else {
            /*
             * possible sets
             * event_id
             * event_id, row_no
             * event_id, row_no, ticket_type
             */
            $from = "$this->place_table AS p LEFT OUTER JOIN $this->tickets_table AS t ON p.place_id = t.place_id";
            if (isset($filter['event_id']) && isset($filter['row_no']) && isset($filter['ticket_type'])) {
                $where = '(event_id = :event_id OR event_id IS NULL) AND row_no = :row_no AND ticket_type = :ticket_type';
                $params = array(":event_id" => $filter['event_id'], ":row_no" => $filter['row_no'],
                    ":ticket_type" => $filter['ticket_type']);
            } elseif (isset($filter['event_id']) && isset($filter['row_no'])) {
                $where = '(event_id = :event_id OR event_id IS NULL) AND row_no = :row_no';
                $params = array(":event_id" => $filter['event_id'], ":row_no" => $filter['row_no']);
            } elseif (isset($filter['event_id'])) {
                $where = 'event_id = :event_id OR event_id IS NULL';
                $params = array(":event_id" => $filter['event_id']);
            } else {
                return null;
            }
            return $this->db->select($from, $where, $params);
        }
    }
    public function add_customer($customer_name, $customer_description) {
        $customer_data = array(
            'customer_name' => $customer_name,
            'customer_description' => $customer_description
        );
        if ($this->db->insert($this->customers_table, $customer_data)) {
            return $this->get_customers_by_name($customer_name);
        } else {
            return null;
        }
    }
    /**
     * @param int $event_id
     * @param int $place_id
     * @param string ('reserved'|'purchased') $ticket_type
     * @param int $customer_id
     * @param float $price
     * @return null|string
     */
    public function add_ticket($event_id, $place_id, $ticket_type='purchased', $customer_id = null, $price) {
        $ticket_data = array(
            'event_id' => $event_id,
            'place_id' => $place_id,
            'ticket_type' => $ticket_type,
            'customer_id' => $customer_id,
            'price' => $price
        );
        return $this->db->insert($this->tickets_table, $ticket_data);
    }
    /**
     * @param int $event_id
     * @param int $place_id
     * @param string ('reserved'|'purchased') $ticket_type
     * @return bool
     */
    public function set_ticket_type($event_id, $place_id, $ticket_type) {
        $where = 'event_id = :event_id AND place_id = :place_id';
        $fields = array('ticket_type' => $ticket_type);
        $params = array(':event_id' => $event_id, ':place_id' => $place_id);
        return $this->db->update($this->tickets_table, $fields, $where, $params);
    }
    public function delete_order($event_id, $place_id) {
        //TODO Not delete and move order to trash_orders table
        $where = 'event_id = :event_id AND place_id = :place_id';
        $params = array(':event_id' => $event_id, ':place_id' => $place_id);
        return $this->db->delete($this->tickets_table, $where, $params);
    }
}