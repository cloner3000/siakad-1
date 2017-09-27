
@push('scripts')
<script src="{{ asset('/js/jquery.inputmask.bundle.min.js') }}"></script>
<script>
	$(function(){
		$(".date").inputmask("dd-mm-yyyy",{"placeholder":"dd-mm-yyyy"});
		$(".year").inputmask("y",{"placeholder":"yyyy"});
	});
</script>
@endpush

<div class="form-group">
	{!! Form::label('nama', 'Nama :', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::text('nama', null, array('class' => 'form-control', 'placeholder' => 'Nama Periode PMB', 'required' => 'required', 'autofocus' => 'autofocus')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('ketua', 'Ketua PMB :', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::text('ketua', null, array('class' => 'form-control', 'placeholder' => 'Ketua PMB', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('mulai', 'Tanggal Mulai :', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-3">
		<?php $mulai = isset($pmb -> mulai) ? date('d-m-Y', strtotime($pmb -> mulai))  :null; ?>
		{!! Form::text('mulai', $mulai, array('class' => 'form-control date', 'placeholder' => 'Tanggal Mulai', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('selesai', 'Tanggal Selesai :', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-3">
		<?php $selesai = isset($pmb -> selesai) ? date('d-m-Y', strtotime($pmb -> selesai))  :null; ?>
		{!! Form::text('selesai', $selesai, array('class' => 'form-control date', 'placeholder' => 'Tanggal Selesai', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('tujuan', 'Jalur :', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::textarea('tujuan', null, array('class' => 'form-control', 'placeholder' => 'Program Tujuan (pisahkan dengan tanda koma ",")', 'required' => 'required', 'rows' => '3')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('buka', 'Status :', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-8">
		<label class="radio-inline">
			{!! Form::radio('buka', 'y') !!} Buka
		</label>
		<label class="radio-inline">
			{!! Form::radio('buka', 'n') !!} Ditutup
		</label>
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-9">
		<button class="btn {{ $btn_type }} btn-flat" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
		</div>		
	</div>				