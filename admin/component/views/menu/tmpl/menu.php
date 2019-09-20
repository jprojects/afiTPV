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
                    <h1 class="page-header"><i class="fa fa-list"></i> Menu</h1>
                </div>
		<div class="col-lg-6">
			<div class="page-header pull-right">	
            	<a href="index.php?view=menu&layout=edit" class="btn btn-success"><i class="fa fa-plus"></i> Nou</a>
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
					<th>Url</th>
					<th>Nivell</th>
					<th>Id</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
				  	<th>Nom</th>
					<th>Url</th>
					<th>Nivell</th>
					<th>Id</th>
				</tr>
			</tfoot>
			<tbody>
				<?php foreach($model->getMenuItems() as $data) : ?>
				<tr>
				  	<td><a href="index.php?view=menu&layout=edit&id=<?= $data->id; ?>"><?= $data->titol; ?></a></td>
					<td><?php echo $data->url; ?></td>
					<td><?php echo $data->level == 0 ? 'Públic' : 'Registrat'; ?></td>
					<td><?php echo $data->id; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>                

            </div>
		
            </div>
        </div>
        <!-- /#page-wrapper -->
</div>
