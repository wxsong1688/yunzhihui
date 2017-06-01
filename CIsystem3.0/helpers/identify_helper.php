<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('identify_age'))
{
	function identify_age($identify)
	{
		$this_year = date("Y");
		$birth_year = substr($identify,6,4);
		$age = (int)($this_year-$birth_year);
		return $age;
	}
}


if(! function_exists("identify_sex"))
{
	function identify_sec($identify)
	{
		
	}
}
?>