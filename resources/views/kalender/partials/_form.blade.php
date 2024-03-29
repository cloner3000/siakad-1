<style>
	.inline{
	display: inline-block;
	}
</style>
<div class="form-group">
	{!! Form::label('mulai', 'Mulai:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-8">
		{!! Form::select('mulai1', array_combine(range(1, 31), range(1, 31)), (isset($agenda) ? $agenda->mulai1 : null), array('class' => 'form-control inline', 'style' => 'width: 70px')) !!}
		{!! Form::select('mulai2', config('custom.bulan'), (isset($agenda) ? $agenda->mulai2 : null), array('class' => 'form-control inline', 'style' => 'width: 120px;')) !!}
		<input class="form-control inline" name="mulai3" type="number" min="2000" max="3000" value="{{ $agenda->mulai3 or '' }}" style="width: 120px;" placeholder="Tahun">
	</div>
</div>

<div class="form-group">
	{!! Form::label('sampai', 'Sampai:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-8">
		{!! Form::select('sampai1', array_combine(range(1, 31), range(1, 31)), (isset($agenda) ? $agenda->sampai1 : null), array('class' => 'form-control inline', 'style' => 'width: 70px')) !!}
		{!! Form::select('sampai2', config('custom.bulan'), (isset($agenda) ? $agenda->sampai2 : null), array('class' => 'form-control inline', 'style' => 'width: 120px;')) !!}
		<input class="form-control inline" name="sampai3" type="number" min="2000" max="3000" value="{{ $agenda->sampai3 or '' }}" style="width: 120px;" placeholder="Tahun">
	</div>
</div>

<div class="form-group">
	{!! Form::label('jenis_kegiatan', 'Jenis Kegiatan:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::select('jenis_kegiatan', config('custom.pilihan.jenisKegiatan'), (isset($agenda) ? $agenda->jenis_kegiatan : null), array('class' => 'form-control')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('kegiatan', 'Kegiatan:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-10">
		{!! Form::textarea('kegiatan', (isset($agenda) ? $agenda->kegiatan : null), array('class' => 'form-control', 'placeholder' => 'Nama kegiatan', 'required' => 'required', 'rows' => '5')) !!}
	</div>
</div>
	
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
		<button class="btn btn-primary" type="submit"><i class="fa fa-floppy-o"></i> {{ $submit_text }}</button>
	</div>		
</div>				