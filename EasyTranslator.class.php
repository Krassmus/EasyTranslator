<?php

require_once dirname(__file__)."/lib/I18nConnector.php";

function ll($text, $language_id = null) {
    $language_id or $language_id = $GLOBALS['_language'];
    $translation = I18nConnector::get()->translate($language_id, $text);
    return $translation 
        ? $translation
        : $text;
}

function l($text, $language_id = null) {
    $language_id or $language_id = $GLOBALS['_language'];
    $translation = I18nConnector::get()->translate($language_id, $text);
    return $translation 
        ? '<span class="translation '.$language_id.'" data-original-string="'.htmlReady($text).'">'.htmlReady($translation).'</span>'
        : '<span class="nottranslated" data-original-string="'.htmlReady($text).'">'.$text.'</span>';
}

class EasyTranslator extends StudIPPlugin implements SystemPlugin {
    
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
        
        /*if (Request::get("text") && Request::get("translation") && Request::get("language_id") && count($_POST)) {
            $translation->add(Request::get("language_id"), Request::get("text"), Request::get("translation"), Request::get("origin"));
        }*/
        
        $template = $this->getTemplate("local.php", "base");
        $template->set_attribute('plugin', $this);
        $template->set_attribute('translations', $translation->getStrings(Request::get("language_id")));
        echo $template->render();
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
        if (Request::get("originaltext") && Request::get("text") && Request::get("language_id") && count($_POST)) {
            $translation->add(
                studip_utf8decode(Request::get("language_id")), 
                studip_utf8decode(Request::get("text")), 
                studip_utf8decode(Request::get("translation")), 
                studip_utf8decode(Request::get("origin"))
            );
            if (Request::get("originaltext") !== Request::get("text")) {
                $translation->delete(
                    studip_utf8decode(Request::get("language_id")), 
                    studip_utf8decode(Request::get("originaltext"))
                );
            }
        }
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