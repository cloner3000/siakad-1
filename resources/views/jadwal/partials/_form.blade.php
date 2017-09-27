@section('scripts2')
<script src="{{ asset('/js/jquery.inputmask.bundle.min.js') }}"></script>
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script>
	$(function(){
		$(".time").inputmask("hh:mm",{"placeholder":"hh:mm"});
	});
	
	$(function(){
		$(".chosen-select").chosen({
			no_results_text: "Tidak ditemukan hasil pencarian untuk: ",
			placeholder_text_single: "Pilih program studi terlebih dahulu"
		});
	});  
</script>
@endsection
@push('styles')
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
	.loader{
	color: #f00900;
	position: absolute;
	z-index: 999;
	top: 10px;
	right: 50%;
	display: none;
	}
</style>
@endpush
<div class="form-group">
	{!! Form::label('matkul_tapel_id', 'Mata Kuliah:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-9">
		{!! Form::select('matkul_tapel_id', $matkul, Input::get('id', null), array('class' => 'form-control chosen-select', 'placeholder' => 'Pilih Kelas Perkuliahan')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('ruang_id', 'Ruang:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::select('ruang_id', $ruang, null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('hari', 'Hari:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::select('hari', config('custom.hari'), Input::get('hari', null), array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('jam_mulai', 'Mulai Jam:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('jam_mulai', Input::get('jam_mulai', null), array('class' => 'form-control time', 'placeholder' => 'Jam mulai mengajar')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('jam_selesai', 'Sampai Jam:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('jam_selesai', Input::get('jam_selesai', null), array('class' => 'form-control time', 'placeholder' => 'Jam selesai mengajar')) !!}
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
		<button class="btn btn-flat {{ $btn_type }}" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>					