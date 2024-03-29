@extends('app')

@section('title')
Ekspor Data Kurikulum
@endsection

@section('header')
<section class="content-header">
	<h1>
		Ekspor Data
		<small>Kurikulum</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Ekspor Data Kurikulum</li>
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
@if(!count($kurikulum))
<span class="text-muted">Belum ada data</span>
@else
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Kurikulum</h3>
	</div>
	<?php
		$c = 0;
	?>
	<div class="box-body">
		<table class="table table-bordered">
			<tr>
				<th rowspan="2">No.</th>
				<th rowspan="2">Nama</th>
				<th rowspan="2">Program Studi</th>
				<th rowspan="2">Mulai Berlaku</th>
				<th colspan="3">Aturan SKS</th>
				<th colspan="2">SKS Kurikulum</th>
				<th rowspan="2">Ekspor</th>
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
				<td>{{ $k -> strata }} {{ $k -> prodi }}</td>
				<td>{{ $k -> tapel }}</td>
				<td>{{ $k -> sks_total }}</td>
				<td>{{ $k -> sks_wajib }}</td>
				<td>{{ $k -> sks_pilihan }}</td>
				<td>{{ $k -> j_sks_wajib }}</td>
				<td>{{ $k -> j_sks_pilihan }}</td>
				<td>
					<a href="{{ route('export.format', ['kurikulum', $k -> singk, 'xlsx', $k -> id]) }}" class="btn btn-success btn-xs btn-flat" title="Ekspor data kurikulum"><i class="fa fa-file-excel-o"></i> Excel</a>				
				</td>
			</tr>
			@endforeach
		</table>
		@endif
	</div>
</div>
@endsection																