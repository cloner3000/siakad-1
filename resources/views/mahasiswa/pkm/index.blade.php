@extends('app')

@section('title')
PKM
@endsection

@section('header')
<section class="content-header">
	<h1>
		PKM
		<small>Data PKM</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Data PKM</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Data PKM</h3>
		<div class="box-tools">
			<a href="{{ route('mahasiswa.pkm.create') }}" class="btn btn-info btn-xs btn-flat" title="Buat Data PKM"><i class="fa fa-plus"></i></a>
		</div>
	</div>
	<div class="box-body">
		@if(!$pkm -> count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c=1; ?>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>No</th>
					<th>Nama</th>
					<th>Tanggal</th>
					<th>Tempat</th>
					<th>Pendaftaran Peserta</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($pkm as $g)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $g -> nama }}</td>
					<td>{{ formatTanggal(date('Y-m-d', strtotime($g -> tanggal_mulai))) }} - {{ formatTanggal(date('Y-m-d', strtotime($g -> tanggal_selesai))) }}</td>
					<td>{{ $g -> tempat }}</td>
					<td>@if($g -> daftar == 'y')<span class="label label-success">Buka</span>@else<span class="label label-danger">Tutup</span>@endif</td>
					<td>
						<a href="{{ route('mahasiswa.pkm.peserta', $g -> id) }}" class="btn btn-info btn-flat btn-xs" title="Peserta PKM"><i class="fa fa-share-alt"></i> Peserta</a>
						<a href="{{ route('mahasiswa.pkm.edit', $g -> id) }}" class="btn btn-warning btn-flat btn-xs" title="Edit Data PKM"><i class="fa fa-pencil-square-o"></i> Edit</a>
						<a href="{{ route('mahasiswa.pkm.delete', $g -> id) }}" class="btn btn-danger btn-flat btn-xs has-confirmation" title="Hapus PKM"><i class="fa fa-trash"></i> Hapus</a>
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
			</tbody>
		</table>
		@endif
	</div>
</div>
@endsection												