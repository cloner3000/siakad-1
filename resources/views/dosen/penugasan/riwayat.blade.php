@extends('app')

@section('title')
Riwayat Penugasan Dosen | {{ $dosen -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Penugasan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/dosen/') }}"> Dosen</a></li>
		<li><a href="{{ url('/dosen/' . $dosen -> id) }}"> {{ $dosen -> nama }}</a></li>
		<li class="active">Penugasan</li>
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
		<h3 class="box-title">Riwayat Penugasan</h3>
		<div class="box-tools">
			<a href="{{ route('dosen.penugasan.create') }}?dosen={{ $dosen -> id }}" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus"></i> Tambah Penugasan</a>
		</div>
	</div>
	<div class="box-body">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th width="20px">No.</th>
					<th>Tahun Ajaran</th>
					<th>Program Studi</th>
					<th>No Surat Tugas</th>
					<th>Tanggal Surat Tugas</th>
					<th>TMT Surat Tugas</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if(!$penugasan -> count())
				<tr>
					<td colspan="6" align="center">Belum ada data penugasan</td>
				</tr>
				@else
				<?php $c=1; ?>
				@foreach($penugasan as $b)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ rtrim($b -> tapel, ' Ganjil') }}</td>
					<td>{{ $b -> strata }} {{ $b -> prodi }}</td>
					<td>{{ $b -> no_surat_tugas }}</td>
					<td>{{ $b -> tgl_surat_tugas }}</td>
					<td>{{ $b -> tmt_surat_tugas }}</td>
					<td>
						<a class="btn btn-warning btn-xs btn-flat" href="{{ route('dosen.penugasan.edit', $b -> id) }}"><i class=" fa fa-edit"></i> Edit</a>
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