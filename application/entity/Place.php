<?php

namespace application\entity;

class Place {

    private $ID;
    private $number;
    private $row;
    private $sector;

    /**
     * @return int
     */
    public function getID()
    {
        return $this->ID;
    }

    /**
     * @param int $ID
     */
    public function setID($ID)
    {
        $this->ID = $ID;
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return int
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * @param int $row
     */
    public function setRow($row)
    {
        $this->row = $row;
    }

    /**
     * @return Sector
     */
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * @param Sector $sector
     */
    public function setSector($sector)
    {
        $this->sector = $sector;
    }

} 