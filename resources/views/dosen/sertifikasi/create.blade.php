@extends('app')

@section('title')
Tambah Sertifikasi Dosen
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Input Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/dosen/') }}"> Dosen</a></li>
		<li><a href="{{ url('/dosen/' . $dosen -> id) }}"> {{ $dosen -> nama }}</a></li>
		<li><a href="{{ url('/dosen/' . $dosen -> id . '/sertifikasi') }}"> Sertifikasi</a></li>
		<li class="active">Input Data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Tambah Sertifikasi Dosen</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model(new Siakad\DosenSertifikasi, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['dosen.sertifikasi.store', $dosen -> id]]) !!}
				@include('dosen/sertifikasi/partials/_form', ['btn_type' => 'btn-primary'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection