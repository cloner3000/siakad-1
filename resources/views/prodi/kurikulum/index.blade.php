@extends('app')

@section('title')
Daftar Kurikulum
@endsection

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Daftar Kurikulum</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/kurikulum') }}"> Kurikulum</a></li>
		<li class="active"> {{ Input::get('nama') }}</li>
	</ol>
</section>
@endsection

@section('content')
<style>
	th{
	vertical-align: middle !important;
	text-align: center;
	}
</style>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Kurikulum</h3>
		<div class="box-tools">
			<a href="{{ route('prodi.kurikulum.create') }}" class="btn btn-primary btn-xs btn-flat" title="Input Data"><i class="fa fa-plus"></i> Tambah Kurikulum</a>
		</div>
	</div>
	<?php
		$c = 0;
	?>
	<div class="box-body">
		@if(!count($kurikulum))
		<span class="text-muted">Belum ada data</span>
		@else
		<table class="table table-bordered">
			<tr>
				<th rowspan="2">No.</th>
				<th rowspan="2">Nama</th>
				<th rowspan="2">Angkatan</th>
				<th rowspan="2">Program Studi</th>
				<th rowspan="2">Mulai Berlaku</th>
				<th colspan="3">Aturan SKS</th>
				<th colspan="2">SKS Kurikulum</th>
				<th rowspan="2"></th>
			</tr>
			<tr>
				<th>Lulus</th>
				<th>Wajib</th>
				<th>Pilihan</th>
				<th>Wajib</th>
				<th>Pilihan</th>
			</tr>
			@foreach($kurikulum as $k)
			<?php 
				$c++;
			?>
			<tr>
				<td>{{ $c }}</td>
				<td>{{ $k -> nama }}</td>
				<td><a href="{{ route('prodi.kurikulum.detail', $k->id) }}">{{ $k -> angkatan }}</a></td>
				<td>{{ $k -> strata }} {{ $k -> prodi }}</td>
				<td>{{ $k -> tapel }}</td>
				<td>{{ $k -> sks_total }}</td>
				<td>{{ $k -> sks_wajib }}</td>
				<td>{{ $k -> sks_pilihan }}</td>
				<td>{{ $k -> j_sks_wajib }}</td>
				<td>{{ $k -> j_sks_pilihan }}</td>
				<td>
					<a href="{{ route('prodi.kurikulum.edit', $k->id) }}" class="btn btn-warning btn-xs btn-flat" title="Edit data kurikulum"><i class="fa fa-edit"></i></a>
					<a href="{{ route('prodi.kurikulum.delete', $k->id) }}" class="btn btn-danger btn-xs has-confirmation btn-flat" title="Hapus data kurikulum"><i class="fa fa-trash"></i></a>					
				</td>
			</tr>
			@endforeach
		</table>
	@endif
	</div>
	</div>
	@endsection																					