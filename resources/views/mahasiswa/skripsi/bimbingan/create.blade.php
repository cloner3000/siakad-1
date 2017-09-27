@extends('app')

@section('title')
Input Data Bimbingan Skripsi
@endsection

@section('header')
<section class="content-header">
	<h1>
		Skripsi
		<small>Input Data Bimbingan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa') }}"> Mahasiswa</a></li>
		<li><a href="{{ url('/mahasiswa/' . $skripsi -> pengarang -> id) }}"> {{ $skripsi -> pengarang -> nama }}</a></li>
		<li><a href="{{ url('/skripsi/' . $skripsi -> id) }}"> Skripsi</a></li>
		<li class="active">Input Data Bimbingan</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Input Data Bimbingan Skripsi</h3>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\BimbinganSkripsi, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.skripsi.bimbingan.store', $skripsi -> id]]) !!}
		@include('mahasiswa/skripsi/bimbingan/partials/_form', ['btn_type' => 'btn-primary'])
		{!! Form::close() !!}
	</div>
</div>
@endsection