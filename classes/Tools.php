<?php
/*
* 2014 PrestaEdit
*
*  @author PrestaEdit <j.danse@prestaedit.com>
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/

class Tools
{
	public static function isSubmit($submit)
	{
		return (
			isset($_POST[$submit]) || isset($_POST[$submit.'_x']) || isset($_POST[$submit.'_y'])
			|| isset($_GET[$submit]) || isset($_GET[$submit.'_x']) || isset($_GET[$submit.'_y'])
		);
	}

	public static function getValue($key, $default_value = false)
	{
		if (!isset($key) || empty($key) || !is_string($key))
			return false;
		$ret = (isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : $default_value));

		if (is_string($ret) === true)
			$ret = urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($ret)));
		return !is_string($ret)? $ret : stripslashes($ret);
	}

	public static function ZipExtract($from_file, $to_dir)
	{
		if (!file_exists($to_dir))
			mkdir($to_dir, 0777);
		if (class_exists('ZipArchive', false))
		{
			$zip = new ZipArchive();
			if ($zip->open($from_file) === true && $zip->extractTo($to_dir) && $zip->close())
				return true;
			return false;
		}
		else
		{
			require_once(_PS_ROOT_DIR_.'/tools/pclzip/pclzip.lib.php');
			$zip = new PclZip($from_file);
			$list = $zip->extract(PCLZIP_OPT_PATH, $to_dir, PCLZIP_OPT_REPLACE_NEWER);
			foreach ($list as $file)
				if ($file['status'] != 'ok' && $file['status'] != 'already_a_directory')
					return false;
			return true;
		}
	}

	public static function deleteFile($file, $exclude_files = array())
	{
		if (isset($exclude_files) && !is_array($exclude_files))
			$exclude_files = array($exclude_files);

		if (file_exists($file) && is_file($file) && array_search(basename($file), $exclude_files) === FALSE)
		{
			@chmod($dirname.$file, 0777); // NT ?
			unlink($file);
		}
    }

	public static function getDefaultIndexContent()
	{
		return '<?php
/*
* 2007-'.date('Y').' PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-'.date('Y').' PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

header("Location: ../");
exit;
';
	}
}