@extends('app')

@section('title')
Skripsi - {{ $skripsi -> judul }} - {{ $skripsi -> pengarang -> NIM }}
@endsection

@section('styles')
<style>
	#preview{
	display:block;
	width: 200px;
	padding: 5px;
	margin-bottom: 15px;
	border: 1px solid #999;
	}
</style>
@endsection

@section('header')
<section class="content-header">
	<h1>
		Skripsi
		<small>Detail</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa') }}"> Mahasiswa</a></li>
		<li><a href="{{ url('/mahasiswa/' . $skripsi -> pengarang -> id) }}"> {{ ucwords(strtolower($skripsi -> pengarang -> nama)) }}</a></li>
		<li class="active">Detail Skripsi</li>
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
		<h3 class="box-title">Skripsi</h3>
		<div class="box-tools">
			@include('mahasiswa.partials._menu', ['role_id' => \Auth::user() -> role_id, 'mahasiswa' => $skripsi -> pengarang])
			<a href="{{ route('skripsi.edit', $skripsi -> id) }}" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-pencil-square-o"></i> Edit</a>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-3">
				<img id="preview" src="@if(isset($skripsi -> cover) and $skripsi -> cover != '')/getimage/{{ $skripsi -> cover }} @else/images/s.png @endif"></img>
			</div>
			<div class="col-sm-9">
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-2 control-label">Nama:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $skripsi -> pengarang -> nama }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">NIM:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $skripsi -> pengarang -> NIM }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">PRODI:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $skripsi -> pengarang -> prodi -> strata }} {{ $skripsi -> pengarang -> prodi -> nama }} {{ $skripsi -> pengarang -> kelas -> nama }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Pembimbing:</label>
						<div class="col-sm-9">
							<div class="form-control-static">
								<ol>
									@foreach($skripsi -> pembimbing as $pb)
									<li>{{ $pb -> nama }} </li>
									@endforeach
								</ol>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Judul:</label>
						<div class="col-sm-10">
							<p class="form-control-static">{{ $skripsi -> judul }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Abstrak:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $skripsi -> abstrak }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">File:</label>
						<div class="col-sm-9">
							<p class="form-control-static">
							@if(isset($skripsi -> file))
							<a href="{{ route('skripsi.file', $skripsi -> id) }}?token={{ csrf_token() }}" class="btn btn-info btn-xs btn-flat"><i class="fa fa-download"></i> Download</a>
							@endif
							</p>
							</div>
							</div>
							</div>				
							</div>	
							</div>			
							</div>	
							</div>	
							
							
							<div class="box box-danger">
							<div class="box-header with-border">
							<h3 class="box-title">Bimbingan</h3>
							<div class="box-tools">
							<a href="{{ route('mahasiswa.skripsi.bimbingan.create', $skripsi -> id) }}" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-pencil-square-o"></i> Tambah Bimbingan</a>
							</div>
							</div>
							<div class="box-body">
							<table class="table table-bordered">
							<thead>
							<tr>
							<th width="20px">No.</th>
							<th>Tanggal</th>
							<th width="70%">Perihal</th>
							<th>Disetujui</th>
							<th></th>
							</tr>
							</thead>
							<tbody>
							@if(!$skripsi -> bimbingan -> count())
							<tr>
							<td colspan="4" align="center">Belum ada data bimbingan</td>
							</tr>
							@else
							<?php $c=1; ?>
							@foreach($skripsi -> bimbingan as $b)
							<tr>
							<td>{{ $c }}</td>
							<td>{{ $b -> tglBimbingan }}</td>
							<td>{{ $b -> tentang }}</td>
							<td>
							@if($b -> disetujui == 'Y')<i class="fa fa-check text-success"></i>@else<i class="fa fa-times text-danger"></i>@endif
							</td>
							<td>
							<a class="btn btn-warning btn-xs btn-flat" href="{{ route('mahasiswa.skripsi.bimbingan.edit', [$skripsi -> id, $b -> id]) }}"><i class=" fa fa-edit"></i> Edit</a>
							</td>
							</tr>
							<?php $c++; ?>
							@endforeach
							@endif
							</tbody>
							</table>
							</div>	
							</div>	
							@endsection																																																												