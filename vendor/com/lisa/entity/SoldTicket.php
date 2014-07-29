<?php

namespace com\lisa\entity;

class SoldTicket implements Ticket{

    private $event;
    private $place;
    private $type;
    private $reserve;

    public function setEvent(Event $event) {
        $this->event = $event;
    }
    public function getEvent() {
        return $this->event;
    }
    public function setPlace(Place $place)
    {
        $this->place = $place;
    }
    public function getPlace()
    {
        return $this->place;
    }
    public function setReserve(Reserve $reserve)
    {
        $this->reserve = $reserve;
    }
    public function getReserve()
    {
        return $this->reserve;
    }
    public function setType(TicketType $type)
    {
        $this->type = $type;
    }
    public function getType()
    {
        return $this->type;
    }
} 