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


?>
