<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('getCurrentDate'))
{
	function getCurrentDate()
	{
		$currentDate = date("Y-m-d H:i:s");
    
    return $currentDate;
    
	}
}

if ( ! function_exists('getDateType'))
{
	function getDateType()
	{
		$dateType = date("A");
    
    return $dateType;
    
	}
}

if ( ! function_exists('getGenderData'))
{
	function getGenderData()
	{
		return array(
			1 => 'Nam',
			2 => 'Nu'
		);
	}
}

if ( ! function_exists('getGender'))
{
	function getGender($id)
	{
		$genderData = getGenderData();
    	return $genderData[$id];
	}
}


if ( ! function_exists('my_lang'))
{
    function my_lang($line, $args = array())
    {
        $CI =& get_instance();
        $lang = $CI->lang->line($line);
        // $lang = '%s %s were %s';// this would be the language line
        return vsprintf($lang, $args);
    }
}


?>
