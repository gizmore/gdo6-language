<?php
namespace GDO\Language;
use GDO\File\FileUtil;
/**
 * Very cheap i18n.
 * 
 * @author gizmore
 * @version 5.0
 * @since 1.0
 */
final class Trans
{
	public static $ISO = 'en';
	
	private static $PATHS = [];
	private static $CACHE;
	
	public static function numFiles()
	{
	    return count(self::$PATHS);
	}
	
	public static function addPath(string $path)
	{
		self::$PATHS[] = $path;
		self::$CACHE = [];
	}
	
	public static function getCache(string $iso)
	{
		return self::load($iso);
	}
	
	public static function load($iso)
	{
		if (!isset(self::$CACHE[$iso]))
		{
			self::reload($iso);
		}
		return self::$CACHE[$iso];
	}
	
	public static function t(string $key, array $args=null)
	{
		return self::tiso(self::$ISO, $key, $args);
	}
	
	public static function tiso(string $iso, string $key, array $args=null)
	{
		self::load($iso);
		if ($text = @self::$CACHE[$iso][$key])
		{
			if ($args)
			{
				if (!($text = @vsprintf($text, $args)))
				{
					$text = @self::$CACHE[$iso][$key] . ': ';
					$text .= print_r($args, true);
				}
			}
		}
		else # Fallback key + printargs
		{
			$text = $key;
			if ($args)
			{
				$text .= ": ";
				$text .= print_r($args, true);
			}
		}
		
		return $text;
	}

	private static function reload(string $iso)
	{
		$trans = [];
		foreach (self::$PATHS as $path)
		{
			if (FileUtil::isFile("{$path}_{$iso}.php"))
			{
				$trans2 = include("{$path}_{$iso}.php");
			}
			else
			{
				$trans2 = require("{$path}_en.php");
			}
			$trans = array_merge($trans, $trans2);
		}
		self::$CACHE[$iso] = $trans;
	}
}
