<?php

namespace application\entity;

class TicketType
{
    const PURCHASED = 'purchased';
    const RESERVED = 'reserved';

    private $_type;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        if ($type == TicketType::PURCHASED || $type == TicketType::RESERVED) {
            $this->_type = $type;
        } else {
            //TODO exception
            //throw new
        }
    }
}

class Ticket {
    private $_event;
    private $_place;
    private $_type;
    private $_reserve;
    private $_price;

    /**
     * @return Event
     */
    public function getEvent()
    {
        return $this->_event;
    }

    /**
     * @param Event $event
     */
    public function setEvent(Event $event)
    {
        $this->_event = $event;
    }

    /**
     * @return Place
     */
    public function getPlace()
    {
        return $this->_place;
    }

    /**
     * @param Place $place
     */
    public function setPlace($place)
    {
        $this->_place = $place;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->_price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->_price = $price;
    }

    /**
     * @return Reserve
     */
    public function getReserve()
    {
        return $this->_reserve;
    }

    /**
     * @param Reserve $reserve
     */
    public function setReserve(Reserve $reserve)
    {
        $this->_reserve = $reserve;
    }

    /**
     * @return TicketType
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param TicketType $type
     */
    public function setType(TicketType $type)
    {
        $this->_type = $type;
    }


} 