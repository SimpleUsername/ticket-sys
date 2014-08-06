<?php

namespace application\entity;

use Exception;

class TicketException extends Exception{}

class Ticket {
    private $_event;
    private $_place;
    private $_type;
    private $_reserve;
    private $_price;

    const TYPE_PURCHASED = 'purchased';
    const TYPE_RESERVED = 'reserved';

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
     * @throws TicketException
     */
    public function getPrice()
    {
        if (!isset($this->_price)) {
            throw new TicketException('Undefined property price');
        }
        return $this->_price;
    }

    /**
     * @param float $price
     * @throws TicketException
     */
    public function setPrice($price)
    {
        if (!is_float($price)) {
            throw new TicketException('Expected Argument 1 (price) to be Float');
        }
        if ($price < 0) {
            throw new TicketException('Цена не может быть отрицательной');
        }
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
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param string $type
     * @throws TicketException
     */
    public function setType($type)
    {
        if ($type != self::TYPE_PURCHASED || $type != self::TYPE_RESERVED)
        {
            throw new TicketException('Неверный тип билета');
        }
        $this->_type = $type;
    }


} 