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


?>
