<?php
/**
 * Created by PhpStorm.
 * User: Ilia
 * Date: 26.06.14
 * Time: 0:32
 */
class Model_Tickets extends Model {

    private $reserve_table = "reserve";
    private $events_table = "events";
    private $event_status_table = "event_status";
    private $tickets_table = "tickets";
    private $tickets_count_table = "tickets_count";
    private $sector_table = "sector";
    private $place_table = "place";

    public function get_reserve_by_id($reserve_id){
        return $this->db->get_records($this->reserve_table, array('reserve_id' => (int)$reserve_id));
    }

    public function get_events($status_id = null) {
        if ($status_id == null){
            return $this->db->get_records($this->events_table);
        } else {
            return $this->db->get_records($this->events_table, array('event_status' => (int)$status_id));
        }
    }
    public function get_events_by_name($event_name, $status_id = null) {
        if ($status_id == null){
            return $this->db->get_records($this->events_table, array('event_name' => '%'.$event_name.'%'));
        } else {
            return $this->db->get_records($this->events_table, array('event_name' => '%'.$event_name.'%',
                'event_status' => (int)$status_id));
        }
    }
    public function get_event_by_id($event_id) {
        return $this->db->get_records($this->events_table, array('event_id' => $event_id))[0];
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
    public function get_free_places_count($event_id=null, $sector_id=null) {
        if ($event_id != null && $sector_id != null) {
            //SELECT sector_id, sum(free_count) FROM tickets_count WHERE event_id = 21 GROUP BY sector_id;
            //SELECT row_no, sum(free_count) FROM tickets_count WHERE event_id = 21 AND sector_id = 10 GROUP BY row_no;
            $sql = "SELECT row_no, free_count FROM ".$this->tickets_count_table." WHERE event_id = ".(int)$event_id;
            $sql .= " AND sector_id = ".(int)$sector_id;
        } elseif ($event_id != null) {
            $sql = "SELECT sector_id, sum(free_count) free_count FROM ".$this->tickets_count_table." WHERE event_id = ".(int)$event_id;
            $sql .=" GROUP BY sector_id";
        } else {
            $sql = "SELECT event_id, sum(free_count) FROM ".$this->tickets_count_table." WHERE event_id = ".(int)$event_id;
            $sql .= $event_id != null?" AND event_id = ".(int)$event_id:"";
            $sql .=" GROUP BY event_id";
        }
        return $this->db->sql($sql);
    }
    public function get_places($filter = null) {
        /*
         * possible sets
         * event_id, sector_id, row_no
         * event_id, sector_id, row_no, ticket_type
         */
        $from = "$this->place_table AS p LEFT OUTER JOIN (select * from $this->tickets_table where event_id=:event_id) AS t ON p.place_id = t.place_id";
        $what = "p.place_id place_id, place_no, row_no, sector_id, event_id, reserve_id, ticket_type, price";
        if (isset($filter['event_id'])
            && isset($filter['sector_id'])
            && isset($filter['row_no'])
            && isset($filter['ticket_type'])) {
            $where = 'sector_id = :sector_id AND row_no = :row_no AND ticket_type = :ticket_type';
            $params = array(":event_id" => $filter['event_id'],
                ":sector_id" => $filter['sector_id'],
                ":row_no" => $filter['row_no'],
                ":ticket_type" => $filter['ticket_type']);
        } elseif (isset($filter['event_id'])
            && isset($filter['sector_id'])
            && isset($filter['row_no'])) {
            $where = 'sector_id = :sector_id AND row_no = :row_no';
            $params = array(":event_id" => $filter['event_id'],
                ":sector_id" => $filter['sector_id'],
                ":row_no" => $filter['row_no']);
        } else {
            return null;
        }
        return $this->db->select($from, $where, $params, $what);
    }
    public function get_place($place_id) {
        return $this->db->get_records($this->place_table, array('place_id' => $place_id));
    }
    public function add_reserve($customer_name, $reserve_description, $reserve_created) {
        $reserve_data = array(
            'customer_name' => $customer_name,
            'reserve_description' => $reserve_description,
            'reserve_created' => $reserve_created
        );
        return $this->db->insert($this->reserve_table, $reserve_data);
    }
    /**
     * @param int $event_id
     * @param int $place_id
     * @param string ('reserved'|'purchased') $ticket_type
     * @param int $reserve_id
     * @param float $price
     * @return null|string
     */
    public function add_ticket($event_id, $place_id, $ticket_type='purchased', $reserve_id = null, $price) {
        $ticket_data = array(
            'event_id' => $event_id,
            'place_id' => $place_id,
            'ticket_type' => $ticket_type,
            'reserve_id' => $reserve_id,
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
    public function delete_order($event_id, $place_id, $ticket_type = null) {
        //TODO Not delete and move order to trash_orders table
        $where = 'event_id = :event_id';
        $params = array(':event_id' => $event_id);
        if ($place_id != null) {
            $where.= " AND place_id = :place_id";
            $params[':place_id'] = $place_id;
        }
        if ($ticket_type != null) {
            $where.= " AND ticket_type = :ticket_type";
            $params[':ticket_type'] = $ticket_type;
        }
        return $this->db->delete($this->tickets_table, $where, $params);
    }
    public function get_tickets($event_id, $sector_id, $row_no, $place_no) {
        $from = "$this->place_table AS p
                LEFT OUTER JOIN (select * from $this->tickets_table where event_id=:event_id) AS t ON p.place_id = t.place_id
                LEFT OUTER JOIN $this->reserve_table AS c ON c.reserve_id = t.reserve_id";
        $where = "sector_id = :sector_id AND row_no = :row_no AND place_no = :place_no";
        $params = array(
            ':event_id' => $event_id,
            ':sector_id' => $sector_id,
            ':row_no' => $row_no,
            ':place_no' => $place_no
        );
        $result = $this->db->select($from, $where, $params)[0];
        return $result;
    }
    public function get_ticket_by_ids($event_id, $place_id) {
        $params = array('event_id' => (int)$event_id, 'place_id' => (int)$place_id);

        return end($this->db->get_records($this->tickets_table, $params));
    }
    public function get_reserved_tickets($reserve_id) {
        $from = "$this->tickets_table t, $this->place_table p, $this->sector_table s, $this->events_table e, $this->reserve_table c ";
        $where = "t.reserve_id = c.reserve_id AND e.event_id = t.event_id AND s.sector_id = p.sector_id ".
        "AND p.place_id = t.place_id AND ticket_type = 'reserved' AND t.reserve_id = :reserve_id ORDER BY e.event_id DESC";
        $params = array(
            ':reserve_id' => $reserve_id
        );
        $what = "e.event_id, e.event_sale, e.event_name, e.event_date, p.place_id, p.sector_id, s.sector_name, p.row_no, p.place_no, t.price";
        $result = $this->db->select($from, $where, $params, $what);
        return $result;
    }

    public function get_reserve($customer_name, $reserve_date)
    {
        $what = "r.reserve_id, customer_name, reserve_description, reserve_created, count(*) tickets_reserved";
        $from = "$this->reserve_table r, $this->tickets_table t";
        $where = "r.reserve_id = t.reserve_id AND ticket_type = 'reserved' AND "
            ."r.customer_name LIKE :customer_name1 AND r.reserve_created LIKE :reserve_created "
            ."GROUP BY reserve_id UNION ";
        $where .= "SELECT $what FROM $from WHERE r.reserve_id = t.reserve_id AND ticket_type = 'reserved' AND "
            ."r.customer_name LIKE :customer_name2 AND r.reserve_created LIKE :reserve_created "
            ."GROUP BY reserve_id";
        $params = array('customer_name1' => $customer_name.'%', 'customer_name2' => '%'.$customer_name.'%', 'reserve_created' => '%'.$reserve_date.'%');
        $result = $this->db->select($from, $where, $params, $what);
        return $result;
    }
}