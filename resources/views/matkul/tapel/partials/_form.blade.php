@push('scripts')
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script>
	$(function(){
		$(".chosen-select").chosen({
			no_results_text: "Tidak ditemukan hasil pencarian untuk: ",
			placeholder_text_single: "Pilih program studi terlebih dahulu"
		});
	});  
</script>
@endpush
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
	{!! Form::label('tapel_id', 'Semester:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::select('tapel_id', $semester, $aktif, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('kurikulum_matkul_id', 'Mata Kuliah:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-7" style="position: relative">
		<i class="fa fa-spinner fa-spin loader loader-matkul"></i>
		{!! Form::select('kurikulum_matkul_id', $matkul, null, array('class' => 'form-control chosen-select')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('kelas2', 'Kelas:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-1">
		{!! Form::select('kelas2', $kelas2, null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('kuota', 'Kuota:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('kuota', null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('bahasan', 'Bahasan:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-8">
		{!! Form::textarea('bahasan', null, array('class' => 'form-control', 'rows' => 3)) !!}
	</div>
	</div>
	<div class="form-group">
		{!! Form::label('dosen_id', 'Dosen:', array('class' => 'col-sm-2 control-label')) !!}
		<div class="col-sm-4">
			{!! Form::select('dosen_id', $dosen, null, array('class' => 'form-control chosen-select')) !!}
		</div>
	</div>
	<div class="form-group">
	<div class="col-sm-offset-2 col-sm-8">
		<button class="btn {{ $btn_type or '' }} btn-flat" type="submit"><i class="fa fa-floppy-o"></i>  {{ $submit_text }}</button>
	</div>		
</div>						