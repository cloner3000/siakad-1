@extends('app')

@section('title')
Ubah data Tahun Akademik
@endsection

@section('header')
<section class="content-header">
	<h1>
		Tahun Akademik
		<small>Ubah Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/tapel') }}"> Tahun Akademik</a></li>
		<li class="active">Ubah Data Tahun Akademik</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Ubah Data Tahun Akademik</h3>
	</div>
	<div class="box-body">
		{!! Form::model($tapel, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['tapel.update', $tapel ->id]]) !!}
		<input type="hidden" name="id" value="{{ $tapel  -> id }}"/>
		@include('tapel/partials/_form', ['submit_text' => 'Simpan'])
		{!! Form::close() !!}
	</div>
</div>
@endsection