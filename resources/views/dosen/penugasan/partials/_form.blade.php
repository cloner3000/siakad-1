@section('scripts2')
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/jtsage-datebox.min.js') }}"></script>
<script src="{{ asset('js/jtsage-datebox.i18n.id.utf8.min.js') }}"></script>
<script src="{{ asset('js/jquery.mousewheel.min.js') }}"></script>
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script>
	$(function(){
		$(".chosen-select").chosen({
			no_results_text: "Tidak ditemukan hasil pencarian untuk: ",
			placeholder_text_single: "Pilih program studi terlebih dahulu"
		});
	});  
</script>
@endsection

@section('styles2')
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
<link href="{{ asset('css/jtsage-datebox.min.css') }}" rel="stylesheet">
@endsection

<div class="form-group">
	{!! Form::label('', 'Nama:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::select('dosen_id', $dosen_list, Input::get('dosen'), ['class' => 'form-control chosen-select']) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('tapel_id', 'Tahun Ajaran:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::select('tapel_id', $tapel, null, array('class' => 'form-control', 'placeholder' => 'Tahun Ajaran')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('prodi_id', 'Program Studi:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::select('prodi_id', $prodi, null, array('class' => 'form-control', 'placeholder' => 'Program Studi')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('no_surat_tugas', 'No. Surat Tugas:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::text('no_surat_tugas', null, array('class' => 'form-control', 'placeholder' => 'No. Surat Tugas')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('tgl_surat_tugas', 'Tgl. Surat Tugas:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('tgl_surat_tugas', null, array('class' => 'form-control', 'placeholder' => 'Tanggal Surat Tugas', 'data-role' => "datebox", 'data-options' => '{"mode":"datebox", "useTodayButton":"true"}')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('tmt_surat_tugas', 'TMT Surat Tugas:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('tmt_surat_tugas', null, array('class' => 'form-control', 'placeholder' => 'TMT Surat Tugas', 'data-role' => "datebox", 'data-options' => '{"mode":"datebox", "useTodayButton":"true"}')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('berlaku_sampai', 'Berlaku sampai:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('berlaku_sampai', null, array('class' => 'form-control', 'placeholder' => 'Berlaku sampai', 'data-role' => "datebox", 'data-options' => '{"mode":"datebox", "useTodayButton":"true"}')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('homebase', 'Homebase:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		<?php
			foreach(['Tidak', 'Ya'] as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="homebase" ';
				if(isset($penugasan) and $k == $penugasan -> homebase) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>	