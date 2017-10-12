@extends('app')

@section('title')
Input data Komponen Penilaian
@endsection

@section('header')
<section class="content-header">
	<h1>
		Komponen Penilaian
		<small>Input data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/matkul/tapel/' . $matkul_tapel_id . '/nilai') }}"> Nilai</a></li>
		<li><a href="{{ url('/matkul/tapel/' . $matkul_tapel_id . '/jenisnilai/pilih') }}"> Komponen Penilaian</a></li>
		<li class="active">Input data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Input data</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model(new Siakad\JenisNilai, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['jenisnilai.store', $matkul_tapel_id]]) !!}
				@include('jenisnilai/partials/_form')
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection