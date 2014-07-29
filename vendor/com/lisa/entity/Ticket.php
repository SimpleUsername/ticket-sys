<?php

namespace com\lisa\entity;


interface Ticket {
    public function getEvent();
    public function setEvent();
    public function getPlace();
    public function setPlace();
} 