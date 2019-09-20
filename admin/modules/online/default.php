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
include('helper.php');
$config = factory::getConfig();
?>

<div>
	<?php foreach(onlineHelper::getUsersOnline() as $usr) : ?>
	<div><a href="<?= $config->site; ?>/admin/index.php?view=users&layout=edit&id=<?= $usr->userid; ?>"><?= $usr->username; ?></a></div>
	<?php endforeach; ?>
</div>

