<?php

interface TranslateDB {
    
    /**
     * returns an array of languages as an array itself with 'id' and 'name' as attributes
     * @return: array(array('id' => "de_DE", 'name' => "Deutsch"), ...) 
     */
    public function getLanguages();

    /**
     * Returns an array of all origins in the database.
     * @return: array of strings
     */
    public function getOrigins();
    
    public function getStrings($language_id, $searchword = null, $origin = null);
    
    public function translate($language_id, $string);
    
    public function add($language_id, $text, $translation, $origin);
    
}