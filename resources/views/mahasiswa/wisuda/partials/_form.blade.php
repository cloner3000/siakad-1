
@section('scripts2')
<script src="{{ asset('/js/jquery.inputmask.bundle.min.js') }}"></script>
<script>
	$(function(){
		$(".date").inputmask("dd-mm-yyyy",{"placeholder":"dd-mm-yyyy"});
		$(".year").inputmask("y",{"placeholder":"yyyy"});
	});
</script>
@endsection

<div class="form-group">
	{!! Form::label('nama', 'Nama:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('nama', null, array('class' => 'form-control', 'placeholder' => 'Nama Jadwal Wisuda', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('tanggal', 'Tanggal', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		<?php $tanggal = isset($wisuda -> tanggal) ? date('d-m-Y', strtotime($wisuda -> tanggal)) : null; ?>
		{!! Form::text('tanggal', $tanggal, array('class' => 'form-control date', 'placeholder' => 'Tanggal Wisuda', 'required' => 'required')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('SKYudisium', 'No. SK:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::text('SKYudisium', null, array('class' => 'form-control', 'placeholder' => 'Nomor SK Yudisium', 'required' => 'required')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('tglSKYudisium', 'Tanggal SK:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('tglSKYudisium', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal SK Yudisium', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('daftar', 'Pendaftaran peserta:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-5">
		<?php
			foreach(['y' => 'Buka', 'n' => 'Tutup'] as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="daftar" ';
				if(isset($wisuda) and $k == $wisuda -> daftar) echo 'checked="checked" ';
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