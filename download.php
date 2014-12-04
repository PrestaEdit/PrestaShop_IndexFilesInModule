<?php
require_once('classes/Tools.php');

header('Content-Transfer-Encoding: binary');
header('Content-Type: application/zip');
header('Content-Length: '.filesize(dirname(__FILE__).'/tmp/'.Tools::getValue('uniqid').'.zip'));
header('Content-Disposition: attachment; filename="'.utf8_decode(Tools::getValue('filename')).'"');
@set_time_limit(0);
readfile(dirname(__FILE__).'/tmp/'.Tools::getValue('uniqid').'.zip');