<?php namespace Siakad\Classes;
	
	class Common{
		public static function isHaj($year)
		{
			return intval($year) >= intval(date('Y')) ? 'calon ' : '';			
		}
	}