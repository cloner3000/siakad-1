@extends('app')

@section('title')
Impor Data
@endsection

@section('header')
<section class="content-header">
	<h1>
		Impor Data
		<small> Mahasiswa</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Impor Data Mahasiswa</li>
	</ol>
</section>
@endsection

@section('content')
<a href="{{ url('/tpl/mahasiswa_tpl.xlsx') }}" class="btn btn-success btn-flat"><i class="fa fa-download"></i> Download Template Data Mahasiswa</a>
@foreach($prodi as $p)
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Import Data Mahasiswa {{ $p -> singkatan }}</h3>
	</div>
	<div class="box-body">
		{!! Form::open(array('url'=>'import/mahasiswa', 'method'=>'POST', 'files'=>true, 'class' => 'form-inline')) !!}
		{!! Form::file('excel', ['class' => 'form-control']) !!}
		{!! Form::hidden('prodi_id', $p -> id) !!}
		{!! Form::submit('Submit', array('class'=>'btn btn-primary btn-flat')) !!}
		{!! Form::close() !!}
	</div>
</div>
@endforeach
@endsection																												