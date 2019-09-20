<?php
/**
 * @version     1.0.0 Afi Framework $
 * @package     Afi Framework
 * @copyright   Copyright © 2014 - All rights reserved.
 * @license	    GNU/GPL
 * @author	    kim
 * @author mail kim@aficat.com
 * @website	    http://www.aficat.com
 *
*/

defined('_Afi') or die ('restricted access');

class model
{ 	
	public static function getUsers()
	{
		$db = factory::getDatabase();
		$db->query('select * from #_users');
		return $db->fetchObjectList();
	}
	
	public static function getSalons()
    {
        $db = factory::getDatabase();
        
        $db->query('SELECT * FROM #_salons');
        
        return $db->fetchObjectList();
	}
	
	public static function getFamilies()
    {
        $db = factory::getDatabase();
        
        $db->query('SELECT * FROM #_families');
        
        return $db->fetchObjectList();
	}
	
	public static function getTerminals()
    {
        $db = factory::getDatabase();
        
        $db->query('SELECT * FROM #_terminals');
        
        return $db->fetchObjectList();
	}
	
	public static function getPercIVAs()
    {
        $db = factory::getDatabase();
        
        $db->query('SELECT * FROM #_tipus_iva');
        
        return $db->fetchObjectList();
	}
	
	function configParam($field)
    {
    	$db = factory::getDatabase();
        
        $db->query("SELECT $field FROM #_configuration");
        
        return $db->loadResult();
    }
    
    function isAdmin() {
    	
    	$user = factory::getUser();
    	
    	if($user->level == 1) { return true; }
    	
    	return false;
    }
	
	public function reorderUp()
	{
		$db  = factory::getDatabase();
		$app = factory::getApplication();
		
		$view  = $app->getVar('view', '', 'get');
		$id    = $app->getVar('id', 0, 'get');
		$table = $app->getVar('table', 0, 'get');
		
		$db->query('SELECT ordre FROM #_'.$table.' WHERE id = '.$id);
		$ordre = $db->loadResult();
		
		$db->query('SELECT id FROM #_'.$table.' WHERE ordre = '.($ordre-1));
		$lastid = $db->loadResult();
		
		$db->query('UPDATE #_'.$table.' SET ordre = ordre - 1 WHERE id = '.$id);
		$db->query('UPDATE #_'.$table.' SET ordre = ordre + 1 WHERE id = '.$lastid);
		
		$app->redirect('index.php?view='.$view);
	}
	
	public function reorderDown()
	{
		$db  = factory::getDatabase();
		$app = factory::getApplication();
		
		$view  = $app->getVar('view', '', 'get');
		$id    = $app->getVar('id', 0, 'get');
		$table = $app->getVar('table', 0, 'get');
		
		$db->query('SELECT ordre FROM #_'.$table.' WHERE id = '.$id);
		$ordre = $db->loadResult();
		
		$db->query('SELECT id FROM #_'.$table.' WHERE ordre = '.($ordre+1));
		$lastid = $db->loadResult();
		
		$db->query('UPDATE #_'.$table.' SET ordre = ordre + 1 WHERE id = '.$id);
		$db->query('UPDATE #_'.$table.' SET ordre = ordre - 1 WHERE id = '.$lastid);
		
		$app->redirect('index.php?view='.$view);
	}
    
    /**
     * Method to get the username
     * @param $username string
    */
    public static function getUsername($userid)
    {
    	$db     = factory::getDatabase();
    	
    	$db->query('select username from #_users WHERE id = '.$userid);
        return $db->loadResult();
    }
    
    /**
     * Method to secure the wishlist
    */
    public static function tokenCheck()
    {
        $db     = factory::getDatabase();
        
        //exit if its the token owner...
        $db->query('select token from #_users WHERE username = '.$_GET['username']);
        $token = $db->loadResult();
        if($token != $_GET['token']) {
            return false;
        }

        return true;
    }
    
    function timeElapsed($datetime, $full = false) 
	{
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);

		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;

