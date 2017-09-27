<?php
	//RANDOM KUESIONER GENERATOR
	$data = null;
	$conn = mysqli_connect('127.0.0.1', 'sachz', '5t2r5cre23', 'siakad2.5');
	
	$mhs = mysqli_query($conn, 'SELECT id FROM mahasiswa');
	while( $fetch = mysqli_fetch_assoc( $mhs ) ) 
	{
		$mt_id = mysqli_query($conn, 'select matkul_tapel.id 
		from `nilai` 
		inner join `matkul_tapel` on `matkul_tapel_id` = `id` 
		where `jenis_nilai_id` = 0 and `mahasiswa_id` = '. $fetch['id'] .' and `nilai`.`nilai` is not null 
		group by `matkul_tapel`.`id`'
		);
		if(!mysqli_error($conn))
		{
			while( $fetch2 = mysqli_fetch_assoc( $mt_id ) ) {
				$data[$fetch['id']][] = $fetch2['id'];
			}
		}
	}
	
	$kue = mysqli_query($conn, 'SELECT id FROM kuesioner WHERE tampil = "y"');
	while( $fetch3= mysqli_fetch_assoc( $kue ) ) 
	{
		$k[] = $fetch3['id'];
	}
	
	$r = $c = 1;
	$query = 'INSERT INTO kuesioner_mahasiswa (kuesioner_id, mahasiswa_id, matkul_tapel_id, skor) VALUES ';
	foreach($data as $m => $mt)
	{
		foreach($mt as $mtid)
		{
			foreach($k as $kid)
			{
				$tmp[$r][] = '(' . $kid . ', ' . $m . ', ' . $mtid . ', "' . rand(1, 5) . '")';
				$c++;
				if($c > 1000) 
				{
					$r ++;
					$c = 1;
				}
			}
		}
	}
	
	foreach($tmp as $tv)
	echo $query . implode(', ', $tv) . ';';
	
	
