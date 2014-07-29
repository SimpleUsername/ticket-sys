<?php

namespace com\lisa\model;

use com\lisa\entity\Sector;

class SectorModel {

    private $DB;
    private $sectorTable = 'sector';

    public function __construct() {
        $this->DB = Model::getInstance();
    }
    public function getSector($ID) {
        $rs = $this->DB->prepare("SELECT sector_id, sector_name FROM ".$this->sectorTable." WHERE sector_id = ?");
        $rs->execute(array($ID));
        $result = $rs->fetch(\PDO::FETCH_KEY_PAIR);
        return new Sector($ID, $result[$ID]);
    }
    public function getAllSectors() {
        $rs = $this->DB->prepare("SELECT sector_id, sector_name FROM ".$this->sectorTable);
        $rs->execute();
        $queryResult = $rs->fetchAll(\PDO::FETCH_KEY_PAIR);
        $result = array();
        foreach ($queryResult as $sectorID=>$sectorName) {
            $result[] = new Sector($sectorID, $sectorName);
        }
        return $result;
    }
} 