@extends('app')

@section('title')
Kalender Akademik
@endsection

@section('header')
<section class="content-header">
	<h1>
		Akademik
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
		<div class="box-tools">
			<a href="{{ url('/upload/file?name=Kalender%20Akademik&type=6') }}" class="btn btn-danger btn-xs btn-flat" title="Upload kalender akademik"><i class="fa fa-upload"></i> Upload kalender akademik</a>
			<a href="{{ route('kalender.create') }}" class="btn btn-primary btn-xs btn-flat" title="Tambah Kegiatan"><i class="fa fa-plus"></i> Tambah Kegiatan Akademik</a>
		</div>
	</div>
	<div class="box-body">		
		@if(!$agenda->count())
		Belum ada data kegiatan
		@else
		<?php $c = 1; ?>
		<table class="table table-bordered">
			<tr>
				<th>No.</th>
				<th>Tanggal</th>
				<th>Jenis Kegiatan</th>
				<th>Kegiatan</th>
				<th></th>
			</tr>
			@foreach($agenda as $a)
			<tr>
				<td>{{ $c }}</td>
				<td>{{ formatTanggal($a -> mulai) }} @if($a -> sampai != '')- {{ formatTanggal($a -> sampai) }}@endif</td>
				<td>{{ config('custom.pilihan.jenisKegiatan')[$a -> jenis_kegiatan] }}</td>
				<td>{{ $a -> kegiatan }}</td>
				<td>
					{!! Form::open(array('class' => 'form-inline', 'method' => 'DELETE', 'route' => array('kalender.destroy', $a -> id))) !!}
					<a href="{{ route('kalender.edit', $a->id) }}" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i> Edit</a>
					<button class="btn btn-danger btn-xs" type="submit" title="Hapus kegiatan"><i class="fa fa-trash-o"></i> Hapus</button>
					{!! Form::close() !!}
				</td>
			</tr>
			<?php $c++; ?>
			@endforeach
		</table>
		<br/>
		@if($file)
		<a href="{{ url('/download/'. $file -> id . '/' . csrf_token()) }}" class="thumbnail" title="Download kalender akademik">
			<img src="{{ url('/getfile/' . $file -> namafile) }}" />
		</a>
		<!--a href="{{ url('/download/'. $file -> id . '/' . csrf_token()) }}" class="btn btn-primary" title="Download kalender akademik"><i class="fa fa-download"></i> Download kalender akademik</a-->
		@endif
		@endif
	</div>
</div>
@endsection															