<!doctype HTML>
<html>
	<head>
		<title>Jurnal Perkuliahan</title>
		<style>
			body{
			padding: 10px;
			width: 21cm;
			height: 35.56cm;
			font-family: Tahoma;
			}
			table{
			font-size: 13px;
			width: 100%;
			margin-top: 20px;
			}
			table.jurnal{
			font-size: 12px;
			border: 1px solid black;
			border-collapse: collapse;
			}
			table.jurnal th, table.jurnal td{
			border: 1px solid black;
			padding: 7px 2px;
			}
			table.jurnal tbody td{
			text-align: center;
			}
			.left{
			text-align: left !important;
			}
			h2, h3{
			text-align: center; font-size: 15px;margin: 0px;
			}			
			h2{
			text-transform: uppercase; 
			}
		</style>
	</head>
	<body>
		<?php
			$ta = explode(' ', $data -> ta);
		?>
		<h2>Jurnal Perkuliahan semester {{ $ta[1] }}</h2>
		<h2>{{ config('custom.profil.abbr') }} {{ config('custom.profil.name') }}</h2>
		<h3>Tahun Akademik {{ $ta[0] }}</h3>
		<table>
			<tr>
				<td width="100px">Mata Kuliah</td>
				<td width="10px">:</td>
				<td>{{ $data -> matkul }} ({{ $data -> kode }})</td>
			</tr>
			<tr>
				<td>Semester</td>
				<td>:</td>
				<td>{{ arabicToRoman($data -> semester) }}</td>
			</tr>
			<tr>
				<td>PRODI</td>
				<td>:</td>
				<td>{{ $data -> prodi }} ({{ $data -> program }})</td>
			</tr>
			<tr>
				<td>Dosen Pengampu</td>
				<td>:</td>
				<td>{{ $data -> dosen }}</td>
			</tr>
		</table>
		<?php $n=1; ?>
		<table class="jurnal">
			<thead>
				<tr>
					<th width="20px">No.</th>
					<th width="100px">Hari / Tanggal</th>
					<th width="90px">Jam Ke-</th>
					<th>Materi</th>
					<th width="90px">TTD Dosen</th>
					<th width="90px">TTD Ketua Kelas</th>
				</tr>
			</thead>
			<tbody>
				@foreach($jurnals as $jurnal)
				<tr>
					<td>{{ $n }}</td>
					<td>{{ $jurnal -> tanggal }}</td>
					<td>{{ $jurnal -> jam_mulai }} - {{ $jurnal -> jam_selesai }}</td>
					<td class="left">{{ $jurnal -> rencana_materi }}</td>
					<td></td>
					<td></td>
				</tr>
				<?php $n++; ?>
				@endforeach
				@if($n < 16)
				@for($n; $n <= 16; $n++)
				<tr>
					<td>{{ $n }}</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				@endfor
				@endif
			</tbody>
		</table>		
		<table>
			<tr>
				<td width="70%"></td>
				<td>
					{{ config('custom.profil.alamat.kabupaten') }}, .......................... <br/>
					Ketua PRODI {{ $data -> singkatan }}<br/>
					<br/>
					<br/>
					<br/>
					<br/>
					<strong>{{ $data -> kaprodi }}</strong>
				</td>
			</tr>
		</table>	
		<script>
			window.print();
		</script>
	</body>
</html>							