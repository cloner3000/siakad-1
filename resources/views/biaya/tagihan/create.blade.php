@extends('app')

@section('title')
Buat Tagihan
@endsection

@section('styles')
<style>
	.inline-select{
	margin-right: 5px;
	display:inline-block;
	width: auto;
	}
</style>
@endsection

@section('scripts2')
<script src="{{ asset('/js/jquery.inputmask.bundle.min.js') }}"></script>
<script>
	$(function(){
		$(".curr").inputmask('999.999.999', { numericInput: true, removeMaskOnSubmit: true });
	});
	
	$(document).on('change', '.target', function(){
		var mod = $(this).val();
		$('.mod').addClass('hidden');
		$('.' + mod).removeClass('hidden');
	});
	
	$(document).on('change', '.jenis_biaya', function(){
		var mod = $(this).val().split('.');
		$('.mod2').addClass('hidden');
		if(mod[1] == 3) $('.bulan').removeClass('hidden');
	});
</script>
@endsection

@section('content')
<h2>Pembayaran</h2>
{!! Form::model(new Siakad\Biaya, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['biaya.tagihan.store']]) !!}
<div class="form-group">
	{!! Form::label('tapel_id', 'Tahun Akademik:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-10">
		{!! Form::select('tapel_id', $tapel, $active, array('class' => 'form-control inline', 'style' => 'width: auto;')) !!}
	</div>
</div>
<div class="form-group">
	<label for="target" class="col-sm-2 control-label">Tagihan untuk:</label>
	<div class="col-sm-6">
		{!! Form::select('target', $target, null, array('class' => 'target form-control inline-select')) !!}
		{!! Form::select('angkatan', $angkatan, null, array('class' => 'angkatan mod form-control inline-select hidden')) !!}
		{!! Form::select('semester', $semester, null, array('class' => 'semester mod form-control inline-select hidden')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('jenis_biaya', 'Pembayaran:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::select('jenis_biaya', $jenis, null, array('class' => 'jenis_biaya form-control')) !!}
	</div>
</div>
<div class="form-group bulan hidden mod2">
	{!! Form::label('periode_bulan', 'Bulan:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-10">
		{!! Form::select('p_bulan', config('custom.bulan'), date('m'), array('class' => 'form-control inline-select', 'style' => 'width: 120px;')) !!}
		<input class="form-control inline-select" name="p_tahun" type="number" min="2000" max="3000" value="{{ date('Y') }}" style="width: 90px;" placeholder="Tahun">
	</div>
</div>
{!! csrf_field() !!}
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
		<button class="btn btn-primary" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>	
{!! Form::close() !!}
@endsection																									