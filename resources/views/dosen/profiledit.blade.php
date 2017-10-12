@extends('app')

@section('title')
Ubah data Dosen - {{ $dosen -> nama or 'Invalid'}}
@endsection

@section('scripts2')
<script src="{{ asset('/js/jquery.inputmask.bundle.min.js') }}"></script>
<script>
	$(function(){
		$(".date").inputmask("dd-mm-yyyy",{"placeholder":"dd-mm-yyyy"});
	});
</script>
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>{{ $dosen -> nama }}</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/profil') }}"> {{ $dosen -> nama }}</a></li>
		<li class="active">Update</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Update Profil</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-3 image_upload">
				<div id="validation-errors"></div>
				{!! Form::open(['url' => url('upload/image'), 'class' => 'form-inline', 'files' => true, 'autocomplete' => 'off', 'id' => 'upload']) !!}
				@include('_partials/_foto', ['foto' => $dosen -> foto, 'default_image' => 'teacher.png'])
				{!! Form::close() !!}
			</div>
			<div class="col-sm-9">
				{!! Form::model($dosen, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['user.profile.update']]) !!}				
				<div class="form-group">
					{!! Form::label('nama', 'Nama Lengkap:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-8">
						<p class="form-control-static">{{ $dosen -> nama }}</p>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('NIDN', 'NIDN:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-4 col-xs-9">
						{!! Form::text('NIDN', null, array('class' => 'form-control', 'placeholder' => 'Nomor Induk Dosen Nasional')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('golongan', 'Golongan:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-9">
						<div style="display:inline-block;">
							{!! Form::text('golongan', null, array('class' => 'form-control', 'placeholder' => 'Golongan')) !!}
						</div>
						<div style="display:inline-block;">
							{!! Form::text('kepangkatan', null, array('class' => 'form-control', 'placeholder' => 'Kepangkatan')) !!}
						</div>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('noIdentitas', 'No. KTP:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('noIdentitas', null, array('class' => 'form-control', 'placeholder' => 'Nomor KTP/Paspor')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('npwp', 'NPWP:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('npwp', null, array('class' => 'form-control', 'placeholder' => 'Nomor Pokok Wajib Pajak')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('tmpLahir', 'TTL:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-9">
						<div style="display:inline-block;">
							{!! Form::text('tmpLahir', null, array('class' => 'form-control', 'placeholder' => 'Tempat Lahir')) !!}
						</div>
						<div style="display:inline-block;">
							{!! Form::text('tglLahir', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Lahir')) !!}
						</div>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('jenisKelamin', 'Jenis Kelamin:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-8">
						<?php
							foreach(config('custom.pilihan.jenisKelamin') as $k => $v) 
							{
								echo '<label class="radio-inline">';
								echo '<input type="radio" name="jenisKelamin" ';
								if(isset($dosen) and $k == $dosen -> jenisKelamin) echo 'checked="checked" ';
								echo 'value="'. $k .'"> '. $v .'</label>';
							}
						?>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('agama', 'Agama:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-9">
						<?php
							foreach(config('custom.pilihan.agama') as $k => $v) 
							{
								echo '<label class="radio-inline">';
								echo '<input type="radio" name="agama" ';
								if(isset($dosen) and $k == $dosen -> agama) echo 'checked="checked" ';
								echo 'value="'. $k .'"> '. $v .'</label>';
							}
						?>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('statusSipil', 'Status Sipil:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-8">
						<?php
							foreach(config('custom.pilihan.statusSipil') as $k => $v) 
							{
								echo '<label class="radio-inline">';
								echo '<input type="radio" name="statusSipil" ';
								if(isset($dosen) and $k == $dosen -> statusSipil) echo 'checked="checked" ';
								echo 'value="'. $k .'"> '. $v .'</label>';
							}
						?>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('alamat', 'Alamat:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-6">
						{!! Form::textarea('alamat', null, array('class' => 'form-control', 'rows' => '5')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('telp', 'Telepon:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-4">
						{!! Form::text('telp', null, array('class' => 'form-control', 'placeholder' => 'Nomor Telepon/HP')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('email', 'Email:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-4">
						{!! Form::email('email', null, array('class' => 'form-control', 'placeholder' => 'Email')) !!}
					</div>
				</div>
				
				{!! Form::hidden('foto', null, array('id' => 'foto')) !!}
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-10">
						<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
					</div>		
				</div>	
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection