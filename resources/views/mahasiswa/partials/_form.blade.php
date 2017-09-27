
@section('scripts2')
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/jtsage-datebox.min.js') }}"></script>
<script src="{{ asset('js/jtsage-datebox.i18n.id.utf8.min.js') }}"></script>
<script src="{{ asset('js/jquery.mousewheel.min.js') }}"></script>
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script>
	$(function(){
		$(".chosen-select").chosen({no_results_text: "Tidak ditemukan hasil pencarian untuk: "});
	});
</script>
@endsection
@section('styles2')
<link href="{{ asset('css/jtsage-datebox.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('/css/chosen.min.css') }}">
<style>
	.form-horizontal .control-label {
	text-align: left;
	}
	
	.radio-inline+.radio-inline, .checkbox-inline+.checkbox-inline {
	margin-top: 0;
	margin-left: 0;
	margin-right: 10px;
	}
	.radio-inline:not(first-child){
	margin-right: 10px;
	}
	
	.span-block{
	display:block; width: 200px; float: left; margin-right: 2px;
	}
	
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
</style>
@endsection

<div class="form-group has-feedback{{ $errors->has('NIM') ? ' has-error' : '' }}">
	{!! Form::label('NIM', 'NIM:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::text('NIM', null, array('class' => 'form-control', 'placeholder' => 'Nomor Induk Mahasiswa', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('angkatan', 'Angkatan:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('angkatan', null, array('class' => 'form-control', 'placeholder' => 'Angkatan', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('NIRM', 'NIRM:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::text('NIRM', null, array('class' => 'form-control', 'placeholder' => 'Nomor Induk Registrasi Mahasiswa')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('NIRL', 'NIRL:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-9">
		<div  class="span-block">
			{!! Form::text('NIRL1', null, array('class' => 'form-control', 'placeholder' => 'NIRL 1')) !!}
		</div>
		<div  class="span-block">
			{!! Form::text('NIRL2', null, array('class' => 'form-control', 'placeholder' => 'NIRL 2')) !!}
		</div>
	</div>
</div>
<div class="form-group has-feedback{{ $errors->has('nama') ? ' has-error' : '' }}">
	{!! Form::label('nama', 'Nama:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-8">
		{!! Form::text('nama', null, array('class' => 'form-control', 'placeholder' => 'Nama lengkap', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('mukim', 'Status Tinggal:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-10 col-xs-10">
		<?php
			foreach(config('custom.pilihan.mukim') as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="mukim" ';
				if(isset($mahasiswa) and $k == $mahasiswa -> mukim) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
			?>
		</div>
</div>
<div class="form-group">
	{!! Form::label('jenisPembayaran', 'Pembiayaan:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-10 col-xs-10">
		<?php
			foreach(config('custom.pilihan.jenisPembayaran') as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="jenisPembayaran" ';
				if(isset($mahasiswa) and $k == $mahasiswa -> jenisPembayaran) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	{!! Form::label('dosen_wali', 'Dosen Wali:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::select('dosen_wali', $dosen, null, array('class' => 'form-control chosen-select', 'data-placeholder' => 'Pilih Dosen Wali')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('prodi_id', 'Prodi:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-10 col-xs-10">
		<?php
			foreach($prodi as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="prodi_id" ';
				if(isset($mahasiswa) and $k == $mahasiswa -> prodi_id) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group has-feedback">
	{!! Form::label('tapelMasuk', 'Mulai Masuk:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::select('tapelMasuk', $tapel, null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('kelasMhs', 'Program:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::select('kelasMhs', $kelas, null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('semesterMhs', 'Semester:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::select('semesterMhs', array_combine($r = range(1,20), $r), null, array('class' => 'form-control')) !!}
	</div>
</div>
<hr/>
<div class="form-group has-feedback{{ $errors->has('jenisKelamin') ? ' has-error' : '' }}">
	{!! Form::label('jenisKelamin', 'Jenis Kelamin:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-8">
		<?php
			foreach(config('custom.pilihan.jenisKelamin') as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="jenisKelamin" ';
				if(isset($mahasiswa) and $k == $mahasiswa -> jenisKelamin) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group has-feedback{{ ($errors->has('tmpLahir') or $errors->has('tglLahir')) ? ' has-error' : '' }}">
	{!! Form::label('tmpLahir', 'TTL:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-10">
		<div  class="span-block">
			{!! Form::text('tmpLahir', null, array('class' => 'form-control', 'placeholder' => 'Tempat Lahir', 'required' => 'required')) !!}
		</div>
		<div  class="span-block">
			{!! Form::text('tglLahir', null, array('class' => 'form-control', 'placeholder' => 'Tanggal Lahir', 'required' => 'required', 'data-role' => "datebox", 'data-options' => '{"mode":"datebox", "useTodayButton":"true"}' )) !!}
		</div>
	</div>
</div>
<div class="form-group has-feedback{{ $errors->has('NIK') ? ' has-error' : '' }}">
	{!! Form::label('NIK', 'No. KTP:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::text('NIK', null, array('class' => 'form-control', 'placeholder' => 'Nomor Induk Kependudukan (No. KTP)', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
{!! Form::label('NPWP', 'NPWP:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-5">
{!! Form::text('NPWP', null, array('class' => 'form-control', 'placeholder' => 'Nomor Pokok Wajib Pajak')) !!}
</div>
</div>
<div class="form-group">
{!! Form::label('statusWrgNgr', 'Status Kw:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-8">
<?php
foreach(config('custom.pilihan.statusWrgNgr') as $k => $v) 
{
echo '<label class="radio-inline">';
echo '<input type="radio" name="statusWrgNgr" ';
if(isset($mahasiswa) and $k == $mahasiswa -> statusWrgNgr) echo 'checked="checked" ';
echo 'value="'. $k .'"> '. $v .'</label>';
}
?>
</div>
</div>
<div class="form-group">
{!! Form::label('wargaNegara', 'Kewarganegaraan:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-5">
{!! Form::select('wargaNegara', $negara, null, array('class' => 'form-control chosen-select', 'data-placeholder' => 'Pilih Negara')) !!}
</div>
</div>
<div class="form-group has-feedback{{ $errors->has('agama') ? ' has-error' : '' }}">
{!! Form::label('agama', 'Agama:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-10">
<?php
foreach(config('custom.pilihan.agama') as $k => $v) 
{
echo '<label class="radio-inline">';
echo '<input type="radio" name="agama" ';
if(isset($mahasiswa) and $k == $mahasiswa -> agama) echo 'checked="checked" ';
echo 'value="'. $k .'"> '. $v .'</label>';
}
?>
</div>
</div>
<div class="form-group">
{!! Form::label('statusSipil', 'Status Sipil:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-8">
<?php
foreach(config('custom.pilihan.statusSipil') as $k => $v) 
{
echo '<label class="radio-inline">';
echo '<input type="radio" name="statusSipil" ';
if(isset($mahasiswa) and $k == $mahasiswa -> statusSipil) echo 'checked="checked" ';
echo 'value="'. $k .'"> '. $v .'</label>';
}
?>
</div>
</div>

<div class="form-group has-feedback{{ $errors->has('kelurahan') ? ' has-error' : '' }}">
{!! Form::label('jalan', 'Alamat:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-10">
<div style="display:inline-block;">
{!! Form::text('jalan', null, array('class' => 'form-control', 'placeholder' => 'Jalan', 'style' => 'width: 150px')) !!}
</div>
<div style="display:inline-block;">
{!! Form::text('rt', null, array('class' => 'form-control', 'placeholder' => 'RT', 'style' => 'width: 80px')) !!}
</div>
<div style="display:inline-block;">
{!! Form::text('rw', null, array('class' => 'form-control', 'placeholder' => 'RW', 'style' => 'width: 80px')) !!}
</div>
<div style="display:inline-block;">
{!! Form::text('dusun', null, array('class' => 'form-control', 'placeholder' => 'Dusun / Lingkungan', 'style' => 'width: 150px')) !!}
</div>
<div style="display:inline-block;">
{!! Form::text('kelurahan', null, array('class' => 'form-control', 'placeholder' => 'Desa / Kelurahan', 'style' => 'width: 150px', 'required' => 'required')) !!}
</div>
<div style="display:inline-block;">
{!! Form::select('id_wil', $wilayah, null, array('class' => 'form-control chosen-select', 'data-placeholder' => 'Kecamatan')) !!}
</div>
<div style="display:inline-block;">
{!! Form::text('kodePos', null, array('class' => 'form-control', 'placeholder' => 'Kode Pos', 'style' => 'width: 150px')) !!}
</div>
</div>
</div>

<div class="form-group">
{!! Form::label('transportasi', 'Transportasi:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-5">
{!! Form::select('transportasi', config('custom.pilihan.transportasi'), null, array('class' => 'form-control')) !!}
</div>
</div>
<div class="form-group">
{!! Form::label('telp', 'Telp. Rumah:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-5">
{!! Form::text('telp', null, array('class' => 'form-control', 'placeholder' => 'Nomor Telepon Rumah')) !!}
</div>
</div>
<div class="form-group">
{!! Form::label('hp', 'HP:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-5">
{!! Form::text('hp', null, array('class' => 'form-control', 'placeholder' => 'Nomor HP')) !!}
</div>
</div>
<div class="form-group">
{!! Form::label('email', 'Email:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-5">
<input class="form-control" placeholder="Email" name="email" type="email" id="email" value="{{ $mahasiswa -> email or '' }}">
</div>
</div>
<hr/>
<div class="form-group has-feedback{{ $errors->has('kps') ? ' has-error' : '' }}">
{!! Form::label('kps', 'Terima KPS:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-8">
<?php
foreach(['Y' => 'Ya', 'N' => 'Tidak'] as $k => $v) 
{
echo '<label class="radio-inline">';
echo '<input type="radio" name="kps" ';
if(isset($mahasiswa) and $k == $mahasiswa -> kps) echo 'checked="checked" ';
echo 'value="'. $k .'"> '. $v .'</label>';
}
?>
<span class="help-block">Kartu Perlindungan Sosial</span>
</div>
</div>
<div class="form-group">
{!! Form::label('noKps', 'No. KPS:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-5">
{!! Form::text('noKps', null, array('class' => 'form-control', 'placeholder' => 'Nomor KPS')) !!}
</div>
</div>
<hr/>
<div class="form-group">
{!! Form::label('thSMTA', 'Lulus SLTA:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-10">
<div style="display:inline-block;">
{!! Form::text('thSMTA', null, array('class' => 'form-control year', 'placeholder' => 'Tahun', 'style' => 'width: 80px')) !!}
</div>
<div style="display:inline-block;">
{!! Form::text('jurSMTA', null, array('class' => 'form-control', 'placeholder' => 'Jurusan', 'style' => 'width: 380px')) !!}
</div>
</div>
</div>
<div class="form-group">
{!! Form::label('NISN', 'NISN:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-5">
{!! Form::text('NISN', null, array('class' => 'form-control', 'placeholder' => 'Nomor Induk Siswa Nasional')) !!}
</div>
</div>
<div class="form-group">
{!! Form::label('jalurMasuk', 'Jalur Masuk:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-7">
{!! Form::select('jalurMasuk', config('custom.pilihan.jalurMasuk'), null, array('class' => 'form-control')) !!}
</div>
</div>
<div class="form-group">
{!! Form::label('jenisPendaftaran', 'Jenis Daftar:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-5">
{!! Form::select('jenisPendaftaran', config('custom.pilihan.jenisPendaftaran'), null, array('class' => 'form-control')) !!}
</div>
</div>

<hr/>

<div class="form-group">
{!! Form::label('NIKAyah', 'NIK Ayah:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-4">
{!! Form::text('NIKAyah', null, array('class' => 'form-control', 'placeholder' => 'NIK Ayah')) !!}
</div>
</div>
<div class="form-group">
{!! Form::label('namaAyah', 'Nama Ayah:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-4">
{!! Form::text('namaAyah', null, array('class' => 'form-control', 'placeholder' => 'Nama Ayah')) !!}
</div>
</div>
<div class="form-group">
{!! Form::label('tglLahirAyah', 'Tgl. Lahir Ayah:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-3">
{!! Form::text('tglLahirAyah', null, array('class' => 'form-control', 'placeholder' => 'Tanggal Lahir Ayah', 'data-role' => "datebox", 'data-options' => '{"mode":"datebox", "useTodayButton":"true"}' )) !!}
</div>
</div>
<div class="form-group">
{!! Form::label('pendidikanAyah', 'Pend. Ayah:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-10">
<?php
foreach(config('custom.pilihan.pendidikanOrtu') as $k => $v) 
{
echo '<label class="radio-inline">';
echo '<input type="radio" name="pendidikanAyah" ';
if(isset($mahasiswa) and $k == $mahasiswa -> pendidikanAyah) echo 'checked="checked" ';
echo 'value="'. $k .'"> '. $v .'</label>';
}
?>
</div>
</div>
<div class="form-group">
{!! Form::label('pekerjaanAyah', 'Pekerjaan Ayah:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-10">
<?php
foreach(config('custom.pilihan.pekerjaanOrtu') as $k => $v) 
{
echo '<label class="radio-inline">';
echo '<input type="radio" name="pekerjaanAyah" ';
if(isset($mahasiswa) and $k == $mahasiswa -> pekerjaanAyah) echo 'checked="checked" ';
echo 'value="'. $k .'"> '. $v .'</label>';
}
?>
</div>
</div>	
<div class="form-group">
{!! Form::label('penghasilanAyah', 'Penghasilan Ayah:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-10">
<?php
foreach(config('custom.pilihan.penghasilanOrtu') as $k => $v) 
{
echo '<label class="radio-inline">';
echo '<input type="radio" name="penghasilanAyah" ';
if(isset($mahasiswa) and $k == $mahasiswa -> penghasilanAyah) echo 'checked="checked" ';
echo 'value="'. $k .'"> '. $v .'</label>';
}
?>
</div>
</div>	
<hr/>
<div class="form-group">
{!! Form::label('NIKIbu', 'NIK Ibu:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-4">
{!! Form::text('NIKIbu', null, array('class' => 'form-control', 'placeholder' => 'NIK Ibu')) !!}
</div>
</div>
<div class="form-group has-feedback{{ $errors->has('namaIbu') ? ' has-error' : '' }}">
{!! Form::label('namaIbu', 'Nama Ibu:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-4">
{!! Form::text('namaIbu', null, array('class' => 'form-control', 'placeholder' => 'Nama Ibu', 'required' => 'required')) !!}
</div>
</div>
<div class="form-group">
{!! Form::label('tglLahirIbu', 'Tgl. Lahir Ibu:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-3">
{!! Form::text('tglLahirIbu', null, array('class' => 'form-control', 'placeholder' => 'Tanggal Lahir Ibu', 'data-role' => "datebox", 'data-options' => '{"mode":"datebox", "useTodayButton":"true"}' )) !!}
</div>
</div>
<div class="form-group">
{!! Form::label('pendidikanIbu', 'Pendidikan Ibu:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-10">
<?php
foreach(config('custom.pilihan.pendidikanOrtu') as $k => $v) 
{
echo '<label class="radio-inline">';
echo '<input type="radio" name="pendidikanIbu" ';
if(isset($mahasiswa) and $k == $mahasiswa -> pendidikanIbu) echo 'checked="checked" ';
echo 'value="'. $k .'"> '. $v .'</label>';
}
?>
</div>
</div>
<div class="form-group">
{!! Form::label('pekerjaanIbu', 'Pekerjaan Ibu:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-10">
<?php
foreach(config('custom.pilihan.pekerjaanOrtu') as $k => $v) 
{
echo '<label class="radio-inline">';
echo '<input type="radio" name="pekerjaanIbu" ';
if(isset($mahasiswa) and $k == $mahasiswa -> pekerjaanIbu) echo 'checked="checked" ';
echo 'value="'. $k .'"> '. $v .'</label>';
}
?>
</div>
</div>			
<div class="form-group">
{!! Form::label('penghasilanIbu', 'Penghasilan Ibu:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-10">
<?php
foreach(config('custom.pilihan.penghasilanOrtu') as $k => $v) 
{
echo '<label class="radio-inline">';
echo '<input type="radio" name="penghasilanIbu" ';
if(isset($mahasiswa) and $k == $mahasiswa -> penghasilanIbu) echo 'checked="checked" ';
echo 'value="'. $k .'"> '. $v .'</label>';
}
?>
</div>
</div>		
<hr/>
<div class="form-group">
{!! Form::label('namaWali', 'Nama Wali:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-4">
{!! Form::text('namaWali', null, array('class' => 'form-control', 'placeholder' => 'Nama Wali')) !!}
</div>
</div>
<div class="form-group">
{!! Form::label('tglLahirWali', 'Tgl. Lahir Wali:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-3">
{!! Form::text('tglLahirWali', null, array('class' => 'form-control', 'placeholder' => 'Tanggal Lahir Wali', 'data-role' => "datebox", 'data-options' => '{"mode":"datebox", "useTodayButton":"true"}' )) !!}
</div>
</div>
<div class="form-group">
{!! Form::label('pendidikanWali', 'Pendidikan Wali:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-10">
<?php
foreach(config('custom.pilihan.pendidikanOrtu') as $k => $v) 
{
echo '<label class="radio-inline">';
echo '<input type="radio" name="pendidikanWali" ';
if(isset($mahasiswa) and $k == $mahasiswa -> pendidikanWali) echo 'checked="checked" ';
echo 'value="'. $k .'"> '. $v .'</label>';
}
?>
</div>
</div>
<div class="form-group">
{!! Form::label('pekerjaanWali', 'Pekerjaan Wali:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-10">
<?php
foreach(config('custom.pilihan.pekerjaanOrtu') as $k => $v) 
{
echo '<label class="radio-inline">';
echo '<input type="radio" name="pekerjaanWali" ';
if(isset($mahasiswa) and $k == $mahasiswa -> pekerjaanWali) echo 'checked="checked" ';
echo 'value="'. $k .'"> '. $v .'</label>';
}
?>
</div>
</div>			
<div class="form-group">
{!! Form::label('penghasilanWali', 'Penghasilan Wali:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-10">
<?php
foreach(config('custom.pilihan.penghasilanOrtu') as $k => $v) 
{
echo '<label class="radio-inline">';
echo '<input type="radio" name="penghasilanWali" ';
if(isset($mahasiswa) and $k == $mahasiswa -> penghasilanWali) echo 'checked="checked" ';
echo 'value="'. $k .'"> '. $v .'</label>';
}
?>
</div>
</div>											