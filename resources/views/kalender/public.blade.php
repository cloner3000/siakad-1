@extends('app')

@section('title')
Kalender Akademik
@endsection

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Kalender Akademik</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Kalender Akademik</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Kalender Akademik</h3>
	</div>
	<div class="box-body">
		@if(!$agenda->count())
		<p class="text-muted">Belum ada data kegiatan</span>
		@else
		<?php $c = 1; ?>
		<table class="table table-bordered">
			<tr>
				<th>No.</th>
				<th>Tanggal</th>
				<th>Jenis Kegiatan</th>
				<th>Kegiatan</th>
			</tr>
			@foreach($agenda as $a)
			<tr>
				<td>{{ $c }}</td>
				<td>{{ formatTanggal($a -> mulai) }} @if($a -> sampai != '')- {{ formatTanggal($a -> sampai) }}@endif</td>
				<td>{{ config('custom.pilihan.jenisKegiatan')[$a -> jenis_kegiatan] }}</td>
				<td>{{ $a -> kegiatan }}</td>
			</tr>
			<?php $c++; ?>
			@endforeach
		</table>
		<br/>
		@if($file)
		<a href="{{ url('/download/'. $file -> id . '/' . csrf_token()) }}" class="btn btn-primary" title="Download kalender akademik"><i class="fa fa-download"></i> Download kalender akademik</a>
		@endif
		@endif
	</div>
</div>
@endsection															