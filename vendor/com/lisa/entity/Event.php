<?php

namespace com\lisa\entity;


class Event {

    private $id;
    private $name;
    private $description;
    private $status;
    private $eventStart;
    private $saleStart;
    private $reserveStart;
    private $reserveEnd;
    //todo counters

    public function __construct($id = null) {
        $this->id = $id;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
        return $this;
    }

    public function __toString() {
        return $this->name;
    }
    //todo getters and setters
}