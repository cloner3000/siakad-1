<div class="form-group">
	{!! Form::label('nama', 'Nama:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('nama', null, array('class' => 'form-control', 'placeholder' => 'Nama ruang kuliah', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('gedung', 'Gedung:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('gedung', null, array('class' => 'form-control', 'placeholder' => 'Gedung')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('kapasitas', 'Kapasitas:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('kapasitas', null, array('class' => 'form-control', 'placeholder' => 'Kapasitas')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('fasilitas', 'Fasilitas:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-6">
		{!! Form::textarea('fasilitas', null, array('class' => 'form-control', 'placeholder' => 'Fasilitas', 'rows' => 3)) !!}
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>	