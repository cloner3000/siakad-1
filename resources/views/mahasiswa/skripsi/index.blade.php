@extends('app')

@section('title')
Daftar Skripsi
@endsection

@section('header')
<section class="content-header">
	<h1>
		Skripsi
		<small>Daftar Skripsi</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Skripsi</li>
	</ol>
</section>
@endsection

@push('styles')
<style>
	ol{
	padding-left: 15px;
	margin: 0px;
	}
</style>
@endpush

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Pencarian Skripsi</h3>
	</div>
	<div class="box-body">
		<form method="get" action="{{ url('/skripsi/search') }}">
			{!! csrf_field() !!}
			<div class="row">
				<div class="col-xs-12">
					<div class="input-group{{ $errors -> has('q') ? ' has-error' : '' }}">
						<input type="text" class="form-control" name="q" placeholder="Pencarian ...." value="{{ Input::get('q') }}">
						<span class="input-group-btn">
							<button class="btn btn-info btn-flat" type="submit"><i class="fa fa-search"></i> Cari</button>
						</span>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Skripsi</h3>
		<div class="box-tools">
			<a href="{{ route('skripsi.create') }}" class="btn btn-primary btn-xs btn-flat"><i class=" fa fa-plus"></i> Tambah Skripsi</a>
		</div>
	</div>
	<div class="box-body">
		@if(!$skripsi->count())
		<p class="text-muted">Data Skripsi tidak ditemukan</p>
		@else
		<p class="text-muted">{{ $message or '' }}</p>
		<?php $c=($skripsi -> currentPage() - 1) * $skripsi -> perPage();; ?>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>No</th>
					<th width="500px">Judul</th>
					<th>Oleh</th>
					<th>Pembimbing</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($skripsi as $g)
				<?php $c++; ?>
				<tr>
					<td>{{ $c }}</td>
					<td><a href="{{ route('skripsi.show', $g->id) }}" title="Tampilkan Skripsi">{{ $g -> judul }}</a></td>
					<td>
						@if(isset($g -> pengarang))<a href="{{ route('mahasiswa.show', $g -> pengarang -> id) }}" title="Data Mahasiswa">{{ $g -> pengarang -> nama}} ({{ $g -> pengarang -> NIM or '' }})</a>@endif
					</td>
					<td>
						<ol>
							@foreach($g -> pembimbing as $pb)
							@if($pb -> nama != '')
							<li><a href="{{ route('dosen.show', $pb -> id) }}" title="Data Dosen">{{ $pb -> nama }}</a></li>
							@endif
							@endforeach
						</ol>
					</td>
					<td>
						<a href="{{ route('skripsi.edit', $g->id) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data Skripsi"><i class="fa fa-pencil-square-o"></i></a>
						<a href="{{ route('skripsi.delete', $g->id) }}" class="btn btn-danger btn-flat btn-xs has-confirmation" title="Hapus Skripsi"><i class="fa fa-trash"></i></a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		{!! $skripsi -> render() !!}
	</div>
</div>
@endif
@endsection																				