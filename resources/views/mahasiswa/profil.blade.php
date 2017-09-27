@extends('app')

@section('title')
Data Mahasiswa - {{ $mahasiswa -> nama or ''}}
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
		Mahasiswa
		<small>{{ ucwords(strtolower($mahasiswa -> nama)) }}</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">{{ ucwords(strtolower($mahasiswa -> nama)) }}</li>
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
				<img id="preview" src="@if(isset($mahasiswa->foto) and $mahasiswa->foto != '')/getimage/{{ $mahasiswa->foto }} @else/images/b.png @endif"></img>
			</div>
			<div class="col-sm-9">
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-3 control-label">Nama:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa->nama }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">NIM:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa->NIM }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">NIRM:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa->NIRM }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Dosen Wali:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa -> dosenwali -> nama }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Prodi:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa -> prodi -> strata }} {{ $mahasiswa -> prodi -> nama }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Semester:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa -> semesterMhs }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Kelas:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa -> kelas -> nama }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Status Keaktifan:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ config('custom.pilihan.statusMhs')[$mahasiswa -> statusMhs] }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Jenis Kelamin:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ config('custom.pilihan.jenisKelamin')[$mahasiswa -> jenisKelamin] }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">TTL:</label>
						<div class="col-sm-9">
							<p class="form-control-static">@if($mahasiswa->tmpLahir != ''){{ $mahasiswa->tmpLahir }}, @endif {{ $mahasiswa->tglLahir }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">NIK:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa -> NIK }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Status Kewarganegaraan:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ config('custom.pilihan.statusWrgNgr')[$mahasiswa -> statusWrgNgr] }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Kewarganegaraan:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa -> kewarganegaraan -> nama }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Agama:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ config('custom.pilihan.agama')[$mahasiswa -> agama] }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Status Sipil:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ config('custom.pilihan.statusSipil')[$mahasiswa -> statusSipil] }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Alamat:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $alamat }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Lulus SMTA:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa -> thSMTA }} {{ $mahasiswa -> jurSMTA }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Nama Ayah:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa -> namaAyah }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Pendidikan Ayah:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ config('custom.pilihan.pendidikanOrtu')[$mahasiswa -> pendidikanAyah] }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Pekerjaan Ayah:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ config('custom.pilihan.pekerjaanOrtu')[$mahasiswa -> pekerjaanAyah] }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Penghasilan Ayah:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ config('custom.pilihan.penghasilanOrtu')[$mahasiswa -> penghasilanAyah] }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Nama Ibu:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa -> namaIbu }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Pendidikan Ibu:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ config('custom.pilihan.pendidikanOrtu')[$mahasiswa -> pendidikanIbu] }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Pekerjaan Ibu:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ config('custom.pilihan.pekerjaanOrtu')[$mahasiswa -> pekerjaanIbu] }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Penghasilan Ibu:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ config('custom.pilihan.penghasilanOrtu')[$mahasiswa -> penghasilanIbu] }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Nama Wali:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa -> namaWali }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Pendidikan Wali:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ config('custom.pilihan.pendidikanOrtu')[$mahasiswa -> pendidikanWali] }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Pekerjaan Wali:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ config('custom.pilihan.pekerjaanOrtu')[$mahasiswa -> pekerjaanWali] }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Penghasilan Wali:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ config('custom.pilihan.penghasilanOrtu')[$mahasiswa -> penghasilanWali] }}</p>
						</div>
					</div>
					
				</div>				
			</div>	
		</div>	
	</div>	
</div>				
@endsection										