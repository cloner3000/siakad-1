@section('scripts')
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/jtsage-datebox.min.js') }}"></script>
<script src="{{ asset('js/jtsage-datebox.i18n.id.utf8.min.js') }}"></script>
<script src="{{ asset('js/jquery.mousewheel.min.js') }}"></script>
@endsection
@section('styles')
<link href="{{ asset('css/jtsage-datebox.min.css') }}" rel="stylesheet">
@endsection

<div class="form-group">
	{!! Form::label('judul', 'Judul Skripsi:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-10">
		<p class="form-control-static">{{ $skripsi -> judul }}</p>
	</div>
</div>
<div class="form-group">
	{!! Form::label('dosen_id', 'Dosen Pembimbing:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-5">
		@foreach($skripsi -> pembimbing as $pb)
		<p class="form-control-static">{{ $pb -> nama }} </p>
		@endforeach
	</div>
</div>
<div class="form-group">
	{!! Form::label('tglBimbingan', 'Tanggal:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::text('tglBimbingan', null, array('class' => 'form-control', 'placeholder' => 'Tanggal Bimbingan', 'required' => 'required', 'data-role' => "datebox", 'data-options' => '{"mode":"flipbox", "useTodayButton":"true"}' )) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('tentang', 'Perihal:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-9">
		{!! Form::textarea('tentang', null, array('class' => 'form-control', 'placeholder' => 'Perihal yang dikonsultasikan', 'rows' => '3')) !!}
	</div>
</div>
<div class="form-group">
{!! Form::label('disetujui', 'Disetujui:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-8">
<?php
foreach(['Y' => 'Ya', 'N' => 'Tidak'] as $k => $v) 
{
echo '<label class="radio-inline">';
echo '<input type="radio" name="disetujui" ';
if(isset($bimbingan) and $k == $bimbingan -> disetujui) echo 'checked="checked" ';
echo 'value="'. $k .'"> '. $v .'</label>';
}
?>
</div>
</div>
<div class="form-group">
<div class="col-sm-offset-2 col-sm-9">
<button class="btn {{ $btn_type }} btn-flat" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
</div>		
</div>	