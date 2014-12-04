<?php
/*
* 2014 PrestaEdit
*
*  @author PrestaEdit <j.danse@prestaedit.com>
*  @license	http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/

ini_set('display_errors', 'on');
error_reporting(E_ALL | E_STRICT);

require_once('classes/ApplicationException.php');
require_once('classes/Tools.php');
require_once('classes/Zip.php');

try
{
	$uniqid = false;
	$zip_name = '';
	$nb_files_added = 0;

	if (Tools::isSubmit('checkZip'))
	{
		// Save document on disk
		if (isset($_FILES['zip_file']) && isset($_FILES['zip_file']['tmp_name']) && !empty($_FILES['zip_file']['tmp_name']))
		{
			if (pathinfo($_FILES['zip_file']['name'], PATHINFO_EXTENSION) != 'zip')
	 			throw new ApplicationException('Invalid extension.');

	 		$zip_name = $_FILES['zip_file']['name'];

			// Generate an uniqid
			do $uniqid = sha1(microtime());
			while (file_exists(dirname(__FILE__).'/tmp/'.$uniqid.'.zip'));

			if (!move_uploaded_file($_FILES['zip_file']['tmp_name'], dirname(__FILE__).'/tmp/'.$uniqid.'.zip'))
				return false;
		}
		else
	 		throw new ApplicationException('Failed to copy the file.');

		// Check Zip
		$zip = new ZipArchive();

		$result_code = $zip->open(dirname(__FILE__).'/tmp/'.$uniqid.'.zip');

		if ($result_code === true)
		{
			for ($i = 0; $i < $zip->numFiles; $i++)
			{
			$stats = $zip->statIndex($i);
				if (!$stats['size'] > 0)
				{
					$filename = $zip->getNameIndex($i).'index.php';
					if ($zip->locateName($filename) === false)
					{
						$zip->addFromString($filename, Tools::getDefaultIndexContent());
						$nb_files_added++;
					}
				}
			}

			// Close
			$zip->close();

			echo $nb_files_added.' index added. <a href="download.php?uniqid='.$uniqid.'&filename='.$zip_name.'" target="_blank">[download]</a> - <a href="index.php?deleteZip=1&uniqid='.$uniqid.'">[delete]</a><br /><br />------<br /><br />';
		}
	 	else
	 		throw new ApplicationException('Error. '.Zip::displayError((int)$result_code));
	}
	else if (Tools::isSubmit('deleteZip'))
	{
		$uniqid = Tools::getValue('uniqid', 0);

		if ($uniqid)
			Tools::deleteFile(dirname(__FILE__).'/tmp/'.$uniqid.'.zip');
	}
}
catch (ApplicationException $ex)
{
	if ($uniqid !== false)
		Tools::deleteFile(dirname(__FILE__).'/tmp/'.$uniqid.'.zip');
	$ex->displayMessage();
}

// Show form
include_once('form.php');