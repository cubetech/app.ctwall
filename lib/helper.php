<?php
	
	/**
	 * Time Ago
	 *
	 * @param $datetime mysql datetime format
	 * @param $full - return full datetime or not
	 *			 
	 * @return datetime - time ago 
	 */
	function timeAgo($datetime, $full = false)
	{
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);
		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;
		$string = [
			'y' => 'Jahr',
			'm' => 'Monat',
			'w' => 'Woche',
			'd' => 'Tag',
			'h' => 'Stunde',
			'i' => 'Minute',
			's' => 'Sekunde',
		];
		foreach ($string as $k => &$v) 
		{
			if ($diff->$k) 
			{
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? (substr($v, -1) == 'e' ? 'n' : 'en') : '');
			} 
			else 
			{
				unset($string[$k]);
			}
		}
		if ( ! $full)
		{
			$string = array_slice($string, 0, 1);	
		} 
		return $string ? 'vor ' . implode(', ', $string) . '' : 'gerade jetzt';
	}
	
	/**
	 * contains
	 *
	 * @param $str string to search within
	 * @param $arr array with keywords
	 *			 
	 * @return datetime - time ago 
	 */
	function contains($str, array $arr) {
	    foreach($arr as $a) {
	        if (stripos($str,$a) !== false) return true;
	    }
	    return false;
	}

