@extends('pmb/peserta/layout')

@section('title')
Formulir Pendaftaran Mahasiswa Baru Online Tahun {{ $pmb -> nama }}
@endsection

@push('styles')
<style>
	.panel-body a{
	color: #337ab7;
	}
	.panel-body a:hover{
	text-decoration: underline;
	}
	img{
	max-width: 100%;
	}
	
	label.btn{
	border-radius: 0px 4px 0px 0px !important;
	}
</style>
@endpush

@section('content')
<div class="container" style="margin-bottom: 30px;">
	<div class="row">
		<div class="col-xs-12">
			<div class="page-header">
				<h2>Formulir Pendaftaran Mahasiswa Baru Online Tahun {{ $pmb -> nama }}</h2>
			</div>
			<div class="row">
				@include('pmb/peserta/_foto_dependency')
				<div class="col-sm-3">
					<div class="row">
						<div class="col-sm-12">
							{!! Form::open(['url' => url('/upload/image'), 'class' => 'form-inline upload_form', 'files' => true, 'autocomplete' => 'off', 'id' => 'foto_upload_form']) !!}
							@include('pmb/peserta/_foto', [
							'form' => 'foto_upload_form', 
							'file_selector' => 'foto_upload_button', 
							'target' => 'foto', 
							'message' => 'Upload foto...',
							'resized_width' => 300,
							'resized_height' => 400,
							'current_image' => '',
							'default_image' => "/images/a.png"
							])
							{!! Form::close() !!}
						</div>
						<div class="col-sm-12">
							{!! Form::open(['url' => url('/upload/image'), 'class' => 'form-inline upload_form', 'files' => true, 'autocomplete' => 'off', 'id' => 'slip_upload_form']) !!}
							@include('pmb/peserta/_foto', [
							'form' => 'slip_upload_form', 
							'file_selector' => 'slip_upload_button', 
							'target' => 'slip', 
							'message' => 'Upload slip pembayaran ...',
							'resized_width' => 600,
							'resized_height' => 400,
							'current_image' => '',
							'default_image' => '/images/slip.jpg'
							])
							{!! Form::close() !!}
						</div>
					</div>
				</div>
				
				<div class="col-sm-9">
					{!! Form::model(new Siakad\PmbPeserta, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['pmb.peserta.store']]) !!}
					@include('pmb/peserta/_form', ['submit_text' => 'Simpan'])
					@if(null !== Input::get('key'))
					{!! Form::hidden('key', Input::get('key')) !!}
					@endif
					{!! Form::hidden('pmb_id', $pmb -> id) !!}
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection	