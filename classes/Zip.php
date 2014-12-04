<?php

class Zip
{
	public static function displayError($error_code = 0)
	{
		switch ((int)$error_code)
		{
			case ZipArchive::ER_OPEN:
				return 'ZipArchive::ER_OPEN';
				break;
			default:
				return '#'.$error_code;
		}
	}
}