<?php

require_once dirname(__file__)."/TranslateDB_SQLite.php";
require_once dirname(__file__)."/TranslateDB_SQLitePDO.php";

class I18nConnector {
    
    protected static $connector = null;
    
    static public function get() {
        if (self::$connector === null) {
            if (!$GLOBALS['TranslationEngine']) {
                $GLOBALS['TranslationEngine'] = "TranslateDB_sqlite";
            }
            if ($GLOBALS['TranslationEngine'] && class_exists($GLOBALS['TranslationEngine'])) {
                $engine = $GLOBALS['TranslationEngine'];
                self::$connector = new $engine();
                if (!is_a(self::$connector, "TranslateDB")) {
                    throw new Exception($GLOBALS['TranslationEngine']." is not a valid TranslateDB superclass.");
                }
            }
        }
        return self::$connector;
    }
}
