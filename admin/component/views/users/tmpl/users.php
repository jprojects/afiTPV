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
//if(!$app->isAdmin()) { $app->redirect('index.php?view=cpanel'); }
$model = $app->getModel();
?>

<div class="wrap">

<?php //echo $app->getModule('sidebar'); ?>

<div id="page-wrapper">
	<div class="col-md-12">
		<?php include('template/'.$config->admin_tmpl.'/message.php'); ?>
		</div>
            <div class="row border-bottom">
                <div class="col-lg-6">
                    <h1 class="page-header"><i class="fa fa-users"></i> Users</h1>
                </div>
		<div class="col-lg-6">
			<div class="page-header pull-right">	
				<a href="#" data-toggle="modal" data-target="#cFields" class="btn btn-success"><i class="fa fa-plus"></i> Custom Fields</a>
            	<a href="index.php?view=users&layout=edit" class="btn btn-success"><i class="fa fa-plus"></i> Nou</a>
			</div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                <table id="adminList" class="table table-bordered table-hover" cellspacing="0" width="100%">
			<thead>
				<tr>
				  	<th>Nom</th>
					<th>Email</th>
					<th>Grup</th>
					<th>Id</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
				  	<th>Nom</th>
					<th>Email</th>
					<th>Grup</th>
					<th>Id</th>
				</tr>
			</tfoot>
			<tbody>
				<?php foreach($model->getUsers() as $data) : ?>
				<tr>
				  	<td><a href="index.php?view=users&layout=edit&id=<?php echo $data->id; ?>"><?php echo $data->username; ?></a></td>
					<td><?php echo $data->email; ?></td>
					<td><?php echo $data->usergroup; ?></td>
					<td><?php echo $data->id; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>                

            </div>
		
            </div>
        </div>
        <!-- /#page-wrapper -->
        
        <div class="modal fade" id="cFields" tabindex="-1" role="dialog" aria-labelledby="cFields" aria-hidden="true">
			<div class="modal-wrapper">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header bg-blue">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title"><i class="fa fa-pencil"></i> Custom Fields</h4>
						</div>
						<form action="index.php?view=users&task=saveField" method="post">
							<div class="modal-body">
								<div class="form-group">
									<input name="nom" type="text" class="form-control" placeholder="Nom camp">
								</div>
								<div class="form-group">
									<select name="tipus" class="form-control">
										<option value="">Selecciona un tipus</option>
										<option value="int">INT</option>
										<option value="tinyint">TINYINT</option>
										<option value="varchar">VARCHAR</option>
										<option value="text">TEXT</option>
										<option value="datetime">DATETIME</option>
									</select>
								</div>
								<div class="form-group">
									<input name="longitud" type="text" class="form-control" placeholder="Longitud">
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Descarta</button>
								<button type="submit" class="btn btn-success pull-right"><i class="fa fa-pencil"></i> Crear</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
</div>
