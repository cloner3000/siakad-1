<?php	
	function getRoleName($json, $roles)
	{
		$json = json_decode($json);
		if(!count($json)) return '';
		foreach($json as $r)
		{
			$tmp[] = $roles[$r];	
		}
		return implode(', ', $tmp);
	}
	function checkKrs($aktif, $krs)
	{
		foreach($krs as $k)
		{
			if($k -> tapel_id == $aktif) return true;
		}
		return false;
	}
	function getPMBKey($no)
	{
		return sha1($no . date('YmdH') . csrf_token());
	}	
	
	function convertKuotaProdi($prodi, $kuota)
	{
		foreach(json_decode($kuota) as $k => $v) $tmp[] = $prodi[$k] . ': ' . intval($v); 
		return implode(', ', $tmp);
	}
	function isOnMaintenis()
	{
		if(file_exists(storage_path() . '/framework/maintenis'))
		{
			$message = parse_ini_file(storage_path() . '/framework/maintenis');
			if(isset($message))
			{
				$msg = '';
			$div_o = '<div style="width: 100%; height: 30px; padding: 5px 25px; background-color: #fe370e; color: white; z-index: 9999; text-align: center;"><strong>PERHATIAN:</strong> ';
			if($message['mode'] == 1)
			{
				$msg .= 'Aplikasi ' . config('custom.app.abbr') .' '. config('custom.app.version') .' sedang dalam mode <strong>TERBATAS</strong>, ';
				$msg .= ' hanya pengguna tertentu yang bisa masuk.';
				// if(count($message['allowed']) > 0) $msg .= ' hanya pengguna dengan wewenang ['. implode(', ', $message['allowed']) .'] yang bisa masuk.';
				$msg .= 'Beberapa fungsi mungkin tidak dapat digunakan.';
			}
			elseif($message['mode'] == 2)
			{
				$msg .= 'Aplikasi ' . config('custom.app.abbr') .' '. config('custom.app.version') .' sedang dalam perbaikan. Beberapa fungsi mungkin tidak dapat digunakan.';
			}
			$div_c = '</div>';	
			
			if($message['message'] != '') $msg = $message['message'];
			return $div_o . $msg . $div_c;
			}
			}
	}
	function maintenisMessage()
	{
		$str = 'Maaf, Aplikasi ' . config('custom.app.abbr') .' '. config('custom.app.version') .' sedang dalam perbaikan.';
		if(file_exists(storage_path() . '/framework/maintenis'))
		{
			$message = parse_ini_file(storage_path() . '/framework/maintenis');
			if(isset($message))
			{
				if($message['message'] != '') $str = $message['message'];
			}
		}
		echo $str;		
	}
	
	function terbilang($numb){  
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");  
		if($numb < 12)  
		return " " . $huruf[$numb];  
		elseif ($numb < 20)  
		return terbilang($numb - 10) . " belas";  
		elseif ($numb < 100)  
		return terbilang($numb / 10) . " puluh" . terbilang($numb % 10);  
		elseif ($numb < 200)  
		return " seratus" . terbilang($numb - 100);  
		elseif ($numb < 1000) 
		return terbilang($numb / 100) . " ratus" . terbilang($numb % 100);  
		elseif ($numb < 2000)  
		return " seribu" . terbilang($numb - 1000);   
		elseif ($numb < 1000000)  
		return terbilang($numb / 1000) . " ribu" . terbilang($numb % 1000);   
		elseif ($numb < 1000000000)  
		return terbilang($numb / 1000000) . " juta" . terbilang($numb % 1000000);   
		elseif ($numb >= 1000000000)  
		return false;  
	}
	
	/* http://mattsenior.com/2013/08/arabic-to-roman-numerals-conversion-with-PHP-and-regex */
	function arabicToRoman($n)
	{
		$n = str_repeat('I', $n);
		
		foreach (array('/I{5}/ V /V{2}/ X', '/I{4}/ IV /VIV/ IX') as $p) {
			foreach (array('IVX', 'XLC', 'CDM') as $r) {
				$a = explode(' ', strtr($p, 'IVX', $r));
				$n = preg_replace(array($a[0], $a[2]), array($a[1], $a[3]), $n);
			}
		}
		
		return $n;
	}
	
	function cutStr($str, $l=5)
	{
		if(strlen($str) <= $l) return $str;
		return substr($str, 0, $l) . ' ...';
	}
	
	function predikat($n){
		if($n <= 4 && $n > 3.75){
			$ret = 'Cumlaude';
			}elseif($n <= 3.75 && $n > 3.5){
			$ret = 'Sangat Memuaskan';
			}elseif($n <= 3.5 && $n > 3){
			$ret = 'Memuaskan';
			}elseif($n <= 3 && $n > 2.5){
			$ret = 'Cukup';
			}elseif($n <= 2.5 && $n > 2){
			$ret = 'Kurang';
			}else{
			$ret = 'Tidak Lulus';
		}
		return $ret;
	}
	function numberToLetter($number, $base = 'base_4'){
		$number = intval($number);
		if($base === 'base_100'){
			switch($number){
				case ($number <= 100 and $number > 90):
				$ret = 'A+';break;
				
				case ($number <= 90 and $number > 85):
				$ret = 'A';break;
				
				case ($number <= 85 and $number > 80):
				$ret = 'A-';break;
				
				case ($number <= 80 and $number > 75):
				$ret = 'B+';break;
				
				case ($number <= 75 and $number > 70):
				$ret = 'B';break;
				
				case ($number <= 70 and $number > 65):
				$ret = 'B-';break;
				
				case ($number <= 65 and $number > 60):
				$ret = 'C+';break;
				
				case ($number <= 60 and $number > 55):
				$ret = 'C';break;
				
				case ($number <= 55 and $number > 50):
				$ret = 'C-';break;
				
				default:
				$ret = 'D';
			}
			}else{
			if($number <= 4 && $number > 3.75){
				$ret = 'A+';
				}elseif($number <= 3.75 && $number > 3.5){
				$ret = 'A';
				}elseif($number <= 3.5 && $number > 3.25){
				$ret = 'A-';
				}elseif($number <= 3.25 && $number > 3){
				$ret = 'B+';
				}elseif($number <= 3 && $number > 2.75){
				$ret = 'B';
				}elseif($number <= 2.75 && $number > 2.5){
				$ret = 'B-';
				}elseif($number <= 2.5 && $number > 2.25){
				$ret = 'C+';
				}elseif($number <= 2.25 && $number > 2){
				$ret = 'C';
				}elseif($number <= 2 && $number > 1.75){
				$ret = 'C-';
				}else{
				$ret = 'D';
			}
		}
		return $ret;
	}
	
	function validateDate($date, $format = 'Y-m-d H:i:s')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
	
	function formatTanggal($Ymd)
	{
		if(!validateDate($Ymd, 'Y-m-d')) return '-';
		$date = explode('-', $Ymd);
		$bln = $date[1];
		return $date[2] . ' ' . config('custom.bulan')[$bln] . ' ' . $date[0];
	}
	
	function formatTanggalWaktu($time) //Y-m-d H:i:s
	{
		if(!validateDate($time)) return '-';
		$time = strtotime($time);
		return config('custom.hari')[date('N', $time)] . ', ' . date('d', $time) . ' ' . config('custom.bulan')[date('m', $time)] . ' ' . date('Y', $time) . ' ' . date('H:i:s', $time);
	}
	
	//13-12-2000 -> 2000-12-13
	function toYmd($dmY)
	{
		if(!validateDate($dmY, 'd-m-Y')) return '-';
		if($dmY == '' or empty($dmY) or !isset($dmY)) $dmY = '00-00-0000';
		$date = explode('-', $dmY);
		return $date[2] . '-' . $date[1] . '-' . $date[0];
	}														