@extends('app')

@section('title')
Kartu Hasil Studi @if(!isset($mhs)) @else {{ ' - ' . $mhs['nama'] . ' (' . $mhs['NIM'] . ')' }} @endif
@endsection

@section('styles')
<style>
	.form-group{margin-bottom:0px;}
	.form-group > label{text-align:left !important}
	.loader{
	margin-left: 5px;
	}
	.table{
	width: 100%;
	margin-top: 10px;
	}
	.nilai{
	margin-top: 15px;
	}
	.semester{
	font-weight:bold;
	}
</style>
@endsection

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Kartu Hasil Studi</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		@if(\Auth::user() -> role_id != 512)
		<li><a href="{{ url('/mahasiswa') }}"> Mahasiswa</a></li>
		<li><a href="{{ route('mahasiswa.show', $mhs['id']) }}"> {{ ucwords(strtolower($mhs['nama'])) }}</a></li>
		@endif
		@if(!$all)
		<li><a href="{{ route('mahasiswa.khs', $mhs['NIM']) }}" > Kartu Hasil Studi</a></li>
		<li class="active">{{ $mhs['ta'] }}</li>
		@else
		<li class="active">Kartu Hasil Studi</li>
		@endif
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Data Mahasiswa</h3>
		<div class="box-tools">
			@include('mahasiswa.partials._menu', ['role_id' => \Auth::user() -> role_id, 'mahasiswa' => $mhs])
		</div>
	</div>
	<div class="box-body">
		<table>
			<tr>
				<th>Nama</th><td>:&nbsp;</td><td>{{ $mhs['nama'] }}</td>
			</tr>
			<tr>
				<th>NIM</th><td>:&nbsp;</td><td>{{ $mhs['NIM'] }}</td>
			</tr>
			<tr>
				<th>PRODI</th><td>:&nbsp;</td><td>{{ $mhs['prodi']['nama'] }}</td>
			</tr>
			<tr>
				<th>Program</th><td>:&nbsp;</td><td>{{ $mhs['kelas']['nama'] }}</td>
			</tr>
			<tr>
				<th>Semester</th><td>:&nbsp;</td><td>{{ $mhs['semesterMhs'] }}</td>
			</tr>
			<!--tr>
				<th>Status KHS&nbsp;</th><td>:&nbsp;</td><td></td>
			</tr-->
			@if(!$all)
			<tr>
				<th>Tahun Akademik</th><td>:&nbsp;</td><td>{{ $nilai[array_keys($nilai)[0]][0]['ta'] }}</td>
			</tr>
			<!--tr>
				<th>Semester</th><td>:&nbsp;</td><td>{{ array_keys($nilai)[0] }}</td>
			</tr-->
			@endif
		</table>
	</div>
</div>

<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title"><span class="label label-success">KHS per-semester</span> untuk Mahasiswa, <span class="label label-warning">KHS semua semester</span> untuk dikumpulkan ke KAPRODI</h3>
		<div class="box-tools">
			@if(strtolower(Auth::user() -> role -> name) == 'mahasiswa')
			<a href="{{ route('printmykhs') }}" class="btn btn-warning btn-xs btn-flat" title="Cetak semua"><i class="fa fa-print"></i> Cetak semua</a>
			@else
			<a href="{{ route('mahasiswa.khs.cetak', [$mhs['NIM']]) }}" class="btn btn-warning btn-xs btn-flat" title="Cetak semua"><i class="fa fa-print"></i> Cetak semua</a>
			@endif
			<!--a href="" class="btn btn-danger btn-xs btn-flat" title="Transkrip Nilai"><i class="fa fa-file-text-o"></i> Transkrip Nilai</a-->
		</div>
	</div>
	<div class="box-body">
		@if(!count($nilai))
		<p class="text-muted">Data KHS belum ada</p>
		@else
		<?php
			$sks_kumulatif = 0;
			$sksn_kumulatif = 0;
			$t_count=0;
		?>
		<table class="khs">
			<tr>
				@foreach($nilai as $semester => $n)
				<td width="50%" valign="top">
					<?php 
						$c = 1; 
						$jsks = 0;
						$jsksn = 0;
					?>
					@if($all)
					<span class="semester">Semester {{ $semester }} ({{ $n[0]['ta'] }})
						@if(\Auth::user() -> role_id == 512)
						<!--a href="{{ route('viewmykhs', $n[0]['taid']) }}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> Tampilkan</a--> 
						<a href="{{ route('printmykhs', $n[0]['taid']) }}" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-print"></i> Cetak</a> 	
						@else
						<!--a href="{{ route('mahasiswa.khs', [$mhs['NIM'], $n[0]['taid']]) }}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> Tampilkan</a--> 
						<a href="{{ route('mahasiswa.khs.cetak', [$mhs['NIM'], $n[0]['taid']]) }}" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-print"></i> Cetak</a> 			
						@endif
					</span>
					@endif
					<table class="table table-bordered">
						<tr><th>No</th><th>Kode</th><th>Mata Kuliah</th><th>Nilai</th><th>SKS</th><th>sksN</th></tr>
						@foreach($n as $nn)
						<?php 
							$sksn = array_key_exists($nn['nilai'], config('custom.konversi_nilai.base_4')) ? config('custom.konversi_nilai.base_4')[$nn['nilai']] * $nn['sks'] : 0; 
							// $sksn = config('custom.konversi_nilai.base_4')[$nn['nilai']] * $nn['sks']; 
						?>
						<tr><td>{{ $c }}</td><td>{{ $nn['kode'] }}</td><td>{{ $nn['matkul'] }}</td><td>{{ $nn['nilai'] }}</td><td>{{ $nn['sks'] }}</td><td>{{ $sksn }}</td></tr>
						<?php 
							$c++; 
							$jsks += $nn['sks'];
							$jsksn += $sksn;
						?>
						@endforeach
						
						@if($all)
						@if($c <= 8)
						@while($c <= 8) <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr> <?php $c++; ?>@endwhile
						@endif
						<?php
							$ip = $jsks < 1 ? 0: number_format($jsksn / $jsks, 2);
						?>
						<tr><td></td><td></td><td></td><td><strong>Jumlah</strong></td><td>{{ $jsks }}</td><td>{{ $jsksn }}</td></tr>
						<tr><td></td><td></td><td></td><td><strong>IP</strong></td><td></td><td>{{ $ip }}</td></tr>
						@endif				
					</table>
					<?php
						$sks_kumulatif += $jsks;
						$sksn_kumulatif += $jsksn;
						$t_count++;
					?>
				</td>
				@if($t_count % 2 == 0)</tr><tr>@endif
				@endforeach
			</tr>
		</table>
		<?php
			$ipk = $sks_kumulatif  < 1 ? 0: round($sksn_kumulatif  / $sks_kumulatif , 2);
		?>
		<div class="col-sm-12">
			<table class="table table-bordered" style="width: auto;">
				<tr><td>Kredit Kumulatif</td><td>: {{ $sks_kumulatif }}</td></tr>
				<tr><td>SksN Kumulatif</td><td>: {{ $sksn_kumulatif }}</td></tr>
				<tr><td>Indeks Prestasi Kumulatif</td><td>: {{ $ipk }}</td></tr>
				<tr><td>Predikat</td><td>: {{  predikat($ipk) }}</td></tr>
			</table>
		</div>
		@endif
	</div>
</div>
@endsection																																																