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

class Html
{
    /**
     * Method to load a form
     * @param $form string the form xml name
    */
    function getForm($form)
    {
        $output = simplexml_load_file('component/forms/'.$form.'.xml'); 
        return $output;
    }
    
    /**
     * Method to load a form
     * @param $form string the form xml name
    */
    function renderFilters($form, $view)
    {
    	$app    = factory::getApplication();
        $lang   = factory::getLanguage();
        $db     = factory::getDatabase();
        $user   = factory::getUser();
        
        $fields = simplexml_load_file('component/forms/filters_'.$form.'.xml'); 
        $html   = '<form class="form-inline" action="" method="get">';
        $html  .= '<input type="hidden" name="view" value="'.$view.'">';
        
        $i = 0;
        foreach($fields as $field) { 
        
            if($field->getName() == "field"){
                if($i > 0) { $html .= '&nbsp;'; }
                
                if($field[$i]->type == 'text') {  

                    $html .= "<div id='".$field[$i]->name."-field' class='form-group'>"; 
                    $html .= "<div class='controls'>";
                    $html .= "<input type='".$field[$i]->type."' id='".$field[$i]->id."' value='".$_GET[''.$field[$i]->name.'']."' name='".$field[$i]->name."' data-message='".$lang->get($field[$i]->message)."' placeholder='".$lang->get($field[$i]->placeholder)."' class='form-control ".$field[$i]->clase."' autocomplete='off'>";
                    $html .= "</div>";
                    $html .= "</div>";	
                }
                if($field[$i]->type == 'date') { 
                    $html .= "<div id='".$field[$i]->name."-field' class='form-group'>"; 
                    $html .= "<div class='input-group date' id='".$field[$i]->id."-icon'>";
                    $html .= "<input type='text' id='".$field[$i]->id."' value='".$_GET[''.$field[$i]->name.'']."' name='".$field[$i]['name']."' data-message='".$lang->get($field[$i]->message)."' class='form-control' autocomplete='off'>";
                    $html .= "<span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span>";
                    $html .= "</div>";
                    $html .= "</div>";
                    $html .= "<script>document.addEventListener('DOMContentLoaded', function(event) { $(function(){ $('#".$field[$i]->id."-icon').datetimepicker({sideBySide: false,format: '".$field[$i]->format."'}); }); });</script>";
                }
                if($field[$i]->type == 'list') {        
                    $html .= "<div id='".$field[$i]->name."-field' class='form-group'>"; 
                    $html .= "<div class='controls'>";
                    $html .= "<select id='".$field[$i]->id."' name='".$field[$i]['name']."' class='form-control' autocomplete='off'>";
                     
                    foreach($field[$i]->option as $option) {
                        $_GET[''.$field[$i]->name.''] == $option['value'] ? $selected = "selected='selected'" : $selected = "";
                        $html .= "<option value='".$option['value']."' $selected>".$lang->get($option[0])."</option>";
                    }

                    if($field[$i]->query != '') { 
                        $db->query($field[$i]->query);
                        $options = $db->fetchObjectList();
                        $value = $field[$i]->value;
                        $key = $field[$i]->key;
                        foreach($options as $option) {
                            $_GET[''.$field[$i]->name.''] == $option->$key ? $selected = "selected='selected'" : $selected = "";
                            $html .= "<option value='".$option->$key."' $selected>".$option->$value."</option>";
                        }
                    }

                    $html .= "</select>";
                    $html .= "</div>";
                    $html .= "</div>";
                }
            
            }
        	$i++;
        }
        
        $html .= '&nbsp;<button class="btn btn-success" type="submit">'.$lang->get('CW_SEARCH').'</button>';
        $html .= '</form>';
        
        return $html;
    }
    
    function renderButtons($form, $view)
    {
    	$app    = factory::getApplication();
        $lang   = factory::getLanguage();
        
        $fields = simplexml_load_file('component/forms/filters_'.$form.'.xml'); 
        $html = "";
        $i = 0;

        foreach($fields as $field) { 
            if($field->getName() == "button"){
                $field[$i]->icon == "" ? $icon = "" : $icon = "<i class='fa ". $field[$i]->icon. "'></i>&nbsp;";
                $field[$i]->view == "" ? $view = "" : $view = "data-view='". $field[$i]->view. "'";
                $color = isset($field[$i]->color) ? $field[$i]->color : 'success';

                if($field[$i]->onclick != '') { $click = 'onclick="'.$field[$i]->onclick.'"'; } else { $click = ''; }
                $html .= '&nbsp;<a href="'. $field[$i]->href .'" id="'. $field[$i]->id .'" '.$click.' '.$view.'  class="btn btn-' . $color . ' ' . $field[$i]->class . '" >' . $icon . $field[$i]->label . '</a>';
            }
        	
        	$i++;
        }
        
        return $html;
    }
    
