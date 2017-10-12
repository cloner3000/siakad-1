@extends('app')

@section('title')
Ubah Data Mahasiswa - {{ $mahasiswa -> nama or ''}}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Ubah Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa') }}"> Mahasiswa</a></li>
		<li><a href="{{ route('mahasiswa.show', $mahasiswa -> id) }}"> {{ ucwords(strtolower($mahasiswa -> nama)) }}</a></li>
		<li class="active">Ubah Data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Update Profil</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-3 image_upload">
				<div id="validation-errors"></div>
				{!! Form::open(['url' => url('upload/image'), 'class' => 'form-inline', 'files' => true, 'autocomplete' => 'off', 'id' => 'upload']) !!}
				@include('_partials/_foto', ['foto' => $mahasiswa -> foto, 'default_image' => 'b.png'])
				{!! Form::close() !!}
				{!! config('custom.ketentuan.foto') !!}
			</div>
			<div class="col-sm-9">
				{!! Form::model($mahasiswa, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.update', $mahasiswa->id]]) !!}
				@if(!$hasAccount)
				<div class="form-group">
					{!! Form::label('username', 'Username:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-3 col-xs-6">
						{!! Form::text('username', null, array('class' => 'form-control', 'placeholder' => 'Username')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('password', 'Password:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-3">
						<input class="form-control" placeholder="Password" name="password" type="password" id="password">
					</div>
				</div>
				<hr/>
				@endif
				@include('mahasiswa/partials/_form')
				{!! Form::hidden('foto', null, array('id' => 'foto')) !!}
				<hr/>
				<div class="form-group">
					{!! Form::label('statusMhs', 'Status Mahasiswa:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-10">
						<?php
							foreach(config('custom.pilihan.statusMhs') as $k => $v) 
							{
								echo '<label class="radio-inline">';
								echo '<input type="radio" name="statusMhs" ';
								if(isset($mahasiswa) and $k == $mahasiswa -> statusMhs) echo 'checked="checked" ';
								echo 'value="'. $k .'"> '. $v .'</label>';
							}
						?>
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('noIjazah', 'No. Ijazah:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-4">
						{!! Form::text('noIjazah', null, array('class' => 'form-control', 'placeholder' => 'Nomor Ijazah')) !!}
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('tglIjazah', 'Tanggal Ijazah:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-3">
						{!! Form::text('tglIjazah', null, array('class' => 'form-control', 'placeholder' => 'Tanggal Ijazah', 'data-role' => "datebox", 'data-options' => '{"mode":"flipbox", "useTodayButton":"true"}' )) !!}
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('tglKeluar', 'Tanggal Keluar:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-3">
						{!! Form::text('tglKeluar', null, array('class' => 'form-control', 'placeholder' => 'Tanggal Keluar', 'data-role' => "datebox", 'data-options' => '{"mode":"flipbox", "useTodayButton":"true"}' )) !!}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button class="btn btn-warning btn-flat btn-lg" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
					</div>		
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection				