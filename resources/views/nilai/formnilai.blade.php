<!doctype HTML>
<html>
	<head>
		<title>Form Nilai</title>
		<style>
			body{
			padding: 10px;
			width: 21cm;
			height: 35.56cm;
			font-family: tahoma;
			}
			h3{
			text-align: center;
			}
			table{
			font-size: 12px;
			width: 100%;
			}
			th{
			text-align: left;
			font-size: 13px;
			}
			table#nilai{
			border-collapse: collapse;
			}
			table#nilai th{
			text-align: center;
			}
			table#nilai td, table#nilai th{
			padding: 5px 3px;
			border: 1px solid black;
			}
		</style>
	</head>
	<body>
		<h3>Form Nilai</h3>
		<table width="100%">
			<tr>
				<th width="20%">Matakuliah & Semester</th><th width="2%">:</th><td width="30%">{{ $data -> matkul }} ({{ $data -> kd }}) ({{ $data -> semester }})</td>
				<th width="20%">Dosen</th><th width="2%">:</th><td>{{ $data -> dosen }}</td>
			</tr>
			<tr>
				<th>Program & Kelas</th><th>:</th><td>{{ $data -> program }} @if(isset($data -> kelas)) ({{ $data -> kelas }})@endif</td>
				<th>Prodi</th><th>:</th><td>{{ $data -> prodi }} ({{ $data -> singkatan }})</td>
			</tr>
			<tr>
				<th>Jadwal & Ruang</th><th>:</th><td>@if(isset($data -> hari)){{ config('custom.hari')[$data -> hari] }}, {{ $data -> jam_mulai }} - {{ $data -> jam_selesai }} ({{ $data -> ruang }})@else<span class="text-muted">Belum ada jadwal</span>@endif</td>
				<th>Tahun Akademik</th><th>:</th><td>{{ $data -> ta }}</td>
			</tr>
			<tr>
				<th>Jumlah Mahasiswa</th><th>:</th><td>{{ count($jenis_nilai) }}</td>
				<th></th><th></th><td></td>
			</tr>
		</table>
		<?php $n = 1; ?>
		<table id="nilai">
			<thead>
				<tr>
					<th width="20px" rowspan="2">No.</th>
					<th rowspan="2">NIM</th>
					<th rowspan="2">Nama</th>
					<?php
						$sid = current(array_keys($jenis_nilai));
						$gtype = current(array_keys($jenis_nilai[$sid]));
						krsort($jenis_nilai[$sid]);
					?>
					<th colspan="{{ count($jenis_nilai[$sid]) }}">Komponen Penilaian</th>
					<th rowspan="2">Tanda Tangan</th>					
				</tr>
				<tr>
					@foreach($jenis_nilai[$sid] as $t => $dt)
					@if($dt['jenis_nilai'] == 0)
					<th>Akhir</th>
					@else
					<th>{{ $dt['nama_nilai'] }}</th>
					@endif
				@endforeach				
				</tr>
				</thead>
				<tbody>
				@foreach($jenis_nilai as $mahasiswa)
				<tr>
				<td>{{ $n }}</td>
				<td>{{ $mahasiswa[$gtype]['nim'] }}</td>
				<td>{{ $mahasiswa[$gtype]['nama']}}</td>
				<?php krsort($mahasiswa); ?>
				@foreach($mahasiswa as $nilai)
				<td>
				<?php if($nilai['jenis_nilai'] == 0) {$class=''; } else {$class='nilai_div'; }?>
				<div class="col-xs-12 {{ $class }}" id="{{ $nilai['id_mhs'] }}-{{ $nilai['mt_id'] }}-{{ $nilai['jenis_nilai'] }}">
				<?php 
				if($nilai['jenis_nilai'] == 0)
				{ 
				echo $nilai['nilai']; 
				} 
				else 
				{
				if(isset($nilai['nilai'])) 
				{
				echo $nilai['nilai'];	
				}
				else
				{
				echo '&nbsp;';
				}
				}
				?>
				</div>
				</td>
				@endforeach
				<td></td>
				</tr>
				<?php $n++; ?>
				@endforeach
				</tbody>
				</table>	
				<br/>
				<table>
				<tr>
				<td width="70%">
				<br/>
				Dosen Pengampu<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				{{ $data -> dosen }}
				</td>
				<td>
				Malang, {{ formatTanggal(date('Y-m-d')) }}<br/>
				Ketua PRODI {{ $data -> singkatan }}<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				{{ $data -> kaprodi }}
				</td>
				</tr>
				</table>	
				<script>
				window.print();
				</script>
				</body>
				</html>				