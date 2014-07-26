<?php
$place_id = 10000;
for ($sector_id = 1; $sector_id <= 25; $sector_id++ ) {
    echo "INSERT INTO `place` (`place_id`, `place_no`, `row_no`, `sector_id`) VALUES".PHP_EOL;
    for ($row_no = 1; $row_no <= 20; $row_no++) {
        for ($place_no = 1; $place_no <= 50; $place_no++) {
            $place_id++;
            echo "($place_id, $place_no, $row_no, $sector_id)";
            echo ($row_no == 20 && $place_no ==50)?";":",";
            echo PHP_EOL;
        }
    }
}
for ($sector_id = 26; $sector_id <= 27; $sector_id++ ) {
    echo "INSERT INTO `place` (`place_id`, `place_no`, `row_no`, `sector_id`) VALUES".PHP_EOL;
    for ($row_no = 1; $row_no <= 10; $row_no++) {
        for ($place_no = 1; $place_no <= 20; $place_no++) {
            $place_id++;
            echo "($place_id, $place_no, $row_no, $sector_id)";
            echo ($row_no == 10 && $place_no == 20)?";":",";
            echo PHP_EOL;
        }
    }
}