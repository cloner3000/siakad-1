@extends('app')

@section('title')
Daftar Dosen
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Daftar Dosen</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		@if(isset($message))
		<li><a href="{{ url('/dosen') }}"> Daftar Dosen</a></li>
		<li class="active">Pencarian</li>
		@else
		<li class="active">Daftar Dosen</li>
		@endif
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Cari data dosen</h3>
	</div>
	<div class="box-body">
		<form method="post" action="{{ url('/dosen/search') }}">
			{!! csrf_field() !!}
			<div class="row">
				<div class="col-xs-12">
					<div class="input-group{{ $errors -> has('q') ? ' has-error' : '' }}">
						<input type="text" class="form-control" name="q" placeholder="Pencarian ....">
						<span class="input-group-btn">
							<button class="btn btn-info btn-flat" type="submit">Cari</button>
						</span>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

@if(!$dosen->count())
<div class="alert alert-info alert-dismissible">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	<h4><i class="icon fa fa-info"></i> Informasi</h4>
	Data dosen tidak ditemukan
</div>
@else
<?php 
	$role_id = \Auth::user() -> role_id; 
	
	$per_page = $dosen -> perPage();
	$total = $dosen -> total();
	$n = ($dosen -> currentPage() - 1) * $per_page;
	$last = $n + $per_page > $total ? $total : $n + $per_page;
	?>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Dosen <small>{{ $n + 1 }} - {{  $last }} dari {{ $total }}</small></h3>
		<div class="box-tools">
			@if(!$public)<a href="{{ route('dosen.create') }}" class="btn btn-primary btn-xs btn-flat" title="Input Data"><i class="fa fa-plus"></i> Tambah Dosen Baru</a>@endif
		</div>
	</div>
	<div class="box-body">
		<p class="text-muted">{{ $message or '' }}</p>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>No.</th>
					<th>Kode</th>
					<th>Nama</th>
					<th>NIDN / NUP / NIDK</th>
					<th>NIY</th>
					<th>L/P</th>
					<th>Agama</th>
					<th>Tanggal Lahir</th>
					<th>Status</th>
					<th width="160px"></th>
				</tr>
			</thead>
			<tbody>
				@foreach($dosen as $g)
				<?php $n++; ?>
				<tr>
					<td>{{ $n }}</td>
					<td>{{ $g -> kode }}</td>
					<td><a href="{{ route('dosen.show', $g->id) }}" title="Tampilkan detail data Dosen">{{ $g -> nama }}</a></td>
					<td>{{ $g -> NIDN }}</td>
					<td>{{ $g -> NIY }}</td>
					<td>{{ $g -> jenisKelamin }}</td>
					<td>{{ config('custom.pilihan.agama')[$g -> agama] }}</td>
					<td>{{ $g -> tglLahir }}</td>
					<td>{{ config('custom.pilihan.statusKepegawaian')[$g -> statusKepegawaian] }}</td>
					<td>
						<div class="btn-group">
							<a href="{{ route('dosen.show', $g->id) }}" class="btn btn-primary btn-xs btn-flat" title="Tampilkan detail data Dosen"><i class="fa fa-newspaper-o"></i></a>
							<a href="{{ route('dosen.pendidikan', $g->id) }}" class="btn btn-warning btn-xs btn-flat" title="Pendidikan Dosen"><i class="fa fa-graduation-cap"></i></a>
							<a href="{{ route('dosen.jurnal', $g->id) }}" class="btn btn-primary btn-xs btn-flat" title="Jurnal Dosen"><i class="fa fa-keyboard-o"></i></a>
							@if($role_id <= 4)
							<a href="{{ route('gaji.create', $g -> id) }}" class="btn btn-success btn-xs btn-flat" title="Tampilkan data pembayaran gaji Dosen"><i class="fa fa-envelope-o"></i></a>
							@endif
							@if(!$public)
							<a href="{{ route('dosen.penugasan', $g->id) }}" class="btn btn-info btn-xs btn-flat" title="Penugasan Dosen"><i class="fa fa-mouse-pointer"></i></a>
							<a href="{{ route('dosen.edit', $g->id) }}" class="btn btn-warning btn-xs btn-flat" title="Edit data dosen"><i class="fa fa-pencil-square-o"></i></a>
							<a href="{{ route('dosen.delete', $g->id) }}" class="btn btn-danger btn-xs btn-delete has-confirmation btn-flat" title="Hapus data dosen"><i class="fa fa-trash"></i></a>
							@endif
						</div>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		{!! $dosen -> render() !!}
	</div>
</div>
@endif
@endsection																						