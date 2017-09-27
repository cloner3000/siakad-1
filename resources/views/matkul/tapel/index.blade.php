@extends('app')

@section('title')
Daftar Kelas Perkuliahan
@endsection

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Kelas Kuliah</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Kelas Kuliah</li>
	</ol>
</section>
@endsection

@section('scripts')
<script>
	$('.filter').change(function(){
		$('form#filter-form').submit();
	});
</script>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter</h3>
	</div>
	<div class="box-body">
		<form method="get" action="{{ url('/matkul/tapel/filter') }}" class="form-inline" id="filter-form">
			{!! csrf_field() !!}
			<div class="form-group">
				<label class="sr-only" for="ta">Tahun Akademik</label>
				{!! Form::select('ta', $semester, Input::get('ta'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<label class="sr-only" for="prodi">PRODI</label>
				{!! Form::select('prodi', $prodi, Input::get('prodi'), ['class' => 'form-control filter']) !!}
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

<?php 
	$per_page = $data -> perPage();
	$total = $data -> total();
	$c = ($data -> currentPage() - 1) * $per_page;
	$last = $c + $per_page > $total ? $total : $c + $per_page;
?>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Kelas Kuliah @if(count($data) > 0){{ $data[0] -> tapel }}@endif  <small>{{ $c + 1 }} - {{ $last }} dari {{ $total }}</small></h3>
		<div class="box-tools">
			<a href="{{ route('matkul.tapel.create') }}" class="btn btn-primary btn-xs btn-flat" title="Pendaftaran Kelas Perkuliahan Baru"><i class="fa fa-plus"></i> Tambah Kelas Perkuliahan</a>
		</div>
	</div>
	<div class="box-body">
		@if(!count($data))
		<p class="text-muted">Belum ada data</p>
		@else
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>No.</th>
					<th>Kode</th>
					<th>Nama</th>
					<th>Smt</th>
					<th>SKS</th>
					<th>Dosen</th>
					<th>PRODI</th>
					<th>Kelas</th>
					<th>Peserta</th>
					<th>Nilai</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $mk)			
				<?php $c++; ?>
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $mk -> kode }}</td>
					<td>{{ $mk -> matkul }}</td>
					<td>{{ $mk -> semester }}</td>
					<td>{{ $mk -> sks }}</td>
					<td>{{ $mk -> dosen }}</td>
					<td>{{ $mk -> prodi }} {{ $mk -> program }}</td>
					<td>{{ $mk -> semester }}{{ $mk -> kelas }}</td>
					<td>{{ $mk -> peserta }} / {{ $mk -> kuota }}</td>
					<td>@if($mk -> nilai == 0)<i class="fa fa-exclamation-triangle text-danger"></i>@else<i class="fa fa-check text-success"></i>@endif</td>
					<td>
						<div class="btn-group">
							<a href="{{ url('/jadwal/create?id=' . $mk -> mtid) }}" class="btn btn-xs btn-danger btn-flat" title="Input Jadwal Kuliah"><i class="fa fa-plus"></i></a>
							<a href="{{ url('/matkul/tapel/' . $mk -> mtid . '/mahasiswa') }}" class="btn btn-xs btn-primary btn-flat" title="Peserta Kuliah"><i class="fa fa-group"></i></a>
							<a href="{{ url('/matkul/tapel/' . $mk -> mtid . '/cetak/formabsensi') }}" class="btn btn-xs btn-primary btn-flat" title="Cetak Form Absensi" target="_blank"><i class="fa fa-print"></i></a>
							<a href="{{ url('/kelaskuliah/' . $mk -> mtid . '/cetak/formjurnal') }}" class="btn btn-xs btn-info btn-flat" title="Cetak Form Jurnal" target="_blank"><i class="fa fa-print"></i></a>
						</div>
						<div class="btn-group">
							<a href="{{ url('/matkul/tapel/' . $mk -> mtid . '/nilai') }}" class="btn btn-xs btn-success btn-flat" title="Nilai"><i class="fa fa-bar-chart"></i></a>
							<a href="{{ url('/matkul/tapel/'. $mk -> mtid . '/nilai/cetak') }}" class="btn btn-xs btn-success btn-flat" title="Cetak form Nilai" target="_blank"><i class="fa fa-print"></i></a>
						</div>
						<a href="{{ url('/kelaskuliah/'. $mk->mtid .'/absensi/cetak') }}" class="btn btn-xs btn-warning btn-flat" title="Cetak Absensi Sesuai Jurnal" target="_blank"><i class="fa fa-print"></i></a>
						<a href="{{ route('matkul.tapel.jurnal.print', [$mk->mtid]) }}" class="btn btn-info btn-xs btn-flat" title="Cetak Jurnal" target="_blank"><i class="fa fa-print"></i></a>
						<a href="{{ route('matkul.tapel.export', [$mk->mtid]) }}" class="btn btn-success btn-xs btn-flat" title="Export ke MS Excel"><i class="fa fa-file-excel-o"></i></a>
						<a href="{{ route('matkul.tapel.edit', $mk->mtid) }}" class="btn btn-xs btn-warning btn-flat" title="Ubah data"><i class="fa fa-edit"></i></a>
						<a href="{{ url('/matkul/tapel/' . $mk -> mtid . '/delete') }}" class="btn btn-xs btn-danger btn-flat has-confirmation" title="Hapus data"><i class="fa fa-trash"></i></a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		{!! $data -> appends([
		'_token' => csrf_token(), 
		'ta' => Input::get('ta'), 
		'prodi' => Input::get('prodi'), 
		'perpage' => Input::get('perpage')
	]) -> render() !!}
	</div>
	</div>
	@endif
	@endsection																																							