		$string = array(
		    'y' => 'any',
		    'm' => 'mes',
		    'w' => 'setmana',
		    'd' => 'dia',
		    'h' => 'hora',
		    'i' => 'minut',
		    's' => 'segon',
		);
		foreach ($string as $k => &$v) {
		    if ($diff->$k) {
		        $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
		    } else {
		        unset($string[$k]);
		    }
		}

		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? 'fa '.implode(', ', $string) : 'just ara';
	}
    
    /**
     * Send email to the user
     * @param $mail string the user email
     * @param $name string the username
     * @param $subject string the mail subject
     * @param $body string the mail body
     * @return boolean true if success false if not
    */
    public static function sendMail($email, $name, $subject, $body)
    {
        $mail   = factory::getMailer();
        $config = factory::getConfig();
        
        @ob_start();
		include 'assets/mail/mail.html';
		$html = @ob_get_clean();
		$htmlbody = str_replace('{{LOGO}}', $config->site.'/assets/img/mail_logo.png', $html);
		$htmlbody = str_replace('{{BODY}}', $body, $htmlbody);
        
        $mail->setFrom($config->email, $config->sitename);
        $mail->addRecipient($name, $email);
        $mail->setReplyTo($config->email);
        $mail->Subject($subject);
        $mail->Body($htmlbody);
        if($mail->send()) {
            return true;
        }
        return false;
    }
    
    /**
     * Send email to the admin
     * @param $subject string the mail subject
     * @param $body string the mail body
     * @return boolean true if success false if not
    */
    public static function sendAdminMail($subject, $body)
    {
        $mail   = factory::getMailer();
        $config = factory::getConfig();
        

		@ob_start();
		include 'assets/mail/mail.html';
		$html = @ob_get_clean();
		$htmlbody = str_replace('{{LOGO}}', $config->site.'/assets/img/mail_logo.png', $html);
		$htmlbody = str_replace('{{BODY}}', $body, $htmlbody);
        
        $mail->setFrom($config->email, $config->sitename);
        $mail->addRecipient($config->sitename, $config->email);
        $mail->setReplyTo($config->email);
        $mail->Subject($subject);
        $mail->Body($htmlbody);
        if($mail->send()) {
            return true;
        }
        return false;
    }
    
    /**
     * Method to cut a long text
     * @param string $string the input text
     * @param int $number the number of words in output
    */
    public function textShorterer($string, $number)
    {
        $string = str_replace('<p>', '', $string);
        $string = str_replace('</p>', '', $string);
        $string = str_word_count($string, 1, '0..9ÁáÉéÍíÓóÚúñäëïöü');
        $i = 0;
        $phrase = "";
        foreach($string as $str) {
            if($i == $number) { break; }
            $phrase .= $str . " ";
            $i++;
        }
        return $phrase;
    }
    
    /**
     * Method to destroy session messages
    */
    public function unsetSession() 
    {
    	$_SESSION['message'] = ''; 
		$_SESSION['messageType'] = '';
    }

    public function pagination($filters) 
    {
		$app = factory::getApplication();
		$lang = factory::getLanguage();
		
    	$total_pages = $_SESSION['total_pages'];
		$html = array();
        $string = '';

        $page = (empty($filters['page'])) ? 1 : $filters['page'];
        unset($filters['page']);
        
		foreach($filters as $k => $v) {
			$string .= '&'.$k.'='.$v;
		}
		
		$first = $lang->get('CW_FIRST');
        $last = $lang->get('CW_LAST');
        $pages = $lang->get('CW_PAGES');
		
		if($total_pages > 0) {
            $html[] = '<ul class="pagination">';
            $html[] = '<li ';
            if($page <= 1 ) { $html[] = 'class="disabled"'; }
            $html[] = '><a href="index.php?'.$string.'&page=1">'.$first.'</a></li>';
			$html[] = '<li class="prev ';
			if($page <= 1 ) { $html[] = ' disabled'; }
			$html[] = '">';
			$html[] = '<a href="';
			if($page <= 1) { $html[] = '#'; } else { $html[] = 'index.php?'.$string.'&page='.($page - 1); }
			$html[] = '"><<</a>';
			$html[] = '</li>';
			$html[] = '<li class="next ';
			if($page >= $total_pages){ $html[] = 'disabled'; }
			$html[] = '">';
			$html[] = '<a href="';
			if($page >= $total_pages){ $html[] = '#'; } else { $html[] = 'index.php?'.$string.'&page='.($page + 1); }
			$html[] = '">>></a>';
			$html[] = '</li>';
			$html[] = '<li ';
			if($page == $total_pages) { $html[] = 'class="disabled"'; }
			$html[] = '>';
			$html[] = '<a href="';
			if($page == $total_pages){ $html[] = '#'; } else { $html[] = 'index.php?'.$string.'&page='.$total_pages; }
			$html[] = '">'.$last.'</a>';
			$html[] = '</li>';
            $html[] = '</ul>';
            $html[] = '<p style="font-size: small">'.$pages.' '.$total_pages.'</p>';

		}
		
		return implode($html);
    }

}
