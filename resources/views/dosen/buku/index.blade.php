@extends('app')

@section('title')
Daftar Buku Dosen
@endsection

@section('header')
<section class="content-header">
	<h1>
		Buku Dosen
		<small>Daftar</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Buku Dosen</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Buku Dosen</h3>
		<div class="box-tools">
			<a href="{{ route('dosen.buku.export') }}" class="btn btn-success btn-xs btn-flat" title="Export Data Buku Dosen"><i class="fa fa-file-excel-o"></i> Export Buku Dosen</a>
			<a href="{{ route('dosen.buku.create') }}" class="btn btn-primary btn-xs btn-flat" title="Input Data Buku Dosen"><i class="fa fa-plus"></i> Tambah Buku Dosen</a>
		</div>		
	</div>
	<div class="box-body">
		@if(!$buku->count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c=1; ?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>No.</th>
					<th>Nama</th>
					<th>Judul Buku</th>
					<th>Klasifikasi</th>
					<th>Penerbit</th>
					<th>ISBN</th>
					<th>Tahun Terbit</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($buku as $b)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $b -> dosen }}</td>
					<td>{{ $b -> judul }}</td>
					<td>@if($b -> klasifikasi == 1) Buku Referensi @else Buku Monograf @endif</td>
					<td>{{ $b -> penerbit }}</td>
					<td>{{ $b -> isbn }}</td>
					<td>{{ $b -> tahun_terbit }}</td>
					<td>
						<a href="{{ route('dosen.buku.edit', $b -> buku_id) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data buku"><i class="fa fa-pencil-square-o"></i> Edit</a>
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