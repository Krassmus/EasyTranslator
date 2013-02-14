<?php

require_once dirname(__file__)."/TranslateDB.interface.php";

class TranslateDB_MongoDB implements TranslateDB {
    
    protected $db = null;
    
    public function __construct() {
        if ($GLOBALS['MONGODB_ACCESS']) {
            $mongo_server = "mongodb://";
            if ($GLOBALS['MONGODB_ACCESS']['username']) {
                $mongo_server .= $GLOBALS['MONGODB_ACCESS']['username'] . "=" . $GLOBALS['MONGODB_ACCESS']['password']."@";
            }
            $mongo_server .= $GLOBALS['MONGODB_ACCESS']['host'];
            if ($GLOBALS['MONGODB_ACCESS']['port']) {
                $mongo_server .= ":" . $GLOBALS['MONGODB_ACCESS']['port'];
            }
            $m = new Mongo($mongo_server);
        } else {
            $m = new Mongo();
        }
        $db_name = str_replace(array(".", " "), array("", ""), $GLOBALS['STUDIP_INSTALLATION_ID']);
        $this->db = $m->selectDB(
            $GLOBALS['MONGODB_ACCESS']['db']
            ? $GLOBALS['MONGODB_ACCESS']['db']
            : "studip_".$db_name."_translation"
        );
        $this->checkDB();
    }
    
    public function getLanguages() {
        $languages = array();
        $result = $this->db->query(
            "SELECT language_id AS id, name " .
            "FROM languages " .
            "ORDER BY name ASC " .
        "");
        while($language = $result->fetchArray(SQLITE3_ASSOC)) {
            $languages[] = $language;
        };
        return $languages;
    }
    
    public function getOrigins() {
        $origins = array();
        $result = $this->db->query(
            "SELECT DISTINCT origin " .
            "FROM translations " .
            "ORDER BY origin ASC " .
        "");
        while ($origin = $result->fetchArray(SQLITE3_NUM)) {
            $origins[] = $origin[0];
        }
        return $origins;
    }
    
    public function getStrings($language_id, $searchword = null, $origin = null) {
        $strings = array();
        $statement = $this->db->prepare(
            "SELECT string, translation, origin " .
            "FROM translations " .
            "WHERE language_id = :language_id " .
                ($searchword !== null ? "AND (string LIKE :searchword OR translation LIKE :searchword) " : "").
                ($origin !== null ? "AND origin = :origin " : "").
            "ORDER BY string ASC " .
        "");
        $statement->bindValue(":language_id", $language_id);
        if ($searchword !== null) {
            $statement->bindValue(":searchword", '%'.$searchword.'%');
        }
        if ($origin !== null) {
            $statement->bindValue(":origin", $origin);
        }
        $result = $statement->execute();
        while ($string = $result->fetchArray(SQLITE3_ASSOC)) {
            $strings[] = $string;
        }
        return $strings;
    }
    
    public function translate($language_id, $string) {
        $statement = $this->db->prepare(
            "SELECT translation " .
            "FROM translations " .
            "WHERE language_id = :language_id " .
                "AND string = :string " .
        "");
        $statement->bindValue(":language_id", $language_id);
        $statement->bindValue(":string", $string);
        $result = $statement->execute();
        $result = $result->fetchArray(SQLITE3_NUM);
        return $result[0];
    }
    
    public function add($language_id, $text, $translation, $origin) {
        $statement = $this->db->prepare(
            "INSERT OR REPLACE INTO translations (language_id, string, translation, origin) " .
            "VALUES (".
                ":language_id, ".
                ":text, ".
                ":translation, ".
                ":origin " .
            ") " .
        "");
        $statement->bindValue(":language_id", $language_id);
        $statement->bindValue(":text", $text);
        $statement->bindValue(":translation", $translation);
        $statement->bindValue(":origin", $origin);
        return $statement->execute() !== false;
    }
    
    public function delete($language_id, $text) {
        $statement = $this->db->prepare(
            "DELETE FROM translations " .
            "WHERE string = :string ".
                "AND language_id = :language_id " .
        "");
        $statement->bindValue(":language_id", $language_id);
        $statement->bindValue(":string", $text);
        return $statement->execute() !== false;
    }
    
    public function get($language_id, $text) {
        $statement = $this->db->prepare(
            "SELECT string, translation, origin " .
            "FROM translations " .
            "WHERE language_id = :language_id " .
                "AND string = :string " .
        "");
        $statement->bindValue(":language_id", $language_id);
        $statement->bindValue(":string", $string);
        $result = $statement->execute();
        return $result->fetchArray(SQLITE3_ASSOC);
        return $result[0];
    }
    
    protected function checkDB() {
        $result = $this->db->query(
            "SELECT * FROM sqlite_master WHERE type='table' " .
        "");
        $language_table_exists = $translation_table_exists = false;
        while ($table = $result->fetchArray(SQLITE3_ASSOC)) {
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