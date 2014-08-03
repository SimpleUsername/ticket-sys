<?php

namespace application\entity;

class Reserve {
    private $ID;
    private $name;
    private $additional;
    private $created;

    public function __construct($ID = null)
    {
        $this->ID = $ID;
    }
    public function getID()
    {
        return $this->ID;
    }
    public function setCreated($created)
    {
        $this->created = $created;
    }
    public function getCreated()
    {
        return $this->created;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setAdditional($additional)
    {
        $this->additional = $additional;
    }
    public function getAdditional()
    {
        return $this->additional;
    }
} 