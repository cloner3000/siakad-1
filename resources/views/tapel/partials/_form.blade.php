@section('styles')
<link href="{{ asset('css/jtsage-datebox.min.css') }}" rel="stylesheet">
@endsection

@section('scripts2')
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/jtsage-datebox.min.js') }}"></script>
<script src="{{ asset('js/jquery.mousewheel.min.js') }}"></script>
@endsection

<div class="form-group">
	{!! Form::label('tahun', 'Tahun:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::select('tahun', array_combine($r = range($d = (date('Y') - 5), ($d + 10)), $r), null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('semester', 'Semester:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		<label class="radio-inline">
			{!! Form::radio('semester', 'Ganjil') !!} Ganjil
		</label>
		<label class="radio-inline">
			{!! Form::radio('semester', 'Genap') !!} Genap
		</label>
	</div>
</div>
<div class="form-group">
	{!! Form::label('mulai', 'Tanggal Mulai TA:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::text('mulai', null, array('class' => 'form-control', 'placeholder' => 'Tanggal Mulai Tahun Akademik', 'data-role' => "datebox", 'data-options' => '{"mode":"flipbox"}' )) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('selesai', 'Tanggal Selesai TA:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::text('selesai', null, array('class' => 'form-control', 'placeholder' => 'Tanggal Selesai Tahun Akademik', 'data-role' => "datebox", 'data-options' => '{"mode":"flipbox"}' )) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('batasRegistrasi', 'Batas Daftar Ulang:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::text('batasRegistrasi', null, array('class' => 'form-control', 'placeholder' => 'Batas Daftar Ulang', 'data-role' => "datebox", 'data-options' => '{"mode":"flipbox"}' )) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('mulaiKrs', 'Tanggal Mulai KRS:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::text('mulaiKrs', null, array('class' => 'form-control', 'placeholder' => 'Tanggal Mulai Pengisian Kartu Rencana Studi', 'data-role' => "datebox", 'data-options' => '{"mode":"flipbox"}' )) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('selesaiKrs', 'Tanggal Selesai KRS:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::text('selesaiKrs', null, array('class' => 'form-control', 'placeholder' => 'Tanggal Selesai Pengisian Kartu Rencana Studi', 'data-role' => "datebox", 'data-options' => '{"mode":"flipbox"}' )) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('aktif', 'Status:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		<label class="radio-inline">
			{!! Form::radio('aktif', 'y') !!} Aktif
		</label>
		<label class="radio-inline">
			{!! Form::radio('aktif', 'n') !!} Tidak aktif
		</label>
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
		<button class="btn btn-primary" type="submit"><i class="fa fa-floppy-o"></i>  {{ $submit_text }}</button>
	</div>		
</div>				