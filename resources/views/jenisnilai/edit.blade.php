@extends('app')

@section('title')
Edit Komponen Penilaian
@endsection

@section('header')
<section class="content-header">
	<h1>
		Komponen Penilaian
		<small>Edit data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/matkul/tapel/' . $matkul_tapel_id . '/nilai') }}"> Nilai</a></li>
		<li><a href="{{ url('/matkul/tapel/' . $matkul_tapel_id . '/jenisnilai/pilih') }}"> Komponen Penilaian</a></li>
		<li class="active">Edit data</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Komponen Penilaian</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model($jenis, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['jenisnilai.update', $matkul_tapel_id, $jenis->id]]) !!}
				<div class="form-group">
					{!! Form::label('nama', 'Nama:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-4">
						{!! Form::text('nama', null, array('class' => 'form-control', 'placeholder' => 'Nama komponen penilaian', 'required' => 'required')) !!}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
					</div>		
				</div>	
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection