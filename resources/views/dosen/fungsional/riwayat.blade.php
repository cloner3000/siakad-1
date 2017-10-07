@extends('app')

@section('title')
Riwayat Fungsional Dosen {{ $dosen -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Fungsional</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/dosen/') }}"> Dosen</a></li>
		<li><a href="{{ url('/dosen/' . $dosen -> id) }}"> {{ $dosen -> nama }}</a></li>
		<li class="active">Riwayat Fungsional</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Dosen</h3>
		<div class="box-tool pull-right">
			@include('dosen.partials._menu', ['dosen' => $dosen])
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-xs-12">
				<table width="100%">
					<tbody>
						<tr>
							<th width="20%">Nama</th><th width="2%">:</th><td width="30%">{{ $dosen -> nama }}</td>
							<th width="20%"></th><th width="2%"></th><td></td>
						</tr>
						<tr>
							<th>Tempat Lahir</th><th>:</th><td>{{ $dosen -> tmpLahir }}</td>
							<th>Tanggal Lahir</th><th>:</th><td>{{ $dosen -> tglLahir }}</td>
						</tr>
						<tr>
							<th>Jenis Kelamin</th><th>:</th><td>@if($dosen -> jenisKelamin == 'L') Laki-laki @else Perempuan @endif</td>
							<th>Agama</th><th>:</th><td>{{ config('custom.pilihan.agama')[$dosen -> agama] }}</td>
						</tr>
						<tr>
							<th>Status</th><th>:</th><td>{{ config('custom.pilihan.statusKepegawaian')[$dosen -> statusKepegawaian] }}</td>
							<th>NIDN</th><th>:</th><td>{{ $dosen -> NIDN }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>		
	</div>
</div>

<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">Riwayat Fungsional</h3>
		<div class="box-tools">
			<a href="{{ route('dosen.fungsional.create', $dosen -> id) }}" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus"></i> Tambah Fungsional</a>
		</div>
	</div>
	<div class="box-body">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th width="20px">No.</th>
					<th>Jabatan</th>
					<th>SK Jabatan</th>
					<th>TMT Jabatan</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if(!$fungsional -> count())
				<tr>
					<td colspan="10" align="center">Belum ada data fungsional</td>
				</tr>
				@else
				<?php 
					$c=1; 
				?>
				@foreach($fungsional as $b)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ config('custom.pilihan.jabatan_fungsional')[$b -> jabatan] }}</td>
					<td>{{ $b -> sk }}</td>
					<td>{{ $b -> tmt }}</td>
					<td>
						<a class="btn btn-warning btn-xs btn-flat" href="{{ route('dosen.fungsional.edit', [$dosen -> id, $b -> id]) }}"><i class=" fa fa-edit"></i> Edit</a>
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
				@endif
			</tbody>
		</table>
	</div>	
</div>	
@endsection																																																							