    /**
     * Method to render a input box
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
     * @return $html string a complete input field html
    */
    function getTextField($form, $name, $default='') 
    {
        $app    = factory::getApplication();
        $lang   = factory::getLanguage();
 
        $html = "";

        foreach($this->getForm($form) as $field) {
            //text inputs...
            if($field['name'] == $name) {
				$field[0]->readonly == 'true' ? $readonly = "readonly='true'" : $readonly = "";
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = 'onchange="'.$field[0]->onchange.'"' : $onchange = "";
                $field[0]->onkeyup != "" ? $onkeyup = " onkeyup='".$field[0]->onkeyup."'" : $onkeyup = "";
                if($field[0]->type != 'hidden') $html .= "<div id='".$field[0]->name."-field' class='form-group'>"; 
                if($field[0]->type != 'hidden' && $field[0]->label != "") $html .= "<label for='".$field[0]->id."'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label>";
                if($field[0]->type != 'hidden' && $field[0]->label != "") $html .= "<div class='controls'>";
                $html .= "<input type='".$field[0]->type."' id='".$field[0]->id."' value='".str_replace("'","&#39;",$default)."' name='".$field[0]->name."'";
                if($field[0]->type != 'hidden') $html .= $disabled." data-message='".$lang->get($field[0]->message)."' ".$onchange." ".$onkeyup." ".$readonly." placeholder='".$lang->get($field[0]->placeholder)."' class='form-control ".$field[0]->clase."' autocomplete='off'";
                $html .= ">";
                //if($field[0]->type != 'hidden') $html .= "<span id='".$field[0]->name."-msg'></span>";
                if($field[0]->type != 'hidden' && $field[0]->label != "") $html .= "</div>";
                if($field[0]->type != 'hidden') $html .= "</div>";				
            }
        }
        return $html;
    }
    
