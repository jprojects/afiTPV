
<!-- FOOTER -->
<footer class="footer">

    <div>
    
        <div class="col-md-4 pull-right visible-md visible-lg" style="text-align:right;">
        	<?php if($user->getAuth()) : ?>
            <?php $imgUrl = $config->site.'/assets/img/uploads/'.$user->image; ?>
            <a href="#"><img width="30" height="30" class="img-circle" src="<?= $imgUrl; ?>" alt="" /></a>&nbsp;
            <?php endif; ?>
        	<span class="text-muted">v.<?= $app->getVersion(); ?></span>
        </div>
        <div class="col-md-6 text-muted">&copy; 2017 <?= $config->sitename; ?> &middot; Made with <i class="fa fa-heart red"></i> from Barcelona
        </div>
        
    </div>
    
</footer>
        
