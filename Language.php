<?php
namespace GDO\Language;
use GDO\DB\GDO;
use GDO\Type\GDO_Char;
use GDO\Template\GDO_Template;
use GDO\DB\Cache;

class Language extends GDO
{
    public function memCached() { return false; }
    
    public function gdoColumns()
    {
        return array(
            GDO_Char::make('lang_iso')->primary()->size(2),
        );
    }
    
    public function getISO() { return $this->getVar('lang_iso'); }
    public function displayName() { return t('lang_'.$this->getISO()); }
    public function displayNameISO($iso) { return tiso($iso, 'lang_'.$this->getISO()); }
    public function renderCell()
    {
        return GDO_Template::php('Language', 'cell/language.php', ['language'=>$this]);
    }
    public function renderChoice()
    {
        return GDO_Template::php('Language', 'choice/language.php', ['language'=>$this]);
    }
    
    /**
     * Get a language by ISO or return a stub object with name "Unknown".
     * @param string $iso
     * @return self
     */
    public static function getByISOOrUnknown(string $iso=null)
    {
        if ( ($iso === null) || (!($language = self::getById($iso))) )
        {
            $language = self::blank(['lang_iso'=>'zz']);
        }
        return $language;
    }
    
    /**
     * @return self[]
     */
    public function allSupported()
    {
        return Module_Language::instance()->cfgSupported();
    }
    
    /**
     * @return self[]
     */
    public function all()
    {
        if (!($cache = Cache::get('gwf_languages')))
        {
            $cache = self::table()->select('*')->exec()->fetchAllArray2dObject();
            Cache::set('gwf_languages', $cache);
        }
        return $cache;
    }
    
    
}