    /**
     * Method to render an email field
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
     * @return $html string a complete input field html
    */
    function getEmailField($form, $name, $default='') 
    {
        $app    = factory::getApplication();
        $lang   = factory::getLanguage();
        
        $html = "";

        foreach($this->getForm($form) as $field) {
            //text inputs...
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->required == 'true' ? $required = "required='true'" : $required = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $field[0]->onkeyup != "" ? $onkeyup = " onkeyup='".$field[0]->onkeyup."'" : $onkeyup = "";
                $html .= "<div id='".$field[0]->name."-field' class='form-group'>"; 
                if($field[0]->label != "") $html .= "<label for='".$field[0]->id."'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label>";
                if($field[0]->label != "") $html .= "<div class='controls'>";
                $html .= "<input type='".$field[0]->type."' name='".$field[0]->name."' style='display:none;' />";
                $html .= "<input type='".$field[0]->type."' id='".$field[0]->id."' value='".$default."' name='".$field[0]->name."'";
                $html .= $disabled.' '.$required.' data-error="'.$lang->get($field[0]->message).'" '.$onchange.$onkeyup.' placeholder="'.$lang->get($field[0]->placeholder).'" class="form-control '.$field[0]->clase.'"  pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" autocomplete="off"';
                if($field[0]->remote != '') { $html .= " data-remote='".$field[0]->remote."'"; }
                $html .= ">";
                $html .= "<div class='help-block with-errors'></div>";
                if($field[0]->label != "") $html .= "</div>";
                $html .= "</div>";
            }
        }
        return $html;
    }
    
    /**
     * Method to render a password field
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
     * @return $html string a complete input field html
    */
    function getPasswordField($form, $name, $default='') 
    {
        $app    = factory::getApplication();
        $lang   = factory::getLanguage();
        
        $html = "";

        foreach($this->getForm($form) as $field) {
            //text inputs...
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->required == 'true' ? $required = "required='true'" : $required = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $field[0]->onkeyup != "" ? $onkeyup = " onkeyup='".$field[0]->onkeyup."'" : $onkeyup = "";
                $html .= "<div id='".$field[0]->name."-field' class='form-group'>"; 
                if($field[0]->label != "") $html .= "<label for='".$field[0]->id."'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label>";
                if($field[0]->label != "") $html .= "<div class='controls'>";
                $html .= "<input type='".$field[0]->type."' name='".$field[0]->name."' style='display:none;' />";
                $html .= "<input type='".$field[0]->type."' data-minlength='".$field[0]->minlength."' id='".$field[0]->id."' value='".$default."' name='".$field[0]->name."'";
                if($field[0]->match != '') { $html .= "data-match='".$field[0]->match."' "; }
                $html .= $disabled.' '.$required.' data-error="'.$lang->get($field[0]->message).'" '.$onchange.$onkeyup.' placeholder="'.$lang->get($field[0]->placeholder).'" class="form-control '.$field[0]->clase.'" autocomplete="off"';
                $html .= ">";
                $html .= "<div class='help-block with-errors'></div>";
                if($field[0]->label != "") $html .= "</div>";
                $html .= "</div>";
            }
        }
        return $html;
    }

    /**
     * Method to render a input box
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
     * @return $html string a complete input field html
    */
    function getDateField($form, $name, $default='') 
    {
        $app    = factory::getApplication();
        $lang   = factory::getLanguage();
        
        $html = "";

        foreach($this->getForm($form) as $field) {
            //text inputs...
            if($field['name'] == $name) {
				$field[0]->readonly == 'true' ? $readonly = "readonly='true'" : $readonly = "";
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $html .= "<div id='".$field[0]->name."-field' class='form-group'>"; 
                if($field[0]->label != "") $html .= "<label for='".$field[0]->id."'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label>";
                $html .= "<div class='input-group date' id='".$field[0]->id."-icon'>";
                $html .= "<input type='text' id='".$field[0]->id."' value='".$default."' name='".$field[0]->name."'";
                $html .= $disabled." data-message='".$lang->get($field[0]->message)."' ".$readonly." class='form-control' autocomplete='off'>";
                $html .= "<span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span>";
                $html .= "</div>";
                $html .= "</div>";
				$html .= "<script>document.addEventListener('DOMContentLoaded', function(event) { $(function(){ $('#".$field[0]->id."-icon').datetimepicker({sideBySide: false,format: '".$field[0]->format."'}); }); });</script>";
            }
        }
        return $html;
    }
    
    /**
     * Method to render a input box with a colorpicker
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
     * @return $html string a complete input field html
    */
    function getColorField($form, $name, $default='') 
    {
        $app    = factory::getApplication();
        $lang   = factory::getLanguage();
        
        $html = "";

        foreach($this->getForm($form) as $field) {
            //text inputs...
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = " onchange='".$field[0]->onchange."'" : $onchange = "";
                $field[0]->onkeyup != "" ? $onkeyup = " onkeyup='".$field[0]->onkeyup."'" : $onkeyup = "";
                $html .= "<div id='".$field[0]->name."-field' class='form-group'>"; 
                $html .= "<label for='".$field[0]->id."'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label>";
                $html .= "<div class='controls'>";
                $html .= "<input type='".$field[0]->type."' name='".$field[0]->name."' id='".$field[0]->id."' value='".$default."'";
                $html .= $disabled.$onchange.$onkeyup.' class="form-control">';
                $html .= "</div>";
                $html .= "</div>";
            }
        }
        return $html;
    }
    
    /**
     * Method to render a media picker
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
     * @return $html string a complete input field html
    */
    function getMediaField($form, $name, $default='') 
    {
        $app    = factory::getApplication();
        $lang   = factory::getLanguage();
        
        $html = "";

        foreach($this->getForm($form) as $field) {
            //text inputs...
            if($field['name'] == $name) {

                $html .= "<div id='".$field[0]->name."-field' class='form-group'>"; 
                $html .= "<label for='".$field[0]->id."'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label>";
                $html .= "<div class='controls'>";           
                $html .= "<input type='".$field[0]->type."' name='".$field[0]->name."' id='".$field[0]->id."' value='".$default."'";
                $html .= ' class="form-control basic">';
                $html .= '<div class="modal mediani-modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">';
				$html .= '<div class="modal-dialog modal-lg">';
				$html .= '<div class="modal-content mediani-modal-content clearfix"></div></div></div>';
				$html .= '<p></p>';
				$html .= '<div class="basic-result"></div>';
                $html .= "</div>";
                $html .= "</div>";
            }
        }
        return $html;
    }

    /**
     * Method to render a input box
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
     * @return $html string a complete input field html
    */
    function getTextareaField($form, $name, $default='') 
    {
        $app    = factory::getApplication();
        $lang   = factory::getLanguage();
        
        $html = "";

        foreach($this->getForm($form) as $field) {
            //text inputs...
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";

                $html .= "<div id='".$field[0]->name."-field' class='form-group'>"; 
                if($field[0]->label != "") $html .= "<label for='".$field[0]->id."'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label>";
                if($field[0]->label != "") $html .= "<div class='controls'>";
                $html .= "<textarea id='".$field[0]->id."' maxlength='".$field[0]->maxlength."' placeholder='".$field[0]->placeholder."' name='".$field[0]->name."' rows='".$field[0]->rows."' cols='".$field[0]->cols."' class='form-control' ".$disabled." ".$onchange.">".$default."</textarea>"; 
                //$html .= "<span id='".$field[0]->name."-msg'></span>";
                if($field[0]->label != "") $html .= "</div>";
                $html .= "</div>";
            }
        }
	
        return $html;
    }
    
    /**
     * Method to render a input box
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
     * @return $html string a complete input field html
    */
    function getEditorField($form, $name, $default='') 
    {
        $app    = factory::getApplication();
        $lang   = factory::getLanguage();
        $app    = factory::getApplication();
        
        //add to the view
        //$app->addScript('plugins/editor/editor.js');
        
        $html = "";

        foreach($this->getForm($form) as $field) {
            //text inputs...
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";

                $html .= "<div id='".$field[0]->name."-field' class='form-group'>"; 
                if($field[0]->label != "") $html .= "<label for='".$field[0]->id."'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label>";
                if($field[0]->label != "") $html .= "<div class='controls'>";
                $html .= "<textarea id='".$field[0]->id."' maxlength='".$field[0]->maxlength."' name='".$field[0]->name."' rows='".$field[0]->rows."' cols='".$field[0]->cols."' class='form-control editor' style='overflow:scroll; max-height:300px'>".$default."</textarea>"; 
                //$html .= "<span id='".$field[0]->name."-msg'></span>";
                if($field[0]->label != "") $html .= "</div>";
                $html .= "</div>";
            }
        }
	
        return $html;
    }
    
    
    /**
     * Method to render a form button
     * @param $form string the form name
     * @param $name string the field name
     * @return $html string a complete html button
    */
    function getButton($form, $name) 
    {
        $lang   = factory::getLanguage();
        
        $html = "";

        foreach($this->getForm($form) as $field) {
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onclick != "" ? $onclick = "onclick='".$field[0]->onclick."'" : $onclick = "";
                $field[0]->type == 'submit' ? $type = "type='".$field[0]->type."'" : $type = "";
                $html .= "<button $type id='".$field[0]->id."' ".$disabled." ".$onclick." class='btn btn-".$field[0]->color." ".$field[0]->clase."'>".$lang->get($field[0]->value)."</button>";
            }
        }
        return $html;
    }

    /**
     * Method to render a repeatable field require jquery ui
     * @param $form string the form name
     * @param $fields array of field names
	 * @param $tmpl array of default values
	 * @param $list object to fill the list field
	 * @param $value string the value field for list fields
	 * @param $key string the key field for list fields
	 * @param $target string the modal url formmodal fields
	 * @param $placeholder string a placeholder for modal fields
	 * @param $uniqid string a uniqid for modal fields
	 * @see https://github.com/Rhyzz/repeatable-fields
     * @return $html string a complete repeatable field
    */
    function getRepeatable($form, $fields, $tmpl=null, $list, $value, $key, $target, $placeholder, $uniqid) 
    {
        $lang   = factory::getLanguage();

        $html = "<div class='repeatable'>";
		$html .= "<table class='wrapper' width='100%'>";
		$html .= "<thead><tr><td width='10%' valign='bottom' colspan='4'><span class='add btn btn-success'><i class='fa fa-plus'></i></span></td></tr></thead>";
		$html .= "<tbody class='container'>";	

		$html .= "<tr class='template row'>";
		$html .= "<td width='10%'><div class='form-group'></div></td>";

		foreach($fields as $field) {
			foreach($this->getForm($form) as $row) {
				if($row['name'] == $field) {
				$row[0]->width == '' ? $width = '40%' : $width = $row[0]->width;
				if($row[0]->type == 'text') { $html .= "<td width='".$width."'>".$this->getTextField($form, $field)."</td>"; }	
				if($row[0]->type == 'textarea') { $html .= "<td width='".$width."'>".$this->getTextareaField($form, $field)."</td>"; }	
				if($row[0]->type == 'list') { $html .= "<td width='".$width."'>".$this->getListField($form, $field, "", $list, $value, $key)."</td>"; }	
				if($row[0]->type == 'checkbox') { $html .= "<td width='".$width."'>".$this->getCheckboxField($form, $field)."</td>"; }	
				if($row[0]->type == 'radio') { $html .= "<td width='".$width."'>".$this->getRadioField($form, $field)."</td>"; }	
				if($row[0]->type == 'modal') { $html .= "<td width='".$width."'>".$this->getModalField($form, $field, '', $target, $placeholder, $uniqid)."</td>"; }	
				}
			}
		}


		$html .= '<td valign="bottom" width="10%" align="right"><div class="form-group"><span class="remove btn btn-danger"><i class="fa fa-minus"></i></span></div></td></tr>';	

		if($tmpl != null) {
			foreach($tmpl as $item) {
				$html .= "<tr class='row fromdb'>";
				$html .= "<td width='10%'><div class='form-group'></div></td>";
				foreach($fields as $field) {
					foreach($this->getForm($form) as $row) {
						if($row['name'] == $field) {
						$row[0]->width == '' ? $width = '40%' : $width = $row[0]->width;
						if($row[0]->type == 'text') { $html .= "<td width='".$width."'>".$this->getTextField($form, $field, $item->$field)."</td>"; }	
						if($row[0]->type == 'textarea') { $html .= "<td width='".$width."'>".$this->getTextareaField($form, $field, $item->$field)."</td>"; }	
						if($row[0]->type == 'list') { $html .= "<td width='".$width."'>".$this->getListField($form, $field, $item->$field, $list, $value, $key)."</td>"; }	
						if($row[0]->type == 'checkbox') { $html .= "<td width='".$width."'>".$this->getCheckboxField($form, $field, $item->$field)."</td>"; }	
						if($row[0]->type == 'radio') { $html .= "<td width='".$width."'>".$this->getRadioField($form, $field, $item->$field)."</td>"; }
						if($row[0]->type == 'modal') { $html .= "<td width='".$width."'>".$this->getModalField($form, $field, '', $target, $placeholder, $uniqid)."</td>"; }
						}
					}
				}
				$html .= '<td width="10%" valign="bottom" align="right"><div class="form-group"><span class="remove btn btn-danger"><i class="fa fa-minus"></i></span></div></td></tr>';
			}
			
		} else {
			$html .= "<tr class='row'>";
			$html .= "<td width='10%'><div class='form-group'></div></td>";
			foreach($fields as $field) {
				foreach($this->getForm($form) as $row) {
					if($row['name'] == $field) {
					$row[0]->width == '' ? $width = '40%' : $width = $row[0]->width;
					if($row[0]->type == 'text') { $html .= "<td width='".$width."'>".$this->getTextField($form, $field)."</td>"; }	
					if($row[0]->type == 'textarea') { $html .= "<td width='".$width."'>".$this->getTextareaField($form, $field)."</td>"; }	
					if($row[0]->type == 'list') { $html .= "<td width='".$width."'>".$this->getListField($form, $field, "", $list, $value, $key)."</td>"; }	
					if($row[0]->type == 'checkbox') { $html .= "<td width='".$width."'>".$this->getCheckboxField($form, $field)."</td>"; }	
					if($row[0]->type == 'radio') { $html .= "<td width='".$width."'>".$this->getRadioField($form, $field)."</td>"; }	
					if($row[0]->type == 'modal') { $html .= "<td width='".$width."'>".$this->getModalField($form, $field, '', $target, $placeholder, $uniqid)."</td>"; }
					}
				}
			}

			$html .= '<td width="10%" valign="bottom" align="right"><div class="form-group"><span class="remove btn btn-danger"><i class="fa fa-minus"></i></span></div></td>';
		}
		$html .= "<script>";
		$html .= '$(document).ready(function () {';
		$html .= '$(".repeatable").each(function() {';
		$html .= '$(this).repeatable_fields();';
		$html .= '});';
		$html .= '});';
		$html .= "</script>";
		$html .= '</tr></tbody></table>';
		$html .= '</div>';
        return $html;
    }
    
    /**
     * Method to render a usergroups select box
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
    */
    function getUsergroupsField($form, $name, $default='') 
    {
        $lang   = factory::getLanguage();
        $db     = factory::getDatabase();
        
        $html = "";

        foreach($this->getForm($form) as $field) {
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $html .= "<div id='".$field[0]->name."-field' class='form-group'>";  
                if($field[0]->label != "") $html .= "<label class='control-label' for='".$field[0]->id."'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label>";
                $html .= "<select id='".$field[0]->id."' name='".$field[0]->name."' data-message='".$lang->get($field[0]->message)."' ".$onchange." class='".$class." form-control' ".$disabled.">";
                
                $db->query('SELECT * FROM #_usergroups');
                $rows = $db->fetchObjectList();
                
                $html .= "<option value=''>".$lang->get('CW_SELECT_USERGROUP')."</option>";
		
				foreach($rows as $row) {
					  $default == $row->id ? $selected = "selected='selected'" : $selected = "";
					  $html .= "<option value='".$row->id."' $selected>".$row->usergroup."</option>";
				}

                $html .= "</select>";
                //$html .= "<span id='".$field[0]->name."-msg'></span>";
                $html .= "</div>";
            }
        }
        return $html;
    }
    
    /**
     * Method to render a users select box
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
    */
    function getUsersField($form, $name, $default='') 
    {
        $lang   = factory::getLanguage();
        $db     = factory::getDatabase();
        $user   = factory::getUser();
        
        $html = "";

        foreach($this->getForm($form) as $field) {
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $html .= "<div id='".$field[0]->name."-field' class='form-group'>";  
                if($field[0]->label != "") $html .= "<label class='control-label' for='".$field[0]->id."'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label>";
                $html .= "<select id='".$field[0]->id."' name='".$field[0]->name."' data-message='".$lang->get($field[0]->message)."' ".$onchange." class='".$class." form-control' ".$disabled.">";
                
                $db->query('SELECT id, username FROM #_users');
                $rows = $db->fetchObjectList();
                
                $html .= "<option value=''>".$lang->get('CW_SELECT_USER')."</option>";
		
				foreach($rows as $row) {
					  $default == '' ? $default = $user->id : $default = $default;
					  $default == $row->id ? $selected = "selected='selected'" : $selected = "";
					  $html .= "<option value='".$row->id."' $selected>".$row->username."</option>";
				}

                $html .= "</select>";
                //$html .= "<span id='".$field[0]->name."-msg'></span>";
                $html .= "</div>";
            }
        }
        return $html;
    }
    
    /**
     * Method to render a select box
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
     * @param $options array optional array of options
     * @return $html string a complete select field html
    */
    function getListField($form, $name, $default='', $options=null, $key='', $value='', $combobox=false) 
    {
        $lang   = factory::getLanguage();
        
        $html = "";

        foreach($this->getForm($form) as $field) {
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
				$combobox == true ? $class = 'combobox' : $class = '';				
                $html .= "<div id='".$field[0]->name."-field' class='form-group'>";  
                if($field[0]->label != "") $html .= "<label class='control-label' for='".$field[0]->id."'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label>";
                $html .= "<select id='".$field[0]->id."' name='".$field[0]->name."' data-message='".$lang->get($field[0]->message)."' ".$onchange." class='".$class." ".$field[0]->classe." form-control' ".$disabled.">";
		
				foreach($field[0]->option as $option) {
					  $default == $option['value'] ? $selected = "selected='selected'" : $selected = "";
					  $option['onclick'] != '' ? $click = "onclick='".$option['onclick']."'" : $click = "";
					  $html .= "<option value='".$option['value']."' $click $selected>".$lang->get($option[0])."</option>";
				}
		
				if($options != null) {
					
					foreach($options as $option) {
						if($key == '' && $value == '') {
							$default == $option->$name ? $selected = "selected='selected'" : $selected = "";
							$html .= "<option value='".$option->$name."' $selected>".$option->$name."</option>";
						} else {
							$default == $option->$value ? $selected = "selected='selected'" : $selected = "";
							$html .= "<option value='".$option->$value."' $selected>".$option->$key."</option>";
						}
					}
				}
                $html .= "</select>";
                //$html .= "<span id='".$field[0]->name."-msg'></span>";
                $html .= "</div>";
            }
        }
        return $html;
    }
    
	/**
     * Method to render a modal field
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
     * @return $html string a complete checkbox field html
    */
    function getModalField($form, $name, $default='', $target='', $placeholder='', $uniqid='') 
    {
        $lang   = factory::getLanguage();
        $uniqid == '' ? $uniqid = uniqid() : $uniqid = $uniqid;
        $html = "";
        foreach($this->getForm($form) as $field) {
            if($field['name'] == $name) {
				$target == '' ? $target = $field[0]->target : $target = $target;
				$placeholder == '' ? $placeholder = $field[0]->placeholder : $placeholder = $placeholder;
                $field[0]->onclick != "" ? $onclick = "onclick='".$field[0]->onclick."'" : $onclick = "";
                $html .= "<div id='".$field[0]->name."-field' class='form-group'>"; 
                $html .= "<div class='input-group'>";
				$html .= "<input type='text' class='form-control SearchBar' name='".$field[0]->name."' value='".$default."' id='input-".$uniqid."' placeholder='".$placeholder."'>";
				$html .= "<span class='input-group-btn'>";
				$html .= "<button class='btn btn-defaul SearchButton' id='searchBtn-".$uniqid."' type='button'>";
				$html .= "<span class='glyphicon glyphicon-search SearchIcon' ></span>";
				$html .= "</button>";
				$html .= "</span>";
				$html .= "</div>";
				$html .= "<!-- modal  -->";
				$html .= "<div class='modal fade' id='myModal-".$uniqid."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel-".$uniqid."' aria-hidden='true'>";
				$html .= "<div class='modal-dialog'>";
				$html .= "<div class='modal-content'>";
				$html .= "<div class='modal-header'>";
				$html .= "<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
				$html .= "<h4 class='modal-title' id='myModalLabel-".$uniqid."'>".$placeholder."</h4>";
				$html .= "</div>";
				$html .= "<div class='modal-body' id='modal-body-".$uniqid."'>";
				$html .= "</div>";
				$html .= "<div class='modal-footer'>";
				$html .= "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>";
				$html .= "</div>";
				$html .= "</div>";
				$html .= "</div>";
				$html .= "</div>";
				$html .= "<script>";
				$html .= "$(document).ready(function () {";
				$html .= "$('#searchBtn-".$uniqid."').on('click', function() {";
				$html .= "$('#myModal-".$uniqid."').modal('show'); ";
				$html .= "$('#modal-body-".$uniqid."').load('".$target."&btn=".$uniqid."');";
				$html .= "});";
				$html .= "});";
				$html .= "</script>";
                $html .= "</div>";
            }
        }
        return $html;
    } 

 	/**
     * Method to render a checkbox
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
     * @return $html string a complete checkbox field html
    */
    function getCheckboxField($form, $name, $default='') 
    {
        $lang   = factory::getLanguage();
        
        $html = "";

        foreach($this->getForm($form) as $field) {
            if($field['name'] == $name) {
                $field[0]->onclick != "" ? $onclick = "onclick='".$field[0]->onclick."'" : $onclick = "";
                $html .= "<div class='form-group'>";
                $html .= "<div id='".$field[0]->name."-field' class='checkbox'>"; 
                $html .= "<label class='checkbox'>";
                foreach($field[0]->option as $option) {
                    $default == $option['value'] ? $checked = "checked='checked';" : $checked = "";
                    $html .= "<input type='checkbox' class='checkbox' name='".$field[0]->name."' id='".$field[0]->id."' value='".$option['value']."' ".$onclick."  data-message='".$lang->get($field[0]->message)."'> ".$lang->get($option[0]);
                }
                $html .= "</label>";
                //$html .= "<span id='".$field[0]->name."-msg'></span>";
                $html .= "</div>";
                $html .= "</div>";
            }
        }
        return $html;
    }
    
    /**
     * Method to render a radio
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
     * @return $html string a complete radio field html
    */
    function getRadioField($form, $name, $default='')
    {
        $lang   = factory::getLanguage();
        
        $html = "";

        foreach($this->getForm($form) as $field) {
            if($field['name'] == $name) {
                $field[0]->onclick != "" ? $onclick = "onclick='".$field[0]->onclick."'" : $onclick = ""; 
                $html .= "<div id='".$field[0]->name."-field' class='form-group'>";
				if($field[0]->label != "") $html .= "<label class='btn-group-label'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label> ";

        	//$html .= "<div class='col-sm-9'>";
                $html .= " <div class='btn-group ".$name."' data-toggle='buttons'>";
		
                foreach($field[0]->option as $option) {
                    $default == $option['value'] ? $checked = "checked='checked'" : $checked = "";
					$default == $option['value'] ? $class = "active" : $class = "";
					$html .= "<label class='btn btn-default ".$class."'>";
					$html .= "<input type='radio' name='".$field[0]->name."' id='".$field[0]->id."' ".$checked." value='".$option['value']."' ".$onclick."  data-message='".$lang->get($field[0]->message)."'> ".$lang->get($option[0]);
					$html .= "</label>";
                }

                //$html .= "</div>";
				$html .= "</div>";
				$html .= "</div>";
            }
        }
        return $html;
    }
    
    /**
     * Method to render a tags field
     * @param $form string the form name
     * @param $name string the field name
     * @param $default mixed optional default value
     * @return $html string a complete tgs field html
    */
    function getTagsField($form, $name, $default='')
    {
    	//needs in a view.php a css and a js file
    	
    	$lang   = factory::getLanguage();

        $html = "";
        
        foreach($this->getForm($form) as $field) {
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $html .= "<div id='".$field[0]->name."-field' class='form-group'>";  
                if($field[0]->label != "") $html .= "<label class='control-label' for='".$field[0]->id."'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label>";
                $html .= "<select id='".$field[0]->id."' name='".$field[0]->name."' data-message='".$lang->get($field[0]->message)."' ".$onchange." class='form-control' ".$disabled.">";
		
				for($i=0; $i<count( $ficheros ); $i++) {
					  $default == $option[$i] ? $selected = "selected='selected'" : $selected = "";
					  $html .= "<option value='".$option[$i]."' $selected>".$option[$i]."</option>";
				}
		
                $html .= "</select>";
                $html .= "<script>$('#".$field[0]->id."').tagsinput();</script>";
                $html .= "</div>";
            }
        }
        return $html;
    }
    
    /**
     * Method to render a filelist field
     * @param $form string the form name
     * @param $name string the field name
     * @param $name string the folder path
     * @param $default mixed optional default value
     * @return $html string a complete filelist field html
    */
    function getFiles($form, $name, $folder, $default='')
    {
    	$lang   = factory::getLanguage();
        
        $html = "";
        
		$dir = opendir($folder);
		while (false !== ($file = readdir($dir))) {
			if( $file != "." && $file != "..") {
				$ficheros[] = $file;
			}
		}
		closedir($dir);

        foreach($this->getForm($form) as $field) {
            if($field['name'] == $name) {
                $field[0]->disabled == 'true' ? $disabled = "disabled='disabled'" : $disabled = "";
                $field[0]->onchange != "" ? $onchange = "onchange='".$field[0]->onchange."'" : $onchange = "";
                $html .= "<div id='".$field[0]->name."-field' class='form-group'>";  
                if($field[0]->label != "") $html .= "<label class='control-label' for='".$field[0]->id."'><a class='hasTip' title='".$lang->get($field[0]->placeholder)."'>".$lang->get($field[0]->label)."</a></label>";
                $html .= "<select multiple data-role='tagsinput' id='".$field[0]->id."' name='".$field[0]->name."' data-message='".$lang->get($field[0]->message)."' ".$onchange." ".$disabled.">";
		
				for($i=0; $i<count( $ficheros ); $i++) {
					  $default == $option[$i] ? $selected = "selected='selected'" : $selected = "";
					  $html .= "<option value='".$option[$i]."' $selected>".$option[$i]."</option>";
				}
		
                $html .= "</select>";
                //$html .= "<span id='".$field[0]->name."-msg'></span>";
                $html .= "</div>";
            }
        }
        return $html;
    }

    function getEditable($form, $name, $url, $pk, $value)
    {
		$lang   = factory::getLanguage();
        $html = "";
		foreach($this->getForm($form) as $field) {
			if($field['name'] == $name) {
				$html = '<a href="#" id="'.$field[0]->id.'" data-type="'.$field[0]->type.'" data-pk="'.$pk.'" data-url="'.$url.'" data-title="'.$lang->get($field[0]->label).'">'.$lang->get($field[0]->label).'</a>';
			}
		}
		return $html;
    }
}
