<?php

require_once dirname(__file__)."/lib/I18nConnector.php";

function l($text, $language_id = null) {
    $language_id or $language_id = $GLOBALS['_language'];
    $translation = I18nConnector::get()->translate($language_id, $text);
    return $translation ? $translation : $text;
}

function ll($text, $language_id = null) {
    return htmlReady(l($text, $language_id));
}

class EasyTranslator extends StudIPPlugin implements SystemPlugin {
    
    public function __construct() {
        parent::__construct();
        $navigation = new AutoNavigation($this->getDisplayName(), PluginEngine::getURL($this, array(), 'languages'));
        Navigation::addItem('/tools/translations', $navigation);
    }
    
    protected function getDisplayName() {
        return l("Übersetzungen");
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
        
        if (Request::get("text") && Request::get("translation") && Request::get("language_id") && count($_POST)) {
            $translation->add(Request::get("language_id"), Request::get("text"), Request::get("translation"), Request::get("origin"));
        }
        
        
        $template = $this->getTemplate("local.php", "base");
        $template->set_attribute('plugin', $this);
        $template->set_attribute('translations', $translation->getStrings(Request::get("language_id")));
        echo $template->render();
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