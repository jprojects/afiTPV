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

if(!$user->getAuth()) {
    $app->redirect($config->site);
}

$model = $app->getModel();
$total_pages = $_SESSION['total_pages'];
$page  = $app->getVar('page', 1, 'get');
$id    = $app->getVar('id', 0, 'get');
?>

<script>
$(document).ready(function() {
	$('.edit').click(function(e) {
		e.preventDefault();
		var id = $(this).attr('data-id');		
		var url = '<?= $config->site; ?>/index.php?view=iva&task=getItemById&id='+id+'&mode=raw';
		$.getJSON(url, function(json){
			console.log(json.id);
			$('#iva_id').val(json.id);
			$('#iva_percIVA').val(json.percIVA);
		});
	});
});
</script>

<div class="container-fluid">
<section class="content">
	<div class="row">
		
		<!-- BEGIN LIST -->
		<div class="col-md-9">
			<div class="grid support-content">
				 <div class="grid-body">
				 
					 <h2>Taxes</h2>
					 
					 <hr>
					 
					<div class="padding"></div>
					 
					<div class="row">
						<!-- BEGIN LIST CONTENT -->
						<div class="col-md-12">
							<ul class="list-group fa-padding">
							
								<?php 
								$taxes = $model->getList();
								if(count($taxes) > 0) :
								$i = 0;
								foreach($taxes as $tax) : 
								?>
								<li class="list-group-item">
									<div class="media">
										<div class="media-body">
											<strong><?= $tax->percIVA; ?></strong> 
											<span class="number pull-right">
												<a href="#" class="edit" data-id="<?= $tax->id; ?>"><i class="fa fa-pencil"></i></a>												
											</span>
											<p class="info"></p>
										</div>
									</div>
								</li>
								<?php 
								$i++;
								endforeach; ?>								
								<?php else : ?>
								No hi ha taxes.
								<?php endif; ?>
								
							</ul>
							
							<?php if($total_pages > 0) : ?>
							<ul class="pagination">
								<li><a href="index.php?view=iva&page=1">Primer</a></li>
								<li class="prev <?php if($page <= 1) { echo 'disabled'; } ?>">
									<a href="<?php if($page <= 1) { echo '#'; } else { echo "index.php?view=iva&page=".($page - 1); } ?>"><<</a>
								</li>
								<li class="next <?php if($page >= $total_pages){ echo 'disabled'; } ?>">
									<a href="<?php if($page >= $total_pages){ echo '#'; } else { echo "index.php?view=iva&page=".($page + 1); } ?>">>></a>
								</li>
								<li <?php if($page == $total_pages) { echo 'class="disabled"'; } ?>>
									<a href="<?php if($page == $total_pages){ echo '#'; } else { echo "index.php?view=iva&page=".$total_pages; } ?>">Últim</a>
								</li>
							</ul>
							<?php endif; ?>
														
						</div>
						<!-- END LIST CONTENT -->
					</div>
				</div>
			</div>
		</div>
		<!-- END LIST -->
		
		<!-- BEGIN FORM -->
		<div class="col-md-3">
		
			<div class="grid support">
				<div class="grid-body">
					
					<i class="fa fa-question-circle"></i> Aquí podràs gestionar els percentatges d'IVA.
				</div>
			</div>
			
			<div class="grid support">
				<div class="grid-body">
					
					<h2>IVA</h2>
					
					<form action="index.php?view=iva&task=saveItem" method="post">	
						<input type="hidden" name="id" id="iva_id" value="" />									
						<?= $html->getTextField('iva', 'percIVA', ''); ?>
						<button type="submit" class="btn btn-success btn-block"><i class="fa fa-pencil"></i> Crear</button>
					</form>
					
				</div>
			</div>
		</div>
		<!-- END FORM -->
		
	</div>
</section>
</div>
