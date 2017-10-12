@extends('app')

@section('title')
Input Data Tahun Akademik
@endsection

@section('header')
<section class="content-header">
	<h1>
		Tahun Akademik
		<small>Input Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/tapel') }}"> Tahun Akademik</a></li>
		<li class="active">Input Data Tahun Akademik</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Input Data Tahun Akademik</h3>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\Tapel, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['tapel.store']]) !!}
		@include('tapel/partials/_form', ['submit_text' => 'Simpan'])
		{!! Form::close() !!}
	</div>
</div>
@endsection