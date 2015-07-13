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


/**
 * notify type
 */
if ( ! function_exists('get_notify_type'))
{
    function get_notify_type($id)
    {
        $notify_type = array(
            1 => 'posts'
        );
        return $notify_type[$id];
    }
}

/**
 * acction notify
*/
if ( ! function_exists('get_notify_action'))
{
    function get_notify_action($notify_type,$id)
    {
        //posts action---
        if ($notify_type == "posts"){
            $action_post = array(
                1 => 'request_post',
                2 => 'pick_post',
                3 => 'cancel_post',
                4 => 'complete_post'
            );
            return $action_post[$id];
        }
        return array();
    }
}




?>
