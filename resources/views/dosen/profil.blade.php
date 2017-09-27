@extends('app')

@section('title')
Data Dosen - {{ $dosen -> nama}}
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
		Dosen
		<small>{{ $dosen -> nama }}</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">{{ $dosen -> nama }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Profil</h3>
		<div class="box-tools">
			<a href="{{ route('user.profile.edit') }}" class="btn btn-warning btn-xs btn-flat" title="Edit Profil"><i class="fa fa-edit"></i> Update Profil</a>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-3">
				<img id="preview" src="@if(isset($dosen->foto) and $dosen->foto != '') /getimage/{{ $dosen->foto }} @else /images/teacher.png @endif"></img>
			</div>
			<div class="col-sm-9">
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-3 control-label">Kode Dosen:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $dosen->kode }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Nama:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $dosen->nama }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">NIDN:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $dosen->NIDN }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Golongan:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $dosen->golongan }} {{ $dosen->kepangkatan }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">NIY:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $dosen->NIY }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">No. KTP/Paspor:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $dosen->noIdentitas }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">TTL:</label>
						<div class="col-sm-9">
							<p class="form-control-static">@if($dosen->tmpLahir != ''){{ $dosen->tmpLahir }}, @endif {{ $dosen->tglLahir }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Jenis Kelamin:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ config('custom.pilihan.jenisKelamin')[$dosen -> jenisKelamin] }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Agama:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ config('custom.pilihan.agama')[$dosen -> agama]}}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Status Sipil:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ config('custom.pilihan.statusSipil')[$dosen -> statusSipil] }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Alamat:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $dosen -> alamat }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Telepon:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $dosen -> telp }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Email:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $dosen -> email }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Status:</label>
						<div class="col-sm-9">
							<p class="form-control-static">@if(isset(config('custom.pilihan.statusDosen')[$dosen -> statusDosen])){{ config('custom.pilihan.statusDosen')[$dosen -> statusDosen] }}@endif</p>
						</div>
					</div>		
				</div>				
			</div>				
		</div>				
	</div>				
</div>				
@endsection