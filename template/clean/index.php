<?php
/**
 * @version     1.0.0 Afi framework $
 * @package     Afi framework
 * @copyright   Copyright © 2014 - All rights reserved.
 * @license	    GNU/GPL
 * @author	    kim
 * @author mail kim@afi.cat
 * @website	    http://www.aficat.com
 *
*/

if($config->offline == 1 && !$user->getAuth()) { $app->redirect($config->site.'/offline.php'); }

include('template/'.$config->template.'/head.php'); 
?>

<body>
    <?php echo $app->getModule('topmenu'); ?>
    <div class="wrap"><?php @include($app->getLayout()); ?></div>
    <?php @include('template/'.$config->template.'/footer.php'); ?>

  </body>
  <?php include('template/'.$config->template.'/message.php'); ?>
</html>
