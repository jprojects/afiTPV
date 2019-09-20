<?php
/**
 * @version     1.0.0 Afi Framework $
 * @package     Afi Framework
 * @copyright   Copyright © 2014 - All rights reserved.
 * @license	    GNU/GPL
 * @author	    kim
 * @author mail kim@afi.cat
 * @website	    http://www.afi.cat
 *
*/

defined('_Afi') or die ('restricted access');

include('../includes/model.php');

class config extends model
{
	function saveConfig()
	{
		$config = factory::getConfig();
            	$app    = factory::getApplication();
            	$lang   = factory::getLanguage();

		$offline	= $_POST['offline'];
		$sitename 	= $_POST['sitename'];
		$description 	= $_POST['description'];
		$email		= $_POST['email'];
		$debug		= $_POST['debug'];
		$driver		= $_POST['driver'];
		$dbhost		= $_POST['host'];
		$dbuser		= $_POST['user'];
		$dbpass		= $_POST['pass'];
		$dbname		= $_POST['database'];
		$dbprefix	= $_POST['dbprefix'];
		$token		= $_POST['token_time'];
		$template	= $_POST['template'];
		$atemplate	= $_POST['admin_template'];
		$cookie		= $_POST['cookie'];
		$adminmails	= $_POST['admin_mails'];
		$inactive	= $_POST['inactive'];
		$domain		= $_POST['domain'];
		$log		= $_POST['log'];

		//config file template
		$txt = "<?php\n/**\n* @version     1.0.0 Afi Framework $\n* @package     Afi Framework\n* @copyright   Copyright © 2014 - All rights reserved.\n* @license	    GNU/GPL\n* @author	    kim\n* @author mail kim@afi.cat\n* @website	    http://www.afi.cat\n*\n*/\n\ndefined('_Afi') or die('restricted access');\n\nclass Configuration {\n\n\tpublic \$site        = '';\n\tpublic \$offline     = '".$offline."';\n\tpublic \$sitename    = '".$sitename."';\n\tpublic \$description = '".$description."';\n\tpublic \$email       = '".$email."';\n\tpublic \$debug       = '".$debug."';\n\tpublic \$driver        = '".$driver."';\n\tpublic \$host        = '".$dbhost."';\n\tpublic \$user        = '".$dbuser."';\n\tpublic \$pass        = '".$dbpass."';\n\tpublic \$database    = '".$dbname."';\n\tpublic \$dbprefix    = '".$dbprefix."';\n\tpublic \$token_time  = '".$token."';\n\tpublic \$template    = '".$template."';\n\tpublic \$admin_template    = '".$atemplate."';\n\tpublic \$cookie      = '".$cookie."';\n\tpublic \$admin_mails = '".$adminmails."';\n\tpublic \$inactive = '".$inactive."';\n\tpublic \$domain = '".$domain."';\n\tpublic \$log = '".$log."';\n}";
		
		$result = file_put_contents(CWPATH_CLASSES.DS."config.php", $txt);

		
		if($result) {
			$link = 'index.php?view=config';
			$type = 'success';
			$msg  = 'La configuració ha estat guardada amb éxit.';
		} else {
			$link = 'index.php?view=config';
			$type = 'danger';
			$msg  = 'Hi ha hagut un error al intentar guardar la configuració.';
		}


		$app->setMessage($msg, $type);
                $app->redirect($link);
	}

}
