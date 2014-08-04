<?php

namespace application\entity;

class EventStatus{
    const REMOVED = -1;
    const EXPECTED = 0;
    const OCCURED = 1;
    const CANCELED = 2;
    const POSTPONED = 3;
}

class Event {

    private $_ID;
    private $_name;
    private $_description;
    private $_status;
    private $_eventStart;
    private $_saleStart;
    private $_reserveStart;
    private $_reserveEnd;
    //todo counters

    public function getID()
    {
        return $this->_ID;
    }

    public function setID($ID)
    {
        $this->_ID = $ID;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getDesctription(){
        return $this->_description;
    }
    public function setDescription($description){
        $this->_description = $description;
    }

    public function getStatus(){
        return $this->_status;
    }
    public function setStatus($status){
        $this->_status = $status;
    }
    public function getEventStart(){
        return $this->_eventStart;
    }
    public function setEventStart($eventStart){
        $this->_eventStart = $eventStart;
    }
    public function getSaleStart(){
        return $this->_saleStart;
    }
    public function setSaleStart($saleStart){
        $this->_saleStart = $saleStart;
    }
    public function getReserveStart(){
        return $this->_reserveStart;
    }
    public function setReserveStart($reserveStart){
        $this->_reserveStart = $reserveStart;
    }
    public function getReserveEnd(){
        return $this->_reserveEnd;
    }
    public function setReserveEnd($reserveEnd){
        $this->_reserveEnd = $reserveEnd;
    }
}