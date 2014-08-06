<?php

namespace test\fake;


class FakeRoute {
    private static $_lastSection;

    public static function getLastSection()
    {
        return self::$_lastSection;
    }

    public static function redirect($section){
        self::$_lastSection = $section;
    }
} 