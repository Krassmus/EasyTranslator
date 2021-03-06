<?php

require_once dirname(__file__)."/lib/I18nConnector.php";

function ll($text, $language_id = null) {
    $language_id or $language_id = $GLOBALS['_language'];
    $translation = I18nConnector::get()->translate($language_id, $text);
    if ($translation === false) {
        if ($GLOBALS['i_page']) {
            preg_match("/\/plugins\.php\/(\w*?)[\/$]/", $_SERVER['REQUEST_URI'], $matches);
            $origin = $matches[1];
        }
        $origin or $origin = "";
        I18nConnector::get()->add($language_id, $text, "", $origin);
    }
    return $translation 
        ? $translation
        : $text;
}

function l($text, $language_id = null) {
    $translation = ll($text, $language_id);
    $translated = $text !== $translation;
    return $translated 
        ? '<span class="translation '.$language_id.'" data-original-string="'.htmlReady($text).'">'.htmlReady($translation).'</span>'
        : '<span class="nottranslated" data-original-string="'.htmlReady($text).'">'.htmlReady($translation).'</span>';
}

if (!function_exists("_")) {
    //Falls gettext als Modul deaktiviert ist.
    function _($text) {
        return ll($text);
    }
}

class EasyTranslator extends StudIPPlugin implements SystemPlugin {
    
    private $foreign_system = "https://develop.studip.de/studip";
    
    public function __construct() {
        parent::__construct();
        $navigation = new AutoNavigation($this->getDisplayName(), PluginEngine::getURL($this, array(), 'languages'));
        Navigation::addItem('/tools/translations', $navigation);
        if (true) {
            PageLayout::addHeadElement("script", array('src' => $this->getPluginURL()."/assets/easytranslator.js", 'type' => "text/javascript"), "");
            $edit_window_template = $this->getTemplate("translation_edit_window.php", null);
            PageLayout::addBodyElements($edit_window_template->render());
        }
    }
    
    protected function getDisplayName() {
        return ll("‹bersetzungen");
    }
    
    public function languages_action () {
        $translation = I18nConnector::get();
        
        $template = $this->getTemplate("languages.php", "base");
        $template->set_attribute('plugin', $this);
        $template->set_attribute('languages', $translation->getLanguages());
        echo $template->render();
    }
    
    public function local_action() {
        Navigation::activateItem('/tools/translations');
        $translation = I18nConnector::get();
        
        if ($_FILES['po_file']) {
            $this->add_po_file(
                $_FILES['po_file']['tmp_name'],
                Request::get("language_id"),
                Request::get("origin")
            );
            PageLayout::postMessage(MessageBox::success(ll("Erfolgreich hochgeladen!")));
        }
        
        $template = $this->getTemplate("local.php", "base");
        $template->set_attribute('plugin', $this);
        $strings = $translation->getStrings(Request::get("language_id"), Request::get("searchword"));
        $template->set_attribute('translations', $strings);
        $template->set_attribute('origins', $translation->getOrigins());
        echo $template->render();
    }
    
    /**
     * This method is also suitable for plugins that want to come with their
     * own translation-file. Usage for plugins:
     *   if (class_exists("EasyTranslator")) {
     *     PluginEngine::getPlugin("EasyTranslator")->add_po_file(
     *       dirname(__file__)."/locale/de.po",
     *       "de_DE",
     *       "my_plugin"
     *     );
     *   }
     * @param type $filepath
     * @param type $language_id
     * @param type $origin 
     */
    public function add_po_file($filepath, $language_id, $origin = "") {
        $translation = I18nConnector::get();
        $po = file($filepath);
        $msgid = $msgstr = null;
        for (;count($po) > 0; array_shift($po)) {
            $line = $po[0];
            if ($line[0] === "#") {
                continue;
            }
            if (preg_match("/^(msgid|msgstr)?\s*\"(.*?)\"\s*$/", $line, $matches)) {
                if ($matches[1] === "msgid") {
                    if ($msgid && $msgstr) {
                        $translation->add(
                            $language_id, 
                            stripslashes($msgid), 
                            stripslashes($msgstr), 
                            $origin
                        );
                    }
                    $msgid = $matches[2];
                    $msgstr = null;
                } elseif ($matches[1] === "msgstr") {
                    if ($msgid) {
                        $msgstr = $matches[2];
                    }
                } elseif ($msgid && !$msgstr) {
                    $msgid .= $matches[2];
                } elseif ($msgid && $msgstr) {
                    $msgstr .= $matches[2];
                }
            }
        }
        if ($msgid && $msgstr) {
            $translation->add(
                $language_id, 
                stripslashes($msgid), 
                stripslashes($msgstr), 
                $origin
            );
        }
    }
    
    public function grep_text_action() {
        var_dump($_ENV);
        //echo exec("ls");
    }
    
    public function get_text_action() {
        $translation = I18nConnector::get();
        $data = $translation->get(Request::get('language_id'), studip_utf8decode(Request::get("string")));
        $data['string'] = Request::get("string");
        $data['translation'] = studip_utf8encode($data['translation']);
        $data['origin'] = studip_utf8encode($data['origin']);
        echo json_encode($data);
    }
    
    public function save_text_action() {
        $translation = I18nConnector::get();
        $output = array();
        if (Request::get("originaltext") && Request::get("text") && Request::get("language_id") && count($_POST)) {
            $translation->add(
                studip_utf8decode(Request::get("language_id")), 
                studip_utf8decode(Request::get("text")), 
                studip_utf8decode(Request::get("translation")), 
                studip_utf8decode(Request::get("origin"))
            );
            $output['action'] = "inserted";
            if (Request::get("originaltext") !== Request::get("text")) {
                $translation->delete(
                    studip_utf8decode(Request::get("language_id")), 
                    studip_utf8decode(Request::get("originaltext"))
                );
                $output['action'] = "changed";
            }
            $output['success'] = 1;
        } else {
            $output['success'] = 0;
        }
        echo json_encode($output);
    }
    
    public function download_action() {
        $translation = I18nConnector::get();
        $output = "";
        $origin = Request::get("origin") ? Request::get("origin") : null;
        foreach ($translation->getStrings(Request::get("language_id"), null, $origin) as $string) {
            $output .= 'msgid "'.addslashes($string['string']).'"'."\n";
            $output .= 'msgstr "'.addslashes($string['translation']).'"'."\n";
            $output .= "\n";
        }
        header("Content-Type: text/x-gettext-translation");
        header("Content-Disposition: attachment; filename=translation_".Request::get("language_id").".po");
        echo $output;
    }
    
    protected function getTemplate($template_file_name, $layout = "without_infobox") {
        if (!$this->template_factory) {
            $this->template_factory = new Flexi_TemplateFactory(dirname(__file__)."/templates");
        }
        $template = $this->template_factory->open($template_file_name);
        if ($layout) {
            if (method_exists($this, "getDisplayName")) {
                PageLayout::setTitle($this->getDisplayName());
            } else {
                PageLayout::setTitle(get_class($this));
            }
            $template->set_layout($GLOBALS['template_factory']->open($layout === "without_infobox" ? 'layouts/base_without_infobox' : 'layouts/base'));
        }
        return $template;
    }
}