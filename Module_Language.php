<?php
namespace GDO\Language;
use GDO\Core\Module;
class Module_Language extends Module
{
    public $module_priority = 2;
    
    public function getClasses() { return ['GDO\Language\Language']; }
    public function onInstall() { LanguageData::onInstall(); }
    public function onLoadLanguage() { $this->loadLanguage('lang/language'); }

    public function getConfig()
    {
        return array(
            GDO_Language::make('languages')->all()->multiple()->initial('["'.GWF_LANGUAGE.'"]'),
        );
    }
    
    /**
     * Get the supported  languages, GWF_LANGUAGE first.
     * @return Language[]
     */
    public function cfgSupported()
    {
        $supported = [GWF_LANGUAGE => Language::table()->find(GWF_LANGUAGE)];
        if ($additional = $this->getConfigValue('languages'))
        {
            $supported = array_merge($supported, $additional);
        }
        return $supported;
    }
}
