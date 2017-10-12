@extends('app')

@section('title')
Edit data Skripsi - {{ $skripsi -> judul }} - {{ $skripsi -> pengarang -> NIM }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Skripsi
		<small>Edit Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/skripsi') }}"> Skripsi</a></li>
		<li class="active">Edit Data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit data Skripsi</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-3">		
				{!! Form::open(['url' => url('/upload/image'), 'class' => 'form-inline', 'files' => true, 'autocomplete' => 'off', 'id' => 'upload']) !!}
				@include('_partials/_foto', ['foto' => $skripsi -> cover, 'default_image' => 's.png', 'label' => 'Cover Skripsi ....'])
				{!! Form::close() !!}	
			</div>
			<div class="col-sm-9">
				{!! Form::model($skripsi, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['skripsi.update', $skripsi -> id]]) !!}				
				<div class="form-group">
					{!! Form::label('nama', 'Nama:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-5">
						<p class="form-control-static">{{ $mahasiswa -> nama }}</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">NIM:</label>
					<div class="col-sm-9">
						<p class="form-control-static">{{ $mahasiswa -> NIM }}</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">PRODI:</label>
					<div class="col-sm-9">
						<p class="form-control-static">{{ $mahasiswa -> prodi -> strata }} {{ $mahasiswa -> prodi -> nama }} {{ $mahasiswa -> kelas -> nama }}</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Pembimbing:</label>
					<div class="col-sm-9">
						<div style="display: inline-block;">
							{!! Form::select('pembimbing1', $dosen, $skripsi -> pembimbing1, ['class' => 'form-control chosen-select']) !!}
						</div>
						<div style="display: inline-block;">
							{!! Form::select('pembimbing2', $dosen, $skripsi -> pembimbing2, ['class' => 'form-control chosen-select']) !!}
						</div>
					</div>
				</div>
				<div class="form-group has-feedback{{ $errors->has('judul') ? ' has-error' : '' }}">
					{!! Form::label('judul', 'Judul Skripsi:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-9">
						{!! Form::textarea('judul', null, array('class' => 'form-control', 'placeholder' => 'Judul Skripsi', 'rows' => '3')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('abstrak', 'Abstrak:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-9">
						{!! Form::textarea('abstrak', null, array('class' => 'form-control', 'placeholder' => 'Abstrak Skripsi', 'rows' => '5')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('softcopy', 'File:', array('class' => 'col-sm-3 control-label')) !!}
					<div class="col-sm-5">
						<input class="upload" name="softcopy" id="softcopy" type="file">
						<label for="softcopy" class="btn btn-danger btn-flat"><i class="fa fa-file"></i> <span>Pilih file...</span></label>
						@if(isset($skripsi -> file))
						<a href="{{ route('skripsi.file', $skripsi -> id) }}?token={{ csrf_token() }}" class="btn btn-info btn-flat"><i class="fa fa-download"></i> Download</a>
						@endif			
					</div>
				</div>
				{!! Form::hidden('cover', null, ['id' => 'foto']) !!}
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-9">
						<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
					</div>		
				</div>	
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script>
	$(function(){
		$(".chosen-select").chosen({no_results_text: "Tidak ditemukan hasil pencarian untuk: "});
	});  
	var inputs = document.querySelectorAll( '.upload' );
	Array.prototype.forEach.call( inputs, function( input )
	{
		var label	 = input.nextElementSibling,
		labelVal = label.innerHTML;
		
		input.addEventListener( 'change', function( e )
		{
			var fileName = '';
			if( this.files && this.files.length > 1 )
			fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
			else
			fileName = e.target.value.split( '\\' ).pop();
			
			if( fileName )
			label.querySelector( 'span' ).innerHTML = fileName;
			else
			label.innerHTML = labelVal;
		});
	});
</script>
@endsection
@section('styles')
<link rel="stylesheet" href="{{ asset('/css/chosen.min.css') }}">
<style>
	.chosen-container{
	font-size: inherit;
	}
	.chosen-single{
	padding: 6px 10px !important;
	box-shadow: none !important;
	border-color: #d2d6de !important;
	background: white !important;
	height: 34px !important;
	border-radius: 0px !important;
	}
	.chosen-drop{
	border-color: #d2d6de !important;	
	box-shadow: none;
	}
</style>
@endsection				