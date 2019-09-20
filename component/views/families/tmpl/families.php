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
if($user->level != 1) {
    $app->redirect('index.php?view=ticket');
}

$model = $app->getModel();
$total_pages = $_SESSION['total_pages'];
$page  = $app->getVar('page', 1, 'get');
$id    = $app->getVar('id', 0, 'get');
$dir   = $app->getVar('list_dir', 'DESC');
?>

<script>
$(document).ready(function() {
	$('.edit').click(function(e) {
		e.preventDefault();
		var id = $(this).attr('data-id');
		var url = '<?= $config->site; ?>/index.php?view=families&task=getItemById&id='+id+'&mode=raw';
		$.getJSON(url, function(json){
			$('#familia_id').val(json.id);
			$('#familia_idSalo').val(json.idSalo);
			$('#familia_descripcio').val(json.descripcio);
			$('#familia_ordre').val(json.ordre);
			$('#familia_activa').val(json.activa);
			$('#familia_serveis').val(json.serveis);
		});

		document.getElementById("form_disabled").removeAttribute("disabled");
		document.getElementById("form_button").innerHTML="Desa";
	});

});
</script>

<div class="container-fluid">
<section class="content">
	<div class="row">

		<!-- BEGIN LIST -->
		<div class="col-md-8">
			<div class="grid support-content">
				 <div class="grid-body">

					<div class="row">
					 	<div class="col-md-6">
						 	<h2>Families</h2>
					 	</div>
					 	<div class="col-md-6 text-right">
							<?= $html->renderButtons('families', 'families'); ?>
						</div>
					</div>

					 <hr>

					<div class="row">
					 	<div class="col-md-12">
					 	<?= $html->renderFilters('families', 'families'); ?>
					 	</div>
					</div>

					<?php $get = $_GET; ?>

					<div class="padding"></div>

					<form action="" method="get" id="itemsList" name="itemsList">
            		<input type="hidden" name="view" value="families">
            		<input type="hidden" name="list_column" id="list_column">
            		<input type="hidden" name="list_dir" id="list_dir" value="<?= $dir; ?>">
						<div class="row">
							<!-- BEGIN TICKET CONTENT -->
							<div class="col-md-12">
								<table class="table table-striped">
									<thead>
										<tr>
											<th width="1%"><input type="checkbox" id="selectAll"></th>
											<th width="20%">
												<a href="#" class="text-primary order" data-field="descripcio">Producte</a>
												<i class="fa <?= $dir == 'DESC' ? 'fa-caret-up' : 'fa-caret-down'; ?> fa-lg"></i>
											</th>
											<th width="20%">
												<a href="#" class="text-primary order" data-field="serveis">Serveis</a>
												<i  class="fa <?= $dir == 'DESC' ? 'fa-caret-up' : 'fa-caret-down'; ?> fa-lg"></i>
											</th>
											<th width="20%">
												<a href="#" class="text-primary order" data-field="activa">Activa</a>
												<i  class="fa <?= $dir == 'DESC' ? 'fa-caret-up' : 'fa-caret-down'; ?> fa-lg"></i>
											</th>
											<th width="20%">
												<a href="#" class="text-primary order" data-field="idSalo">Saló</a>
												<i class="fa <?= $dir == 'DESC' ? 'fa-caret-up' : 'fa-caret-down'; ?> fa-lg"></i>
											</th>
											<th class="text-right" width="19%">Drag</th>
										</tr>
									</thead>
									<tbody id="sortable" data-view="families">
									<?php
									$i = 1;
									$families = $model->getList();
									if(count($families) > 0) :
									foreach($families as $familia) :
									?>
									<tr class="item ui-state-default" data-id="<?= $familia->id; ?>">
										<td>
											<input type="checkbox" name="cd" data-id="<?= $familia->id; ?>">
										</td>
										<td>
											<p> <a class="edit" data-id="<?= $familia->id; ?>" href="#"><?= $familia->descripcio; ?></a></p>
										</td>
										<td>
											<p><?= $familia->serveis; ?></p>
										</td>
										<td>
											<p><?= $familia->activa; ?></p>
										</td>
										<td>
											<span class="label" style="background-color:<?= $familia->color; ?>">Saló: <?= $familia->idSalo; ?> </span>
										</td>
										<td align="right">
											<a href="#" class="edit icon" data-id="<?= $familia->id; ?>"><i class="fa fa-pencil"></i></a>
											<a href="?view=families&task=deleteItem&id=<?= $familia->id; ?>" class="delete icon" data-message="Confirm?">
												<i class="fa fa-trash"></i>
											</a>
											<a href="#" class="opacity hasTip handle icon" title="Drag and drop to reorder">
                                            	<i class="fa fa-list"></i>
                                            </a>
										</td>
									</tr>
									<?php
									$i++;
									endforeach; ?>
									<?php else : ?>
									Selecciona un article de la llista.
									<?php endif; ?>
									</tbody>
								</table>

								<?= $model->pagination($get); ?>

							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- END LIST -->

		<!-- BEGIN FORM -->
		<div class="col-md-4">

			<div class="grid support">
				<div class="grid-body">

					<i class="fa fa-question-circle"></i> Aquí podràs gestionar les families.
				</div>
			</div>

			<div class="grid support">
				<div class="grid-body">

				<h2>Familia <a href="index.php?view=families"><i class="fa fa-recycle pull-right"></i></a></h2>

					<fieldset id="form_disabled" disabled="disabled">
						<form id="my_form" action="index.php?view=families&task=saveItem" method="post">
							<input type="hidden" name="id" id="familia_id" value="" />
							<?= $html->getListField('familia', 'idSalo', $user->cf_salo, $model->getSalons(), 'nom', 'id'); ?>
							<?= $html->getTextField('familia', 'descripcio'); ?>
							<?= $html->getListField('familia', 'serveis', 0); ?>
							<?= $html->getListField('familia', 'activa', 0); ?>
							<button id="form_button" type="submit" class="btn btn-success btn-block" style="margin-bottom: 8px;">Crear</button>
						</form>
						<button onClick="window.location.reload();" class="btn btn-danger btn-block">Cancel·lar</button>
					</fieldset>

				</div>
			</div>
		</div>
		<!-- END FORM -->

	</div>
</section>
</div>
