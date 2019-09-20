<?php
/**
 * @version     1.0.0 Afi framework $
 * @package     Afi framework
 * @copyright   Copyright © 2016 - All rights reserved.
 * @license	    GNU/GPL
 * @author	    kim
 * @author mail kim@afi.cat
 * @website	    http://www.afi.cat
 *
*/

defined('_Afi') or die ('restricted access');

?>

<?php if($_SESSION['admin_message'] != '') : ?>
<script>
	<?php if($_SESSION['admin_messageType'] == 'success') : ?>
	toastr.success("<?= $_SESSION['admin_message']; ?>", 'Message', {timeOut: 7000});
	<?php endif; ?>
	<?php if($_SESSION['admin_messageType'] == 'danger') : ?>
	toastr.error("<?= $_SESSION['admin_message']; ?>", 'Message', {timeOut: 7000});
	<?php endif; ?>
	$.post('<?= $config->site; ?>/admin/index.php?view=cpanel&task=unsetSession&mode=raw');
</script>
<?php endif; ?>
