<?php

namespace com\lisa\entity;

class Place {

    private $ID;
    private $number;
    private $row;
    private $sector;

    public function __construct($ID, $number, $row, $sector) {
        $this->ID = $ID;
        $this->number = $number;
        $this->row = $row;
        $this->sector = $sector;
    }
    public function getID()
    {
        return $this->ID;
    }
    public function getNumber()
    {
        return $this->number;
    }
    public function getRow()
    {
        return $this->row;
    }
    public function getSector()
    {
        return $this->sector;
    }
} 