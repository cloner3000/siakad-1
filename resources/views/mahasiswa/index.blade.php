@extends('app')

@section('title')
Daftar Mahasiswa
@endsection

@section('styles')
<style>
	td > small{
	font-size: 9px;
	}
	time{
	display: block;
	font-size: 10px;
	}
</style>
@endsection

@section('scripts')
<script>
	$('.filter').change(function(){
		$('form#filter-form').submit();
	});
</script>

<script src="{{ url('/js/jquery.timeago.js') }}" type="text/javascript"></script>
<script>
	jQuery.timeago.settings.strings = {
		prefixAgo: null,
		prefixFromNow: null,
		suffixAgo: "yang lalu",
		suffixFromNow: "dari sekarang",
		seconds: "kurang dari semenit",
		minute: "sekitar satu menit",
		minutes: "%d menit",
		hour: "sekitar sejam",
		hours: "sekitar %d jam",
		day: "sehari",
		days: "%d hari",
		month: "sekitar sebulan",
		months: "%d bulan",
		year: "sekitar setahun",
		years: "%d tahun"
	};
	jQuery(document).ready(function() {
		jQuery("time.timeago").timeago();
	});
</script>
@endsection

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Daftar Mahasiswa</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		@if(isset($message))
		<li><a href="{{ url('/mahasiswa') }}"> Daftar Mahasiswa</a></li>
		<li class="active">Pencarian</li>
		@else
		<li class="active">Daftar Mahasiswa</li>
		@endif
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Cari data mahasiswa</h3>
	</div>
	<div class="box-body">
		<form method="get" action="{{ url('/mahasiswa/search') }}">
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

<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter</h3>
	</div>
	<div class="box-body">
		<form method="get" action="{{ url('/mahasiswa/filter') }}" class="form-inline" id="filter-form">
			{!! csrf_field() !!}
			<div class="form-group">
				<label class="sr-only" for="prodi">PRODI</label>
				{!! Form::select('prodi', $prodi, Input::get('prodi'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<label class="sr-only" for="program">Program</label>
				{!! Form::select('program', $program, Input::get('program'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<label class="sr-only" for="angkatan">Angkatan</label>
				{!! Form::select('angkatan', $angkatan, Input::get('angkatan'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<label class="sr-only" for="semester">Semester</label>
				{!! Form::select('semester', $semester, Input::get('semester'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<label class="sr-only" for="status">Status</label>
				{!! Form::select('status', $status, Input::get('status'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<label class="sr-only" for="perpage">N-Data</label>
				{!! Form::select('perpage', [25 => 25, 50 => 50, 100 => 100, 200 => 200], Input::get('perpage'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-filter"></i> Filter</button>
			</div>
		</form>
	</div>
</div>

@if(!$mahasiswa -> count())
<div class="alert alert-info alert-dismissible">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	<h4><i class="icon fa fa-info"></i> Informasi</h4>
	Data mahasiswa tidak ditemukanf
</div>
@else
<?php 
	$role_id = \Auth::user() -> role_id; 
	
	$per_page = $mahasiswa -> perPage();
	$total = $mahasiswa -> total();
	$n = ($mahasiswa -> currentPage() - 1) * $per_page;
	$last = $n + $per_page > $total ? $total : $n + $per_page;
?>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Mahasiswa <small>{{ $n + 1 }} - {{  $last }} dari {{ $total }}</small></h3>
		<div class="box-tools">
			<a href="{{ route('mahasiswa.import') }}" class="btn btn-success btn-xs btn-flat" title="Impor Data Mahasiswa"><i class="fa fa-file-excel-o"></i> Import Data Mahasiswa</a>
			<a href="{{ route('mahasiswa.yudisium.import') }}" class="btn btn-success btn-xs btn-flat" title="Impor Yudisium Mahasiswa"><i class="fa fa-file-excel-o"></i> Import Yudisium Mahasiswa</a>
			<a href="{{ route('mahasiswa.create') }}" class="btn btn-primary btn-xs btn-flat" title="Input Data"><i class="fa fa-plus"></i> Tambah Mahasiswa</a>
		</div>
	</div>
	<div class="box-body">
		<p class="text-muted">{{ $message or '' }}</p>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>No</th>
					<th>NIM</th>
					<th>NIRM</th>
					<th>Nama <small>(Last login)</small></th>
					<th>Semester</th>
					<th>Prodi</th>
					<th>Program</th>
					<th>Status</th>
					<th>KRS</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($mahasiswa as $g)
				<?php 
					$n++; 
					$krs = '<i class="fa fa-warning text-danger"></i>';
					if(count($g -> krs)) if(checkKRS($aktif, $g -> krs)) $krs = '<i class="fa fa-check text-success"></i>';					
				?>
				<tr>
					<td>{{ $n }}</td>
					<td>{{ $g -> NIM }}</td>
					<td>{{ $g -> NIRM }}</td>
					<td>
						<a href="{{ route('mahasiswa.show', $g -> id) }}">{{ $g -> nama }}</a>
						<time class="timeago" datetime="{{ $g -> authInfo -> last_login or '' }}"></time>
					</td>
					<td>{{ $g -> semesterMhs }}</td>
					<td>{{ $g -> prodi -> singkatan }}</td>
					<td>{{ $g -> kelas -> nama }}</td>
					<td>{{ config('custom.pilihan.statusMhs')[$g -> statusMhs] }}</td>
					<td>{!! $krs !!}</td>
					<td>
						@if($role_id === 4)
						<a href="{{ route('biaya.create', $g -> id) }}" class="btn btn-success btn-xs btn-flat"><i class="fa fa-money"></i> Pembayaran</a>
						@endif
						<a href="{{ route('mahasiswa.edit', $g -> id) }}" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-pencil-square-o"></i></a>
						<a href="{{ route('mahasiswa.delete', $g -> id) }}" class="btn btn-danger btn-xs has-confirmation btn-flat" data-message="Hapus data mahasiswa {{ $g -> nama }}?"><i class="fa fa-trash"></i></a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	{!! $mahasiswa -> appends([
	'_token' => csrf_token(), 
	'q' => Input::get('q'), 
	'prodi' => Input::get('prodi'), 
	'program' => Input::get('program'), 
	'angkatan' => Input::get('angkatan'), 
	'semester' => Input::get('semester'), 
	'status' => Input::get('status'), 
	'perpage' => Input::get('perpage')
	]) -> render() !!}
	</div>
	</div>
	@endif
	@endsection																															