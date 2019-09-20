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

defined('_Afi') or die ('restricted access');

?>

<?php if($_SESSION['message'] != '') : ?>
<script>
	<?php if($_SESSION['messageType'] == 'success') : ?>
	toastr.success("<?= $_SESSION['message']; ?>", 'Message', {timeOut: 7000});
	<?php endif; ?>
	<?php if($_SESSION['messageType'] == 'danger') : ?>
	toastr.error("<?= $_SESSION['message']; ?>", 'Message', {timeOut: 7000});
	<?php endif; ?>
	$.post('<?= $config->site; ?>/index.php?view=home&task=unsetSession');
</script>
<?php endif; ?>
