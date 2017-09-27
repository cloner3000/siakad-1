
@section('styles2')
<link href="{{ asset('css/jtsage-datebox.min.css') }}" rel="stylesheet">
<style>
	.radio-inline+.radio-inline, .checkbox-inline+.checkbox-inline {
	margin-top: 0;
	margin-left: 0;
	margin-right: 10px;
	}
	.radio-inline:not(first-child){
	margin-right: 10px;
	}
</style>
@endsection
@section('scripts2')
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/jtsage-datebox.min.js') }}"></script>
<script src="{{ asset('js/jtsage-datebox.i18n.id.utf8.min.js') }}"></script>
<script src="{{ asset('js/jquery.mousewheel.min.js') }}"></script>
@endsection

@if(Auth::user() -> role_id <= 2)
<div class="form-group">
	{!! Form::label('kode', 'Kode Dosen:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2 col-xs-6">
		{!! Form::text('kode', null, array('class' => 'form-control', 'placeholder' => 'Kode Dosen')) !!}
	</div>
</div>
@endif
<div class="form-group">
	{!! Form::label('nama', 'Nama Lengkap:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		{!! Form::text('nama', null, array('class' => 'form-control', 'placeholder' => 'Nama lengkap & Gelar', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('NIDN', 'NIDN:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4 col-xs-9">
		{!! Form::text('NIDN', null, array('class' => 'form-control', 'placeholder' => 'Nomor Induk Dosen Nasional')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('NIP', 'NIP:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4 col-xs-9">
		{!! Form::text('NIP', null, array('class' => 'form-control', 'placeholder' => 'Nomor Induk Pegawai')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('golongan', 'Golongan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<div style="display:inline-block;">
			{!! Form::text('golongan', null, array('class' => 'form-control', 'placeholder' => 'Golongan')) !!}
		</div>
		<div style="display:inline-block;">
			{!! Form::text('kepangkatan', null, array('class' => 'form-control', 'placeholder' => 'Kepangkatan')) !!}
		</div>
	</div>
</div>
<div class="form-group">
	{!! Form::label('NIY', 'NIY:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3 col-xs-6">
		{!! Form::text('NIY', null, array('class' => 'form-control', 'placeholder' => 'Nomor Induk Yayasan')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('NIK', 'No. KTP:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-6">
		{!! Form::text('NIK', null, array('class' => 'form-control', 'placeholder' => 'Nomor Induk Kependudukan (NIK) / No. KTP')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('npwp', 'NPWP:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-6">
		{!! Form::text('npwp', null, array('class' => 'form-control', 'placeholder' => 'Nomor Pokok Wajib Pajak')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('tmpLahir', 'TTL:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<div style="display:inline-block;">
			{!! Form::text('tmpLahir', null, array('class' => 'form-control', 'placeholder' => 'Tempat Lahir')) !!}
		</div>
		<div style="display:inline-block;">
			{!! Form::text('tglLahir', null, array('class' => 'form-control', 'placeholder' => 'Tanggal Lahir', 'data-role' => "datebox", 'data-options' => '{"mode":"datebox", "useTodayButton":"true"}')) !!}
		</div>
	</div>
</div>
<div class="form-group">
	{!! Form::label('jenisKelamin', 'Jenis Kelamin:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		<?php
			foreach(config('custom.pilihan.jenisKelamin') as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="jenisKelamin" ';
				if(isset($dosen) and $k == $dosen -> jenisKelamin) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	{!! Form::label('agama', 'Agama:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<?php
			foreach(config('custom.pilihan.agama') as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="agama" ';
				if(isset($dosen) and $k == $dosen -> agama) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	{!! Form::label('statusSipil', 'Status Sipil:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		<?php
			foreach(config('custom.pilihan.statusSipil') as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="statusSipil" ';
				if(isset($dosen) and $k == $dosen -> statusSipil) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	{!! Form::label('alamat', 'Alamat:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-6">
		{!! Form::textarea('alamat', null, array('class' => 'form-control', 'rows' => '5')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('telp', 'Telepon:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::text('telp', null, array('class' => 'form-control', 'placeholder' => 'Nomor Telepon/HP')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('email', 'Email:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::email('email', null, array('class' => 'form-control', 'placeholder' => 'Email')) !!}
	</div>
</div>

@if(Auth::user() -> role_id <= 2)
<div class="form-group">
	{!! Form::label('jabatan', 'Jabatan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-6">
		{!! Form::text('jabatan', null, array('class' => 'form-control', 'placeholder' => 'Jabatan')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('statusDosen', 'Status:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<?php
			foreach(config('custom.pilihan.statusDosen') as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="statusDosen" ';
				if(isset($dosen) and $k == $dosen -> statusDosen) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>	
<div class="form-group">
	{!! Form::label('statusKepegawaian', 'Status Kepeg.:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<?php
			foreach(config('custom.pilihan.statusKepegawaian') as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="statusKepegawaian" ';
				if(isset($dosen) and $k == $dosen -> statusKepegawaian) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>		
@endif