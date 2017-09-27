<div class="form-group">
	{!! Form::label('nama', 'Nama:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::text('nama', null, array('class' => 'form-control', 'placeholder' => 'Nama komponen penilaian', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('bobot', 'Bobot:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
		<div class="input-group">
			<input class="form-control" placeholder="Bobot nilai" required="required" min="1" max="100" name="bobot" type="number" id="bobot" value="{{ $type-> bobot or '' }}">
			<span class="input-group-addon">%</span>
		</div>
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
		<button class="btn btn-primary btn-flat" type="submit"><i class="fa fa-floppy-o"></i>  Simpan</button>
	</div>		
</div>				