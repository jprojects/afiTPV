<?php
/**
 * @version     1.0.0 Deziro $
 * @package     Deziro
 * @copyright   Copyright © 2014 - All rights reserved.
 * @license	    GNU/GPL
 * @author	    kim
 * @author mail info@dezi.ro
 * @website	    http://www.dezi.ro
 *
*/

defined('_Afi') or die ('restricted access');
$usr = factory::getUser();
?>

<style>
.cropit-preview {
	background-color: #f8f8f8;
	background-size: cover;
	border: 1px solid #ccc;
	border-radius: 3px;
	margin-top: 7px;
	width: 250px;
	height: 250px;
}

.cropit-preview-image-container {
	cursor: move;
}

.image-size-label {
	margin-top: 10px;
}

input, .export {
	display: block;
}

button {
	margin-top: 10px;
}
.controls-wrapper {
    margin-top: 20px;
    text-align: center;
}
.controls-wrapper .slider-wrapper .cropit-image-zoom-input {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    height: 5px;
    background: #eee;
    -webkit-border-radius: 5px;
    border-radius: 5px;
    outline: none;
    width: 130px;
    margin: 0 20px;
}
.rotate-cw-btn, .rotate-ccw-btn { cursor: pointer; }
.controls-wrapper {position:relative;}
.controls-wrapper .rotation-btns, .controls-wrapper .slider-wrapper {display:inline-block}
.controls-wrapper .rotation-btns {font-size:16px;margin-right:40px;}
.controls-wrapper .rotation-btns * {vertical-align:middle}
.controls-wrapper .rotation-btns .icon:first-child {margin-right:20px}
.controls-wrapper .rotation-btns .icon {-webkit-transition:color 0.25s;-moz-transition:color 0.25s;-o-transition:color 0.25s;-ms-transition:color 0.25s;transition:color 0.25s;cursor:pointer;}
.controls-wrapper .rotation-btns .icon:hover {color:#888}
.controls-wrapper .slider-wrapper, .controls-wrapper .slider-wrapper {-webkit-transition:opacity 0.25s;-moz-transition:opacity 0.25s;-o-transition:opacity 0.25s;-ms-transition:opacity 0.25s;transition:opacity 0.25s;}
.controls-wrapper .slider-wrapper * {vertical-align:middle}
.controls-wrapper .slider-wrapper .small-image {font-size:16px}
.controls-wrapper .slider-wrapper .large-image {font-size:24px} 
.controls-wrapper .slider-wrapper .cropit-image-zoom-input {width:130px;margin:0 20px;} 
.controls-wrapper .slider-wrapper .cropit-image-zoom-input.custom {-webkit-appearance:none;-moz-appearance:none;appearance:none;height:5px;background:#eee;-webkit-border-radius:5px;border-radius:5px;outline:none;} 
.controls-wrapper .slider-wrapper .cropit-image-zoom-input.custom::-moz-range-track{-webkit-appearance:none;-moz-appearance:none;appearance:none;height:5px;background:#eee;-webkit-border-radius:5px;border-radius:5px;outline:none} 
.controls-wrapper .slider-wrapper .cropit-image-zoom-input.custom::-webkit-slider-thumb {-webkit-appearance:none;-moz-appearance:none;appearance:none;width:15px;height:15px;background:#888;-webkit-border-radius:50%;border-radius:50%;-webkit-transition:background 0.25s;-moz-transition:background 0.25s;-o-transition:background 0.25s;-ms-transition:background 0.25s;transition:background 0.25s;} 
.controls-wrapper .slider-wrapper .cropit-image-zoom-input.custom::-webkit-slider-thumb:hover, .controls-wrapper .slider-wrapper .cropit-image-zoom-input.custom::-webkit-slider-thumb:active {background:#bbb} 
.controls-wrapper .slider-wrapper .cropit-image-zoom-input.custom::-moz-range-thumb {-webkit-appearance:none;-moz-appearance:none;appearance:none;width:15px;height:15px;background:#888;-webkit-border-radius:50%;border-radius:50%;-webkit-transition:background 0.25s;-moz-transition:background 0.25s;-o-transition:background 0.25s;-ms-transition:background 0.25s;transition:background 0.25s;} 
.controls-wrapper .slider-wrapper .cropit-image-zoom-input.custom::-moz-range-thumb:hover, .controls-wrapper .slider-wrapper .cropit-image-zoom-input.custom::-moz-range-thumb:active {background:#bbb} 
</style>

<div class="row">
    
    <div class="col-xs-12 col-md-6 col-md-offset-3">

		<form name="avatar" id="avatar" action="<?= $config->site; ?>/index.php?view=config&task=upload" method="post" enctype="multipart/form-data">
    		<div id="image-cropper">
				<div class="cropit-preview"></div>
				<div class="controls-wrapper">
					<div class="rotation-btns">
						<span class="fa fa-undo rotate-ccw-btn"></span>
						<span class="fa fa-repeat rotate-cw-btn"></span>
					</div>
					<div class="slider-wrapper">
						<input type="range" class="cropit-image-zoom-input custom" min="0" max="1" step="0.01">
					</div>
				</div>
				<input type="file" class="cropit-image-input" style="display:none;" accept="image/*;capture=camera" />
				<input type="hidden" class="hidden-image-data" name="image-data" />
				<input type="hidden" name="return" value="<?= $app->getVar('return'); ?>" />
				<input type="hidden" name="logo" id="logo" value="0" />
			</div>
		</form>
    </div>
    
</div>

<script>
$(function() {

	$('#image-cropper').cropit();

	// Handle rotation
	$('.rotate-cw-btn').click(function() {
		console.log('rotateCW');
	  $('#image-cropper').cropit('rotateCW');
	});
	$('.rotate-ccw-btn').click(function() {
		console.log('rotateCCW');
	  $('#image-cropper').cropit('rotateCCW');
	});
});
</script>
