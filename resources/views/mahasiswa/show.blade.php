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
		<li><a href="{{ url('/mahasiswa') }}"> Mahasiswa</a></li>
		<li class="active">{{ ucwords(strtolower($mahasiswa -> nama)) }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Profil</h3>
		<div class="box-tools">
			@include('mahasiswa.partials._menu', ['role_id' => \Auth::user() -> role_id, 'mahasiswa' => $mahasiswa])
			<a href="{{ route('mahasiswa.edit', $mahasiswa -> id) }}" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-pencil-square-o"></i> Edit</a>
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
						<label class="col-sm-2 control-label">Nama:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa->nama }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">NIM:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa->NIM }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">NIRM:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa->NIRM }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">NIRL 1:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa->NIRL1 }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">NIRL 2:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa->NIRL2 }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Status Tinggal:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ config('custom.pilihan.mukim')[$mahasiswa -> mukim] }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Pembiayaan:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ config('custom.pilihan.jenisPembayaran')[$mahasiswa -> jenisPembayaran] }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Dosen Wali:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa -> dosenwali -> nama }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">PRODI:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa -> prodi -> strata }} {{ $mahasiswa -> prodi -> nama }} {{ $mahasiswa -> kelas -> nama }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Semester:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa -> semesterMhs }} </p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Masuk:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa -> tglMasuk or '' }}, {{ $tapel -> nama or '' }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Status Keaktifan:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ config('custom.pilihan.statusMhs')[$mahasiswa -> statusMhs] }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Judul Skripsi:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa -> skripsi -> judul or '-' }}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">SK Yudisium:</label>
						<div class="col-sm-9">
							<p class="form-control-static">
								@if(isset($mahasiswa -> wisuda))
								@if($mahasiswa -> wisuda -> SKYudisium != '')
								{{ $mahasiswa -> wisuda -> SKYudisium }}, <strong>Tanggal:</strong> {{ $mahasiswa -> wisuda -> tglSKYudisium }}
								@else
									- 
								@endif
								@endif
							</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">No. Ijazah:</label>
						<div class="col-sm-9">
							<p class="form-control-static">@if($mahasiswa -> noIjazah != ''){{ $mahasiswa -> noIjazah}}, <strong>Tanggal:</strong> {{ $mahasiswa -> tglIjazah  or '-'}}@else - @endif</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Tgl. Keluar:</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{ $mahasiswa -> tglKeluar  or '-' }}</p>
						</div>
					</div>
					<hr/>
					<div class="form-group">
					<label class="col-sm-2 control-label">Jenis Kelamin:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ config('custom.pilihan.jenisKelamin')[$mahasiswa -> jenisKelamin] }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">TTL:</label>
					<div class="col-sm-9">
					<p class="form-control-static">@if($mahasiswa->tmpLahir != ''){{ $mahasiswa->tmpLahir }}, @endif {{ $mahasiswa->tglLahir }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">NIK:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ $mahasiswa -> NIK }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Status Kewarganegaraan:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ config('custom.pilihan.statusWrgNgr')[$mahasiswa -> statusWrgNgr] }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Kewarganegaraan:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ $mahasiswa -> kewarganegaraan -> nama }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Agama:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ config('custom.pilihan.agama')[$mahasiswa -> agama] }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Status Sipil:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ config('custom.pilihan.statusSipil')[$mahasiswa -> statusSipil] }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Alamat:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ $alamat }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Transportasi:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ config('custom.pilihan.transportasi')[$mahasiswa -> transportasi] }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Telp. Rumah:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ $mahasiswa -> telp }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">HP:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ $mahasiswa -> hp }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Email:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ $mahasiswa -> email }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Terima KPS:</label>
					<div class="col-sm-9">
					<p class="form-control-static">
					@if($mahasiswa -> kps == 'Y')
					Ya
					@else
					Tidak
					@endif
					</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">No. KPS:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ $mahasiswa -> noKps }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Lulus SLTA:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ $mahasiswa -> thSMTA }}, Jurusan {{ $mahasiswa -> jurSMTA }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">NISN:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ $mahasiswa -> NISN }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Jalur Masuk:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ config('custom.pilihan.jalurMasuk')[$mahasiswa -> jalurMasuk] }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Jenis Daftar:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ config('custom.pilihan.jenisPendaftaran')[$mahasiswa -> jenisPendaftaran] }}</p>
					</div>
					</div>
					<hr/>
					<div class="form-group">
					<label class="col-sm-2 control-label">NIK Ayah:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ $mahasiswa -> NIKAyah }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Nama Ayah:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ $mahasiswa -> namaAyah }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Pendidikan Ayah:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ config('custom.pilihan.pendidikanOrtu')[$mahasiswa -> pendidikanAyah] }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Pekerjaan Ayah:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ config('custom.pilihan.pekerjaanOrtu')[$mahasiswa -> pekerjaanAyah] }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Penghasilan Ayah:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ config('custom.pilihan.penghasilanOrtu')[$mahasiswa -> penghasilanAyah] }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">NIK Ibu:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ $mahasiswa -> NIKIbu }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Nama Ibu:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ $mahasiswa -> namaIbu }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Pendidikan Ibu:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ config('custom.pilihan.pendidikanOrtu')[$mahasiswa -> pendidikanIbu] }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Pekerjaan Ibu:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ config('custom.pilihan.pekerjaanOrtu')[$mahasiswa -> pekerjaanIbu] }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Penghasilan Ibu:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ config('custom.pilihan.penghasilanOrtu')[$mahasiswa -> penghasilanIbu] }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Nama Wali:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ $mahasiswa -> namaWali }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Pendidikan Wali:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ config('custom.pilihan.pendidikanOrtu')[$mahasiswa -> pendidikanWali] }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Pekerjaan Wali:</label>
					<div class="col-sm-9">
					<p class="form-control-static">{{ config('custom.pilihan.pekerjaanOrtu')[$mahasiswa -> pekerjaanWali] }}</p>
					</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Penghasilan Wali:</label>
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