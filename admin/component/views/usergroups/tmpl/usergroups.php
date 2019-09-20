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
                    <h1 class="page-header"><i class="fa fa-users"></i> Grups</h1>
                </div>
		<div class="col-lg-6">
			<div class="page-header pull-right">
				
                    		<a href="index.php?view=usergroups&layout=edit" class="btn btn-success"><i class="fa fa-plus"></i> Nou</a>
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
					<th>Id</th>
				  	<th>Nom</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>Id</th>
				  	<th>Nom</th>
				</tr>
			</tfoot>
			<tbody>
				<?php foreach($model->getUsergroups() as $data) : ?>
				<tr>
					<td><?= $data->id; ?></td>
				  	<td><a href="index.php?view=usergroups&layout=edit&id=<?= $data->id; ?>"><?= $data->usergroup; ?></a></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>                

            </div>
		
            </div>
        </div>
        <!-- /#page-wrapper -->
</div>
