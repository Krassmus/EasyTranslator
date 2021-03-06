<?php

require_once dirname(__file__)."/TranslateDB.interface.php";

class TranslateDB_sqlitepdo implements TranslateDB {
    
    protected $db = null;
    
    public function __construct() {
        $this->db = new PDO("sqlite:".$GLOBALS['STUDIP_BASE_PATH']."/data/translatedb.sqlite");
        $this->checkDB();
    }
    
    public function getLanguages() {
        return $this->db->query(
            "SELECT language_id AS id, name " .
            "FROM languages " .
            "ORDER BY name ASC " .
        "")->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getOrigins() {
        return $this->db->query(
            "SELECT DISTINCT origin " .
            "FROM translations " .
            "ORDER BY origin ASC " .
        "")->fetchAll(PDO::FETCH_COLUMN);
    }
    
    public function getStrings($language_id, $searchword = null, $origin = null) {
        return $this->db->query(
            "SELECT string, translation, origin " .
            "FROM translations " .
            "WHERE language_id = ".$this->db->quote($language_id)." " .
                ($searchword !== null ? "AND (string LIKE ".$this->db->quote('%'.$searchword.'%')." OR translation LIKE ".$this->db->quote('%'.$searchword.'%').") " : "").
                ($origin !== null ? "AND origin = ".$this->db->quote($origin)." " : "").
            "ORDER BY string ASC " .
        "")->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function translate($language_id, $string) {
        return $this->db->query(
            "SELECT translation " .
            "FROM translations " .
            "WHERE language_id = ".$this->db->quote($language_id)." " .
                "AND string = ".$this->db->quote($string)." " .
        "")->fetch(PDO::FETCH_COLUMN, 0);
    }
    
    public function add($language_id, $text, $translation, $origin) {
        return $this->db->exec(
            "INSERT OR REPLACE INTO translations (language_id, string, translation, origin) " .
            "VALUES (".
                $this->db->quote($language_id).", ".
                $this->db->quote($text).", ".
                $this->db->quote($translation).", ".
                $this->db->quote($origin)." " .
            ") " .
        "");
    }
    
    public function delete($language_id, $text) {
        return $this->db->exec(
            "DELETE FROM translations " .
            "WHERE string = ".$this->db->quote($text)." ".
                "AND language_id = ".$this->db->quote($language_id)." " .
        "");
    }
    
    public function get($language_id, $text) {
        return $this->db->query(
            "SELECT string, translation, origin " .
            "FROM translations " .
            "WHERE language_id = ".$this->db->quote($language_id)." " .
                "AND string = ".$this->db->quote($text)." " .
        "")->fetch(PDO::FETCH_ASSOC);
    }
    
    protected function checkDB() {
        $tables = $this->db->query(
            "SELECT * FROM sqlite_master WHERE type='table' " .
        "")->fetchAll(PDO::FETCH_ASSOC);
        $language_table_exists = $translation_table_exists = false;
        foreach ($tables as $table) {
            if ($table['name'] === "languages") {
                $language_table_exists = true;
            } elseif($table['name'] === "translations") {
                $translation_table_exists = true;
            }
        }
        if (!$language_table_exists) {
            $this->db->exec(
                "CREATE TABLE languages (".
                    "language_id VAR_CHAR(32) NOT NULL, " .
                    "name VAR_CHAR(64), " .
                    "PRIMARY KEY (language_id) " .
                ") " .
            "");
            $this->db->exec(
                "INSERT INTO languages (language_id, name) VALUES ('de_DE', 'Deutsch') " .
            "");
            $this->db->exec(
                "INSERT INTO languages (language_id, name) VALUES ('en_GB', 'English') " .
            "");
        }
        if (!$translation_table_exists) {
            $this->db->exec(
                "CREATE TABLE translations (".
                    "language_id VAR_CHAR(32) NOT NULL, " .
                    "string TEXT NOT NULL, " .
                    "translation TEXT NOT NULL, " .
                    "origin VAR_CHAR(64), " .
                    "PRIMARY KEY (language_id, string) " .
                ") " .
            "");
        }
    }
    
}