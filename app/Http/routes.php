<?php
	// Event::listen('illuminate.query', function($sql){var_dump($sql);});
	
	Route::get(
	'/about', function() {
		return view('about');
	}
	);
	
	Route::controllers([
	'auth' => 'Auth\AuthController',
	]);
	
	Route::get(
	'/password/reset/{username}/{reset_token}', [
	'uses' => 'UsersController@getResetPassword'
	]
	);
	Route::post(
	'/password/reset', 
	'UsersController@postResetPassword'
	);
	
	Route::get(
	'/password/username', [
	'as' => 'password.username',
	'uses' => 'UsersController@getUsername'
	]
	);
	Route::post(
	'/password/username', 
	'UsersController@postUsername'
	);
	
	
	Route::post('/upload/image', 'UploadController@storeImage');
	Route::get(
	'/getimage/{year}/{month}/{file}', 
	'UploadController@getImage'
	);
	
	Route::get('getfile/{year}/{month}/{date}/{file}', [
	'as' => 'getfile', 
	'uses' => 'FileEntryController@getFile'
	]);
	
	//FORMULIR PMB
	Route::get('/pmb/formulir', 
	'PmbPesertaController@create'
	);
	Route::post('/pmb/formulir', [
	'as' => 'pmb.peserta.store',
	'uses' =>'PmbPesertaController@store'
	]);
	Route::get('/pmb/print/{type}', [
	'as' => 'pmb.peserta.print.dialog',
	'uses' =>'PmbPesertaController@dialog'
	]);
	Route::get('/pmb/print/{type}/{kode}', [
'as' => 'pmb.peserta.print',
'uses' =>'PmbPesertaController@printing'
]);
/* 	Route::get('/pmb/kartu/{kode}/print', [
'as' => 'pmb.peserta.kartu.print',
'uses' =>'PmbPesertaController@printCard'
]); */
Route::get('/pmb/formulir/{kode}', [
'as' => 'pmb.peserta.stored',
'uses' =>'PmbPesertaController@stored'
]);

// kalender akademik
Route::get('/kalenderakademik/{tahun?}', [
'as' => 'kalender.public',
'uses' => 'KalenderController@publicIndex'
]);	

Route::get('info/{id}',[
'as' => 'informasi.public', 
'uses' =>'InformasiController@publicShow'
]);

Route::get('download/{id}/{token}', [
'uses' => 'FileEntryController@download'
]);

Route::group(['middleware' => ['auth', 'maintenis']], function(){
Route::get('ganti-pass', ['as' => 'password.change', 'uses' => 'UsersController@changePassword']);
Route::patch('ganti-pass/{user_id}', ['as' => 'password.update', 'uses' => 'UsersController@updatePassword']);

Route::get('/profil', [
'as' => 'user.profile',
'roles' => ['administrator', 'dosen', 'mahasiswa'],
'uses' => 'UsersController@myProfile'
]);
Route::get('/profil/edit', [
'as' => 'user.profile.edit',
'roles' => ['administrator', 'dosen', 'mahasiswa'],
'uses' => 'UsersController@myProfileEdit'
]);
Route::patch('/profil', [
'as' => 'user.profile.update',
'roles' => ['administrator', 'dosen', 'mahasiswa'],
'uses' => 'UsersController@myProfileUpdate'
]);	
});


/* Route::group(['middleware' => ['auth', 'roles', 'maintenis']], function()
{	
//KRS
Route::get('/krs', [
'as' => 'krs.index',
'roles' => ['mahasiswa'],
'uses' => 'KrsController@index'
]);
Route::get('/krs/print', [
'as' => 'krs.print',
'roles' => ['mahasiswa'],
'uses' => 'KrsController@printKrs'
]);
Route::get('/tawaran', [
'as' => 'krs.create',
'roles' => ['mahasiswa'],
'uses' => 'KrsController@create'
]);
Route::post('/tawaran', [
'as' => 'krs.store',
'roles' => ['mahasiswa'],
'uses' => 'KrsController@store'
]);		
Route::delete('/krs/', [
'as' => 'krs.destroy',
'roles' => ['mahasiswa'],
'uses' => 'KrsController@destroy'
]);	
}); */

Route::group(['middleware' => ['auth', 'roles', 'profil', 'kuesioner', 'maintenis']], function()
{	

Route::get('/', ['as' => 'root', 'uses' => 'HomeController@index']);
Route::get('home', ['as' => 'root', 'uses' => 'HomeController@index']);	

//KRS
Route::get('/krs', [
'as' => 'krs.index',
'roles' => ['mahasiswa'],
'uses' => 'KrsController@index'
]);
Route::get('/krs/print', [
'as' => 'krs.print',
'roles' => ['mahasiswa'],
'uses' => 'KrsController@printKrs'
]);
Route::get('/tawaran', [
'as' => 'krs.create',
'roles' => ['mahasiswa'],
'uses' => 'KrsController@create'
]);
Route::post('/tawaran', [
'as' => 'krs.store',
'roles' => ['mahasiswa'],
'uses' => 'KrsController@store'
]);		
Route::delete('/krs/', [
'as' => 'krs.destroy',
'roles' => ['mahasiswa'],
'uses' => 'KrsController@destroy'
]);	


/*
* Mahasiswa
*/
Route::get('/khs/cetak/{ta?}', [
'as' => 'printmykhs',
'roles' => ['mahasiswa'], 
'uses' =>'MahasiswaController@printMyKhs'
]);
Route::get('/khs/{ta?}', [
'as' => 'viewmykhs',
'roles' => ['mahasiswa'], 
'uses' =>'MahasiswaController@viewMyKhs'
]);

Route::get('/jadwalmahasiswa', [
'roles' => ['mahasiswa'], 
'uses' =>'JadwalController@mahasiswa'
]);

});

Route::group(['middleware' => ['auth', 'roles', 'maintenis']], function()
{			
//PMB TES
Route::get('pmb/{id}/ujian', [
'as' => 'pmb.ujian.index',
'uses' => 'PmbPesertaController@index'
]);

//PMB Peserta
Route::get('pmb/{id}/peserta', [
'as' => 'pmb.peserta.index',
'uses' => 'PmbPesertaController@index'
]);
Route::get('/pmb/peserta/{kode}/show', [
'as' => 'pmb.peserta.show',
'uses' =>'PmbPesertaController@printForm'
]);	
Route::get('/pmb/{id}/peserta/{kode}/delete', [
'as' => 'pmb.peserta.delete',
'roles' => ['administrator'], 
'uses' =>'PmbPesertaController@destroy'
]);

//PMB
Route::get('pmb/{id}/delete', [
'as' => 'pmb.delete',
'uses' => 'PmbController@destroy'
]);
Route::patch('pmb/{id}', [
'as' => 'pmb.update',
'uses' => 'PmbController@update'
]);
Route::get('pmb/{id}/edit', [
'as' => 'pmb.edit',
'uses' => 'PmbController@edit'
]);
Route::post('pmb', [
'as' => 'pmb.store',
'uses' => 'PmbController@store'
]);
Route::get('pmb/create', [
'as' => 'pmb.create',
'uses' => 'PmbController@create'
]);
Route::get('pmb', [
'as' => 'pmb.index',
'uses' => 'PmbController@index'
]);
Route::get('pmb/grafik', [
'as' => 'pmb.grafik', 'uses' => 'PmbController@graph'
]);
/* Route::get('pmb/export/{format}', [
'as' => 'pmb.index', 'uses' => 'PmbController@exportTo'
]); */
Route::get('pmb/{no_pendaftaran}', [
'as' => 'pmb.show', 'uses' => 'PmbController@show'
]);
Route::get('pmb/{no_pendaftaran}/cetak', [
'as' => 'pmb.cetak', 'uses' => 'PmbController@cetak'
]);

//KURIKULUM MATKUL
Route::post('/kurikulum/{kurikulum}/matkul/add', [
'as' => 'prodi.kurikulum.matkul.add',
'roles' => ['administrator'],
'uses' =>'KurikulumMatkulController@addFrom'
]);
Route::get('/kurikulum/{kurikulum}/matkul/edit', [
'as' => 'prodi.kurikulum.matkul.edit',
'roles' => ['administrator'],
'uses' =>'KurikulumMatkulController@edit'
]);
Route::post('/kurikulum/{kurikulum}/matkul/update', [
'as' => 'prodi.kurikulum.matkul.update',
'roles' => ['administrator'],
'uses' =>'KurikulumMatkulController@update'
]);

Route::get('/kurikulum/{kurikulum}/matkul/create', [
'as' => 'prodi.kurikulum.matkul.create',
'roles' => ['administrator'],
'uses' =>'KurikulumMatkulController@create'
]);
Route::post('/kurikulum/{kurikulum}/matkul', [
'as' => 'prodi.kurikulum.matkul.store',
'roles' => ['administrator'],
'uses' =>'KurikulumMatkulController@store'
]);
Route::get('/kurikulum/{kurikulum}/matkul/{matkul}/delete', [
'as' => 'prodi.kurikulum.matkul.delete',
'roles' => ['administrator'],
'uses' =>'KurikulumMatkulController@destroy'
]);

//KURIKULUM
Route::get('/kurikulum', [
'as' => 'prodi.kurikulum.index',
'roles' => ['administrator'],
'uses' =>'KurikulumController@index'
]);
Route::get('/kurikulum/create', [
'as' => 'prodi.kurikulum.create',
'roles' => ['administrator'],
'uses' =>'KurikulumController@create'
]);
Route::post('/kurikulum/create', [
'as' => 'prodi.kurikulum.store',
'roles' => ['administrator'],
'uses' =>'KurikulumController@store'
]);
Route::get('/kurikulum/{kurikulum}/detail', [
'as' => 'prodi.kurikulum.detail',
'roles' => ['administrator'],
'uses' =>'KurikulumController@detail'
]);
Route::get('/kurikulum/{kurikulum}/delete', [
'as' => 'prodi.kurikulum.delete',
'roles' => ['administrator'],
'uses' =>'KurikulumController@destroy'
]);
Route::get('/kurikulum/{kurikulum}/edit', [
'as' => 'prodi.kurikulum.edit',
'roles' => ['administrator'],
'uses' =>'KurikulumController@edit'
]);
Route::patch('/kurikulum/{kurikulum}', [
'as' => 'prodi.kurikulum.update',
'roles' => ['administrator'],
'uses' =>'KurikulumController@update'
]);

Route::post('/mahasiswa/golongan', [
'roles' => ['keuangan / administrasi', 'administrator', 'akademik'],
'uses' =>'MahasiswaController@getGolongan'
]);

//CONFIG
Route::get('config', [
'as' => 'config.edit',
'roles' => ['administrator'],
'uses' => 'ConfigController@edit'
]);
Route::patch('config', [
'as' => 'config.update',
'roles' => ['administrator'],
'uses' => 'ConfigController@update'
]);

// Transfer fast edit
Route::get('/mahasiswa/transfer', [
'roles' => ['administrator', 'akademik'],
'uses' => 'MahasiswaController@transfer'
]);
Route::post('/mahasiswa/transfer', [
'roles' => ['administrator', 'akademik'],
'uses' => 'MahasiswaController@filterMahasiswa'
]);
Route::post('/mahasiswa/do/transfer', [
'roles' => ['administrator', 'akademik'],
'uses' =>'MahasiswaController@doTransfer'
]);

// Jenis Pembiayaaan fast edit
Route::get('/mahasiswa/adminpembiayaan', [
'roles' => ['administrator', 'akademik'],
'uses' => 'MahasiswaController@adminFundingTypeForm'
]);
Route::post('/mahasiswa/adminpembiayaan', [
'roles' => ['administrator', 'akademik'],
'uses' =>'MahasiswaController@adminFundingTypeUpdate'
]);
Route::post('/mahasiswa/adminpembiayaan/anggota', [
'roles' => ['administrator', 'akademik'],
'uses' => 'MahasiswaController@adminFundingTypeMember'
]);	

// Status fast edit
Route::get('/mahasiswa/adminstatus', [
'as' => 'admin.status',
'roles' => ['administrator', 'akademik'],
'uses' => 'MahasiswaController@adminStatus'
]);	
Route::post('/mahasiswa/adminstatus', [
'as' => 'admin.status.update',
'roles' => ['administrator', 'akademik'],
'uses' => 'MahasiswaController@adminStatusUpdate'
]);
Route::post('/mahasiswa/adminstatus/anggota', [
'roles' => ['administrator', 'akademik'],
'uses' => 'MahasiswaController@adminStatusAnggota'
]);	

// Perwalian fast edit
Route::get('/mahasiswa/adminperwalian', [
'as' => 'admin.perwalian',
'roles' => ['administrator', 'akademik'],
'uses' => 'MahasiswaController@adminCustodian'
]);	
Route::post('/mahasiswa/adminperwalian', [
'as' => 'admin.perwalian.update',
'roles' => ['administrator', 'akademik'],
'uses' => 'MahasiswaController@adminCustodianUpdate'
]);
Route::post('/mahasiswa/adminperwalian/anggota', [
'roles' => ['administrator', 'akademik'],
'uses' => 'MahasiswaController@adminCustodianAnggota'
]);	

/*
Data exchange
*/
Route::get('/mahasiswa/yudisium/impor', [
'as' => 'mahasiswa.yudisium.import',
'roles' => ['administrator', 'akademik'],
'uses' => 'DataExchangeController@importYudisiumForm'
]);	
Route::post('/mahasiswa/yudisium/impor', [
'as' => 'mahasiswa.yudisium.import.post',
'roles' => ['administrator', 'akademik'],
'uses' => 'DataExchangeController@importYudisium'
]);	

Route::get('/mahasiswa/impor', [
'as' => 'mahasiswa.import',
'roles' => ['administrator', 'akademik'],
'uses' => 'DataExchangeController@importForm'
]);	
Route::post('/import/mahasiswa', [
'as' => 'mahasiswa.import.post',
'roles' => ['administrator', 'akademik'],
'uses' => 'DataExchangeController@import'
]);	

/* Route::get('/export/transkripmerge', [
'as' => 'export.transkripmerge',
'roles' => ['administrator'],
'uses' => 'DataExchangeController@exportTranskripMerge'
]); */

Route::get('/export/kurikulum', [
'as' => 'export.kurikulum',
'roles' => ['administrator'],
'uses' => 'DataExchangeController@exportKurikulum'
]);

Route::get('/export/{data}', [
'as' => 'export',
'roles' => ['administrator'],
'uses' => 'DataExchangeController@export'
]);	
Route::get('/export/{data}/{prodi}/{type}/{var?}', [
'as' => 'export.format',
'roles' => ['administrator'],
'uses' => 'DataExchangeController@exportInto'
]);	

/*
29 Okt 2016
PPL
*/
Route::get('/ppl/info', [
'as' => 'mahasiswa.ppl.info',
'roles' => ['mahasiswa'],
function(){abort(404);}
]);

Route::get('/ppl/{id}/peserta/{mhs}/cetak', [
'as' => 'mahasiswa.ppl.peserta.cetak',
'roles' => ['administrator', 'akademik'], 
'uses' =>'PplController@cetakFormulir'
]);
Route::get('/ppl/cetakformulir', [
'as' => 'mahasiswa.ppl.peserta.cetak2',
'roles' => ['mahasiswa', 'akademik'], 
'uses' =>'PplController@cetakFormulir2'
]);

//SKRIPSI
Route::get('/skripsi/mahasiswa/search', [
'roles' => ['administrator', 'akademik'],
'uses' => 'SkripsiController@filter'
]);
Route::get('/skripsi/search', [
'roles' => ['administrator', 'akademik'],
'uses' => 'SkripsiController@search'
]);

Route::get('/skripsi/create', [
'as' => 'skripsi.create',
'roles' => ['administrator', 'akademik'], 
'uses' =>'SkripsiController@create'
]);
Route::get('/skripsi/tmp/{id}/delete', [
'as' => 'skripsi.tmp.delete',
'roles' => ['administrator', 'akademik'], 
'uses' =>'SkripsiController@destroy_tmp'
]);
Route::post('/skripsi/tmp', [
'as' => 'skripsi.tmp.store',
'roles' => ['administrator', 'akademik'], 
'uses' =>'SkripsiController@store_tmp'
]);
Route::get('/skripsi/tmp/save', [
'as' => 'skripsi.store',
'roles' => ['administrator', 'akademik'], 
'uses' =>'SkripsiController@store'
]);
Route::get('/skripsi/tmp/remove', [
'as' => 'skripsi.tmp.remove',
'roles' => ['administrator', 'akademik'], 
'uses' =>'SkripsiController@remove_tmp'
]);
Route::get('/skripsi/', [
'as' => 'skripsi.index',
'roles' => ['administrator', 'akademik'], 
'uses' =>'SkripsiController@index'
]);
Route::get('/skripsi/{id}/file', [
'as' => 'skripsi.file',
'roles' => ['administrator', 'akademik'], 
'uses' =>'SkripsiController@downloadFile'
]);
Route::get('/skripsi/{id}', [
'as' => 'skripsi.show',
'roles' => ['administrator', 'akademik'], 
'uses' =>'SkripsiController@show'
]);
Route::get('/skripsi/{id}/delete', [
'as' => 'skripsi.delete',
'roles' => ['administrator', 'akademik'], 
'uses' =>'SkripsiController@destroy'
]);
Route::get('/skripsi/{id}/edit', [
	'as' => 'skripsi.edit',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'SkripsiController@edit'
	]);
	Route::patch('/skripsi/{id}', [
	'as' => 'skripsi.update',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'SkripsiController@update'
	]);
	
	//BIMBINGAN
	Route::get('/skripsi/{skripsi}/bimbingan/create', [
	'as' => 'mahasiswa.skripsi.bimbingan.create',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'BimbinganSkripsiController@create'
	]);
	Route::post('/skripsi/{skripsi}/bimbingan/create', [
	'as' => 'mahasiswa.skripsi.bimbingan.store',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'BimbinganSkripsiController@store'
	]);
	Route::get('/skripsi/{skripsi}/bimbingan/{bimbingan}/edit', [
	'as' => 'mahasiswa.skripsi.bimbingan.edit',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'BimbinganSkripsiController@edit'
	]);
	Route::patch('/skripsi/{skripsi}/bimbingan/{bimbingan}/edit', [
	'as' => 'mahasiswa.skripsi.bimbingan.update',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'BimbinganSkripsiController@update'
	]);
	
	//PPL
	Route::get('/ppl', [
	'as' => 'mahasiswa.ppl.index',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'PplController@index'
	]);
	Route::get('/ppl/create', [
	'as' => 'mahasiswa.ppl.create',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'PplController@create'
	]);
	Route::post('/ppl/create', [
	'as' => 'mahasiswa.ppl.store',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'PplController@store'
	]);
	Route::get('/ppl/{id}/peserta', [
	'as' => 'mahasiswa.ppl.peserta',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'PplController@peserta'
	]);
	Route::get('/ppl/{id}/peserta/{mhs}/hapus', [
	'as' => 'mahasiswa.ppl.peserta.delete',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'PplController@hapusPeserta'
	]);
	
	Route::get('/ppl/{id}/peserta/{mhs}', [
	'as' => 'mahasiswa.ppl.peserta.show',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'PplController@showPeserta'
	]);
	Route::get('/ppl/{id}/edit', [
	'as' => 'mahasiswa.ppl.edit',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'PplController@edit'
	]);
	Route::patch('/ppl/{id}', [
	'as' => 'mahasiswa.ppl.update',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'PplController@update'
	]);
	Route::get('/ppl/{id}/delete', [
	'as' => 'mahasiswa.ppl.delete',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'PplController@destroy'
	]);
	
	Route::get('/ppl/daftar', [
	'as' => 'mahasiswa.ppl.formdaftar',
	'roles' => ['mahasiswa', 'akademik'], 
	'uses' =>'PplController@formDaftarPpl'
	]);
	Route::post('/ppl/daftar', [
	'as' => 'mahasiswa.ppl.daftar',
	'roles' => ['mahasiswa', 'akademik'], 
	'uses' =>'PplController@daftarPpl'
	]);
	
	/*
	27 Okt 2016
	PKM
	*/
	Route::get('/pkm/info', [
	'as' => 'mahasiswa.pkm.info',
	'roles' => ['mahasiswa'],
	function(){abort(404);}
	]);
	
	Route::get('/pkm/{id}/peserta/{mhs}/cetak', [
	'as' => 'mahasiswa.pkm.peserta.cetak',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'PkmController@cetakFormulir'
	]);
	Route::get('/pkm/cetakformulir', [
	'as' => 'mahasiswa.pkm.peserta.cetak2',
	'roles' => ['mahasiswa', 'akademik'], 
	'uses' =>'PkmController@cetakFormulir2'
	]);
	
	Route::get('/pkm', [
	'as' => 'mahasiswa.pkm.index',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'PkmController@index'
	]);
	Route::get('/pkm/create', [
	'as' => 'mahasiswa.pkm.create',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'PkmController@create'
	]);
	Route::post('/pkm/create', [
	'as' => 'mahasiswa.pkm.store',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'PkmController@store'
	]);
	Route::get('/pkm/{id}/peserta', [
	'as' => 'mahasiswa.pkm.peserta',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'PkmController@peserta'
	]);
	Route::get('/pkm/{id}/peserta/{mhs}/hapus', [
	'as' => 'mahasiswa.pkm.peserta.delete',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'PkmController@hapusPeserta'
	]);
	
	Route::get('/pkm/{id}/peserta/{mhs}', [
	'as' => 'mahasiswa.pkm.peserta.show',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'PkmController@showPeserta'
	]);
	Route::get('/pkm/{id}/edit', [
	'as' => 'mahasiswa.pkm.edit',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'PkmController@edit'
	]);
	Route::patch('/pkm/{id}', [
	'as' => 'mahasiswa.pkm.update',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'PkmController@update'
	]);
	Route::get('/pkm/{id}/delete', [
	'as' => 'mahasiswa.pkm.delete',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'PkmController@destroy'
	]);
	
	Route::get('/pkm/daftar', [
	'as' => 'mahasiswa.pkm.formdaftar',
	'roles' => ['mahasiswa'], 
	'uses' =>'PkmController@formDaftarPkm'
	]);
	Route::post('/pkm/daftar', [
	'as' => 'mahasiswa.pkm.daftar',
	'roles' => ['mahasiswa'], 
	'uses' =>'PkmController@daftarPkm'
	]);
	
	
	/*
	15 Okt 2016
	Wisuda
	*/
	
	Route::get('/wisuda/info', [
	'as' => 'mahasiswa.wisuda.info',
	'roles' => ['mahasiswa'],
	function(){abort(404);}
	]);
	
	Route::get('/wisuda/{id}/peserta/{mhs}/cetak', [
	'as' => 'mahasiswa.wisuda.peserta.cetak',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'WisudaController@cetakFormulir'
	]);
	Route::get('/wisuda/cetakformulir', [
	'as' => 'mahasiswa.wisuda.peserta.cetak2',
	'roles' => ['mahasiswa'], 
	'uses' =>'WisudaController@cetakFormulir2'
	]);
	
	Route::get('/wisuda', [
	'as' => 'mahasiswa.wisuda.index',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'WisudaController@index'
	]);
	Route::get('/wisuda/create', [
	'as' => 'mahasiswa.wisuda.create',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'WisudaController@create'
	]);
	Route::post('/wisuda/create', [
	'as' => 'mahasiswa.wisuda.store',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'WisudaController@store'
	]);
	Route::get('/wisuda/{id}/peserta', [
	'as' => 'mahasiswa.wisuda.peserta',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'WisudaController@peserta'
	]);
	Route::get('/wisuda/{id}/peserta/{mhs}/hapus', [
	'as' => 'mahasiswa.wisuda.peserta.delete',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'WisudaController@hapusPeserta'
	]);
	
	Route::get('/wisuda/{id}/peserta/{mhs}', [
	'as' => 'mahasiswa.wisuda.peserta.show',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'WisudaController@showPeserta'
	]);
	Route::get('/wisuda/{id}/edit', [
	'as' => 'mahasiswa.wisuda.edit',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'WisudaController@edit'
	]);
	Route::patch('/wisuda/{id}', [
	'as' => 'mahasiswa.wisuda.update',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'WisudaController@update'
	]);
	Route::get('/wisuda/{id}/delete', [
	'as' => 'mahasiswa.wisuda.delete',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'WisudaController@destroy'
	]);
	
	Route::get('/wisuda/daftar', [
	'as' => 'mahasiswa.wisuda.formdaftar',
	'roles' => ['mahasiswa'], 
	'uses' =>'WisudaController@formDaftarWisuda'
	]);
	Route::post('/wisuda/daftar', [
	'as' => 'mahasiswa.wisuda.daftar',
	'roles' => ['mahasiswa'], 
	'uses' =>'WisudaController@daftarWisuda'
	]);
	
	/**
	* 05 Apr 2016
	* Absensi Dosen
	**/
	Route::get('dosen/absensi/{month?}/{year?}', [
	'as' => 'dosen.absensi.index', 
	'roles' => ['administrator', 'keuangan / administrasi', 'akademik'],
	'uses' =>'AbsensiDosenController@index'
	])
	-> where(['month' => '[0-9]+', 'year' => '[0-9]+']);
	Route::get('dosen/absensi/create/{d?}/{m?}/{y?}/{id?}/{st?}', [
	'as' => 'dosen.absensi.create', 
	'roles' => ['administrator', 'keuangan / administrasi', 'akademik'],
	'uses' =>'AbsensiDosenController@create'
	]);
	Route::post('dosen/absensi', [
	'as' => 'dosen.absensi.store', 
	'roles' => ['administrator', 'keuangan / administrasi', 'akademik'],
	'uses' =>'AbsensiDosenController@store'
	]);
	
	/**
	* Ruangan
	**/
	Route::get('ruangan', [
	'as' => 'ruangan.index', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'RuanganController@index'
	]);
	Route::get('ruangan/create', [
	'as' => 'ruangan.create', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'RuanganController@create'
	]);
	Route::get('ruangan/{ruangan}/edit', [
	'as' => 'ruangan.edit', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'RuanganController@edit'
	]);
	Route::patch('ruangan/{ruangan}', [
	'as' => 'ruangan.update', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'RuanganController@update'
	]);
	Route::post('ruangan', [
	'as' => 'ruangan.store', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'RuanganController@store'
	]);
	
	/**
	* Kelas
	**/
	Route::get('kelas', [
	'as' => 'kelas.index', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'KelasController@index'
	]);
	Route::get('kelas/create', [
	'as' => 'kelas.create', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'KelasController@create'
	]);
	Route::get('kelas/{kelas}/edit', [
	'as' => 'kelas.edit', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'KelasController@edit'
	]);
	Route::patch('kelas/{kelas}', [
	'as' => 'kelas.update', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'KelasController@update'
	]);
	Route::post('kelas', [
	'as' => 'kelas.store', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'KelasController@store'
	]);
	
	/**
	* PRODI
	**/
	Route::get('prodi', [
	'as' => 'prodi.index', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'ProdiController@index'
	]);
	Route::get('prodi/create', [
	'as' => 'prodi.create', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'ProdiController@create'
	]);
	Route::get('prodi/{prodi}/edit', [
	'as' => 'prodi.edit', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'ProdiController@edit'
	]);
	Route::patch('prodi/{prodi}', [
	'as' => 'prodi.update', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'ProdiController@update'
	]);
	Route::post('prodi/store', [
	'as' => 'prodi.store', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'ProdiController@store'
	]);
	Route::get('prodi/{prodi}/delete', [
	'as' => 'prodi.delete', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'ProdiController@destroy'
	]); 
	
	/**
	* Jadwal
	**/
	Route::get('jadwal', [
	'as' => 'matkul.tapel.jadwal', 
	'roles' => ['prodi', 'administrator', 'akademik'],
	'uses' =>'JadwalController@index'
	]);
	Route::get('jadwal2', [
	'as' => 'matkul.tapel.jadwal2', 
	'roles' => ['prodi', 'administrator', 'akademik'],
	'uses' =>'JadwalController@index2'
	]);
	Route::get('jadwal/create', [
	'as' => 'matkul.tapel.jadwal.create', 
	'roles' => ['prodi', 'administrator', 'akademik'],
	'uses' =>'JadwalController@create'
	]);
	Route::post('jadwal/store', [
	'as' => 'matkul.tapel.jadwal.store', 
	'roles' => ['prodi', 'administrator', 'akademik'],
	'uses' =>'JadwalController@store'
	]);
	Route::get('jadwal/{jadwal}/delete', [
	'as' => 'matkul.tapel.jadwal.delete', 
	'roles' => ['prodi', 'administrator', 'akademik'],
	'uses' =>'JadwalController@delete'
	]);
	Route::get('jadwal/{jadwal}/edit', [
	'as' => 'matkul.tapel.jadwal.edit', 
	'roles' => ['prodi', 'administrator', 'akademik'],
	'uses' =>'JadwalController@edit'
	]);
	Route::patch('jadwal/{jadwal}', [
	'as' => 'matkul.tapel.jadwal.update', 
	'roles' => ['prodi', 'administrator', 'akademik'],
	'uses' =>'JadwalController@update'
	]);
	Route::get('/jadwaldosen', [
	'roles' => ['dosen'], 
	'uses' =>'JadwalController@dosen'
	]);
	
	/**
	* Transaksi lain
	**/
	Route::get('/transaksi', [
	'as' => 'transaksi.index',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'TransaksiController@index'
	]);
	Route::get('/transaksi/create', [
	'as' => 'transaksi.create',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'TransaksiController@create'
	]);
	Route::post('/transaksi', [
	'as' => 'transaksi.store',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'TransaksiController@store'
	]);
	/* 		Route::get('/transaksi/{transaksi}', [
	'as' => 'transaksi.edit',
	'roles' => ['keuangan / administrasi'],
	'uses' => 'TransaksiController@edit'
	]);
	Route::patch('/transaksi/{transaksi}', [
	'as' => 'transaksi.update',
	'roles' => ['keuangan / administrasi'],
	'uses' => 'TransaksiController@update'
	]); */
	
	/**
	* Neraca
	**/
	Route::get('/neraca', [
	'as' => 'neraca.index',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'NeracaController@index'
	]);
	
	/**
	* Gaji
	**/
	// Route::get('/gajidosen', [
	// 'roles' => ['dosen'],
	// 'uses' => 'GajiController@indexDosen'
	// ]);
	Route::get('/gaji', [
	'as' => 'gaji.index',
	'roles' => ['keuangan / administrasi', 'dosen', 'administrator'],
	'uses' => 'GajiController@index'
	]);
	Route::get('/gaji/create/{dosen_id}', [
	'as' => 'gaji.create',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'GajiController@create'
	]);
	Route::post('/gaji', [
	'as' => 'gaji.store',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'GajiController@store'
	]);
	Route::get('/gaji/{dosen_id}/{bulan}/delete', [
	'as' => 'gaji.delete',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'GajiController@destroy'
	]);
	Route::get('/gaji/{dosen_id}/{bulan}/confirm', [
	'as' => 'gaji.confirm',
	'roles' => ['keuangan / administrasi', 'dosen', 'administrator'],
	'uses' => 'GajiController@confirm'
	]);
	
	
	/**
	* Pembayaran
	**/
	
	// Mahasiswa
	/* 	Route::get('/pembayaran', [
	'as' => 'mahasiswa.pembayaran.index',
	'roles' => ['mahasiswa'],
	'uses' => 'PembayaranController@index'
	]);
	Route::post('/pembayaran/search', [
	'roles' => ['mahasiswa'],
	'uses' => 'PembayaranController@preSearch'
	]);
	Route::get('/pembayaran/search/{q}', [
	'as' => 'biaya.pembayaran.search', 
	'roles' => ['mahasiswa'],
	'uses' => 'PembayaranController@search'
	]);
	*/
	// Tagihan
	/* 	Route::get('/biaya/tagihan', [
	'as' => 'biaya.tagihan.index',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'TagihanController@index'
	]);
	Route::get('/biaya/tagihan/create', [
	'as' => 'biaya.tagihan.create',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'TagihanController@create'
	]);
	Route::post('/biaya/tagihan', [
	'as' => 'biaya.tagihan.store',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'TagihanController@store'
	]);
	Route::post('/biaya/tagihan/search', [
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'TagihanController@preSearch'
	]);
	Route::get('/biaya/tagihan/search/{q}', [
	'as' => 'biaya.tagihan.search', 
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'TagihanController@search'
	]);
	*/
	// Route::post('/biaya/search', [
	// 'roles' => ['keuangan / administrasi', 'administrator'],
	// 'uses' => 'BiayaController@preSearch'
	// ]);
	// Route::get('/biaya/search/{q}', [
	// 'as' => 'matkul.search', 
	// 'roles' => ['keuangan / administrasi', 'administrator'],
	// 'uses' => 'BiayaController@search'
	// ]);
	
	//MAHASISWA
	
	Route::get('/mahasiswa/{id}/transkrip', [
	'as' => 'mahasiswa.transkrip',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'MahasiswaController@transkrip'
	]);	
	Route::get('/mahasiswa/{id}/transkrip/print', [
	'as' => 'mahasiswa.transkrip.print',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'MahasiswaController@printTranskrip'
	]);	
	
	Route::get('/mahasiswa/create', [
	'as' => 'mahasiswa.create',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'MahasiswaController@create'
	]);	
	Route::post('/mahasiswa', [
	'as' => 'mahasiswa.store',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'MahasiswaController@store'
	]);	
	Route::get('/mahasiswa/{id}/edit', [
	'as' => 'mahasiswa.edit',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'MahasiswaController@edit'
	]);	
	Route::patch('/mahasiswa/{id}', [
	'as' => 'mahasiswa.update',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'MahasiswaController@update'
	]);	
	Route::delete('/mahasiswa/{id}', [
	'as' => 'mahasiswa.destroy',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'MahasiswaController@destroy'
	]);		
	Route::get('/mahasiswa', [
	'as' => 'mahasiswa.index',
	'roles' => ['administrator', 'akademik', 'ketua', 'keuangan / administrasi'],
	'uses' => 'MahasiswaController@index'
	]);	
	
	Route::get('/mahasiswa/{nim}/khs/cetak/{ta?}', [
	'as' => 'mahasiswa.khs.cetak', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'MahasiswaController@cetakKhs'
	]);
	Route::get('/mahasiswa/{nim}/khs/{ta?}', [
	'as' => 'mahasiswa.khs', 
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' =>'MahasiswaController@viewKhs'
	]);
	Route::post('/mahasiswa/angkatan/{docheck?}', [
	'roles' => ['administrator', 'akademik'],
	'uses' => 'MahasiswaController@angkatan'
	]);
	Route::get('/mahasiswa/search', [
	'roles' => ['administrator', 'akademik', 'ketua', 'keuangan / administrasi'],
	'uses' => 'MahasiswaController@search'
	]);
	Route::get('/mahasiswa/filter', [
	'roles' => ['administrator', 'akademik', 'ketua', 'keuangan / administrasi'],
	'uses' => 'MahasiswaController@filter'
	]);
	Route::get('/mahasiswa/search/{q}', [
	'as' => 'mahasiswa.search', 
	'roles' => ['administrator', 'akademik', 'ketua', 'keuangan / administrasi'],
	'uses' => 'MahasiswaController@search'
	]);	
	Route::get('/mahasiswa/{nim}/krs/{action?}', [
	'as' => 'mahasiswa.krs',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'KrsController@adminKrs'
	]);			
	
	Route::get('/mahasiswa/{id}', [
	'as' => 'mahasiswa.show',
	'roles' => ['administrator', 'akademik', 'mahasiswa'],
	'uses' => 'MahasiswaController@show'
	]);		
	
	Route::get('/biaya/tanggungan', [
	'as' => 'biaya.mahasiswa.tanggungan',
	'roles' => ['mahasiswa'],
	'uses' => 'BiayaController@tanggunganMahasiswa'
	]);
	Route::get('/biaya/riwayat', [
	'as' => 'biaya.mahasiswa.riwayat',
	'roles' => ['mahasiswa'],
	'uses' => 'BiayaController@riwayatMahasiswa'
	]);
	
	
	Route::get('/biaya/{nim}/{id}/delete', [
	'as' => 'biaya.delete',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'BiayaController@destroy'
	]);
	Route::post('/biaya/form', [
	'as' => 'biayakuliah.form.submit',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'BiayaController@formSubmit'
	]);
	Route::get('/biaya/form/{nim?}', [
	'as' => 'biayakuliah.form',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'BiayaController@form'
	]);
	Route::get('/biaya/setup/{tahun?}/{prodi_id?}/{program_id?}/{jenisPembayaran?}', [
	'as' => 'biayakuliah.setup',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'BiayaKuliahController@setup'
	]);
	Route::post('/biaya/submit', [
	'as' => 'biayakuliah.setup.submit',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'BiayaKuliahController@setupSubmit'
	]);
	Route::get('/biaya/{nim}/cetak/status', [
	'as' => 'biaya.mahasiswa.status',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'BiayaController@printStatus'
	]);
	Route::get('/biaya/{nim}/cetak/kwitansi', [
	'as' => 'biaya.mahasiswa.receipt',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'BiayaController@printReceipt'
	]);
	Route::get('/biaya/detail/{tahun?}/{prodi_id?}/{program_id?}/{jenisPembayaran?}', [
	'as' => 'biaya.detail',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'BiayaController@detail'
	]);
	Route::get('/biaya/detail/{tahun}/{prodi_id}/{program_id}/{jenisPembayaran}/cetak', [
	'as' => 'biaya.detail.print',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'BiayaController@detailCetak'
	]);
	Route::get('/biaya/detail/{tahun}/{prodi_id}/{program_id}/{jenisPembayaran}/save', [
	'as' => 'biaya.detail.save',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'BiayaController@detailSave'
	]);
	Route::get('/biaya/report/{tahun?}/{prodi_id?}/{program_id?}/{jenisPembayaran?}', [
	'as' => 'biaya.report',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'BiayaController@report'
	]);
	/* 
	Route::get('/biaya/mahasiswa', [
	'as' => 'biaya.mahasiswa.index',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'BiayaController@mahasiswaIndex'
	]);
	Route::post('/biaya/mahasiswa/search', [
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'BiayaController@preSearchMahasiswa'
	]);
	Route::get('/biaya/mahasiswa/search/{q}', [
	'as' => 'matkul.search', 
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'BiayaController@searchMahasiswa'
	]);
	*/
	// Route::get('/biaya/create/{mahasiswa_id}', [
	// 'as' => 'biaya.create',
	// 'roles' => ['keuangan / administrasi', 'administrator'],
	// 'uses' => 'BiayaController@create'
	// ]);
	// Route::post('/biaya', [
	// 'as' => 'biaya.store',
	// 'roles' => ['keuangan / administrasi', 'administrator'],
	// 'uses' => 'BiayaController@store'
	// ]);
	// Route::get('/biaya/{biaya}/edit', [
	// 'as' => 'biaya.edit',
	// 'roles' => ['keuangan / administrasi', 'administrator'],
	// 'uses' => 'BiayaController@edit'
	// ]);
	// Route::get('/biaya/{nim}', [
	// 'as' => 'biaya.show',
	// 'roles' => ['keuangan / administrasi', 'administrator'],
	// 'uses' => 'BiayaController@show'
	// ]);
	
	/* 		
	Route::patch('/biaya/{biaya}', [
	'as' => 'biaya.update',
	'roles' => ['keuangan / administrasi'],
	'uses' => 'BiayaController@update'
	]); 
	*/
	
	/**
	* Jenis Gaji
	**/
	Route::get('/jenisgaji/{jenisgaji}/delete', [
	'as' => 'jenisgaji.delete',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'JenisGajiController@destroy'
	]);
	Route::get('/jenisgaji', [
	'as' => 'jenisgaji.index',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'JenisGajiController@index'
	]);
	Route::get('/jenisgaji/create', [
	'as' => 'jenisgaji.create',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'JenisGajiController@create'
	]);
	Route::post('/jenisgaji', [
	'as' => 'jenisgaji.store',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'JenisGajiController@store'
	]);
	Route::get('/jenisgaji/{jenisgaji}/edit', [
	'as' => 'jenisgaji.edit',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'JenisGajiController@edit'
	]);
	Route::patch('/jenisgaji/{jenisgaji}', [
	'as' => 'jenisgaji.update',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'JenisGajiController@update'
	]);
	
	/**
	* Jenis Biaya
	**/
	Route::get('/jenisbiaya', [
	'as' => 'jenisbiaya.index',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'JenisBiayaController@index'
	]);
	Route::get('/jenisbiaya/create', [
	'as' => 'jenisbiaya.create',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'JenisBiayaController@create'
	]);
	Route::post('/jenisbiaya', [
	'as' => 'jenisbiaya.store',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'JenisBiayaController@store'
	]);
	Route::get('/jenisbiaya/{jenisbiaya}', [
	'as' => 'jenisbiaya.edit',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'JenisBiayaController@edit'
	]);
	Route::get('/jenisbiaya/{jenisbiaya}/delete', [
	'as' => 'jenisbiaya.delete',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'JenisBiayaController@destroy'
	]);
	Route::patch('/jenisbiaya/{jenisbiaya}', [
	'as' => 'jenisbiaya.update',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'JenisBiayaController@update'
	]);
	
	
	/*
	Ketua
	*/
	Route::get('informasi',[
	'as' => 'informasi.index', 
	'roles' => ['administrator', 'ketua', 'akademik'],
	'uses' =>'InformasiController@index'
	]);
	Route::get('informasi/{id}/show',[
	'as' => 'informasi.show', 
	'roles' => ['administrator', 'ketua', 'akademik'],
	'uses' =>'InformasiController@show'
	]);
	Route::patch('informasi/{id}/edit',[
	'as' => 'informasi.update', 
	'roles' => ['administrator', 'ketua', 'akademik'],
	'uses' =>'InformasiController@update'
	]);
	Route::get('informasi/{id}/edit',[
	'as' => 'informasi.edit', 
	'roles' => ['administrator', 'ketua', 'akademik'],
	'uses' =>'InformasiController@edit'
	]);
	Route::post('informasi',[
	'as' => 'informasi.store', 
	'roles' => ['administrator', 'ketua', 'akademik'],
	'uses' =>'InformasiController@store'
	]);
	Route::get('informasi/create',[
	'as' => 'informasi.create', 
	'roles' => ['administrator', 'ketua', 'akademik'],
	'uses' =>'InformasiController@create'
	]);
	
	/*
	Kelas kuliah
	*/
	Route::post('matkul/tapel/getangkatanlist', [
	'as' => 'matkul.tapel.getangkatanlist', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'MatkulTapelController@getAngkatanList'
	]);
	Route::post('matkul/tapel/getmatkullist', [
	'as' => 'matkul.tapel.getmatkullist', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'MatkulTapelController@getMatkulList'
	]);
	Route::get('matkul/tapel/filter', [
	'as' => 'matkul.tapel.filtering', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'MatkulTapelController@filtering'
	]);
	
	Route::get('matkul/tapel/{matkul_tapel_id}/jenisnilai/pilih',[
	'as' => 'jenisnilai.index', 
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' =>'JenisNilaiController@index'
	]);
	Route::get('matkul/tapel/{matkul_tapel_id}/jenisnilai/{jenis_nilai_id}/pilih', [
	'as' => 'jenisnilai.use', 
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' =>'JenisNilaiController@useExistingType'
	]);
	Route::get('matkul/tapel/{matkul_tapel_id}/jenisnilai', [
	'as' => 'jenisnilai.create', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'JenisNilaiController@create'
	]);
	Route::post('matkul/tapel/{matkul_tapel_id}/jenisnilai',[
	'as' => 'jenisnilai.store',
	'roles' => ['administrator', 'akademik'],
	'uses' =>'JenisNilaiController@store'
	]);
	Route::get('matkul/tapel/{matkul_tapel_id}/jenisnilai/{jenis_nilai_id}/edit', [
	'as' => 'jenisnilai.edit', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'JenisNilaiController@edit'
	]);
	Route::patch('matkul/tapel/{matkul_tapel_id}/jenisnilai/{jenis_nilai_id}',[
	'as' => 'jenisnilai.update',
	'roles' => ['administrator', 'akademik'],
	'uses' =>'JenisNilaiController@update'
	]);
	
	Route::get('matkul/tapel', [
	'as' => 'matkul.tapel.index', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'MatkulTapelController@index'
	]);
	
	Route::get('matkul/tapel/{matkul_tapel_id}/delete', [
	'as' => 'matkul.tapel.delete', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'MatkulTapelController@destroy'
	]);
	Route::get('matkul/tapel/{matkul_tapel_id}/cetak/formabsensi', [
	'as' => 'matkul.tapel.print.formabsensi', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'MatkulTapelController@cetakFormAbsensi'
	]);
	Route::get('matkul/tapel/{matkul_tapel_id}/edit', [
	'as' => 'matkul.tapel.edit', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'MatkulTapelController@edit'
	]);
	Route::patch('matkul/tapel/{matkul_tapel_id}', [
	'as' => 'matkul.tapel.update', 
	'roles' => ['administrator', 'akademik'],
	'uses' => 'MatkulTapelController@update'
	]);
	
	Route::get('matkul/tapel/create', [
	'as' => 'matkul.tapel.create',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'MatkulTapelController@create'
	]);
	Route::post('matkul/tapel', [
	'as' => 'matkul.tapel.store', 
	'roles' => ['administrator', 'akademik'],
	'uses' => 'MatkulTapelController@store'
	]);
	
	Route::get('matkul/tapel/{matkul_tapel_id}/mahasiswa', [
	'as' => 'matkul.tapel.addmhs', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'MatkulTapelController@AddMhs'
	]);
	Route::post('matkul/tapel/{matkul_tapel_id}/mahasiswa/in', [
	'as' => 'matkul.tapel.addmhsin', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'MatkulTapelController@AddMhsIn'
	]);
	Route::post('matkul/tapel/{matkul_tapel_id}/mahasiswa/out', [
	'as' => 'matkul.tapel.addmhsout', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'MatkulTapelController@AddMhsOut'
	]);
	
	Route::get('/kelaskuliah/{matkul_tapel_id}/peserta', [
	'roles' => ['dosen'], 
	'uses' =>'MatkulTapelController@pesertaKuliah'
	]);
	
	Route::get('/dosen/{id}/aktifitasmengajar', [
	'as' => 'dosen.aktifitasmengajar',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'DosenController@aktifitasMengajarDosen'
	]);
	
	// Route::get('/dosen/{id}/mahasiswa/add', [
	// 'as' => 'dosen.skripsi.mahasiswa.add',
	// 'roles' => ['administrator'], 
	// 'uses' =>'DosenSkripsiController@add'
	// ]);
	Route::post('/dosen/{id}/mahasiswa/add', [
	'as' => 'dosen.skripsi.mahasiswa.store',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'DosenSkripsiController@store'
	]);
	Route::get('/dosen/{id}/mahasiswa', [
	'as' => 'dosen.skripsi.mahasiswa',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'DosenSkripsiController@index'
	]);
	Route::get('/dosen/{id}/mahasiswa/{mahasiswa_id}/delete', [
	'as' => 'dosen.skripsi.mahasiswa.delete',
	'roles' => ['administrator', 'akademik'], 
	'uses' =>'DosenSkripsiController@destroy'
	]);
	
	Route::get('/kelaskuliah', [
	'roles' => ['dosen'], 
	'uses' =>'MatkulTapelController@mataKuliahDosen'
	]);
	Route::post('/kelaskuliah/{matkul_tapel_id}/upload/{tipe}', [
	'as' => 'matkul.tapel.upload',
	'roles' => ['administrator', 'akademik', 'dosen'], 
	'uses' =>'MatkulTapelController@uploadFile'
	]);
	Route::get('/kelaskuliah/{matkul_tapel_id}/upload/{tipe}', [
	'roles' => ['administrator', 'akademik', 'dosen'], 
	'uses' =>'MatkulTapelController@showFormUploadFile'
	]);
	
	/* 		Route::get('/kelaskuliah/{matkul_tapel_id}/nilai', [
	'roles' => ['dosen'], 
	'uses' =>'NilaiController@index'
	]);  */
	
	Route::get('matkul/tapel/{matkul_tapel_id}/nilai', [
	'as' => 'matkul.tapel.nilai', 
	'roles' => ['administrator', 'akademik', 'akademik', 'dosen'], 
	'uses' =>'NilaiController@index'
	]);
	
	Route::get('matkul/tapel/{matkul_tapel_id}/export', [
	'as' => 'matkul.tapel.export', 
	'roles' => ['administrator', 'akademik', 'akademik'],
	'uses' =>'NilaiController@export'
	]);
	
	Route::post('matkul/tapel/nilai/import', [
	'as' => 'matkul.tapel.nilai.import', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'NilaiController@import'
	]);
	
	Route::get('matkul/tapel/{matkul_tapel_id}/nilai/cetak', [
	'as' => 'matkul.tapel.nilai.cetak', 
	'roles' => ['administrator', 'dosen', 'akademik'], 
	'uses' =>'NilaiController@formNilai'
	]);
	Route::post('matkul/tapel/nilai/destroy', [
	'as' => 'matkul.tapel.nilai.destroy',
	'roles' => ['administrator', 'dosen', 'akademik'], 
	'uses' =>'NilaiController@destroy'
	]);
	Route::post('matkul/tapel/nilai/store', [
	'as' => 'matkul.tapel.nilai.store', 
	'roles' => ['administrator', 'dosen', 'akademik'],
	'uses' =>'NilaiController@store'
	]);
	Route::get('matkul/tapel/{matkul_tapel_id}/hitungnilai', [
	'as' => 'matkul.tapel.hitungnilai', 
	'roles' => ['administrator', 'dosen', 'akademik'], 
	'uses' =>'NilaiController@hitungNilaiAkhir'
	]);
	
	/*
	Kuesioner
	*/
	Route::get('/penilaian/{id?}/{mtid?}', [
	'as' => 'penilaian.index',
	'roles' => ['mahasiswa'], 
	'uses' =>'KuesionerMahasiswaController@penilaian'
	]);
	Route::patch('/penilaian/{id}', [
	'as' => 'penilaian.update',
	'roles' => ['mahasiswa'], 
	'uses' =>'KuesionerMahasiswaController@update'
	]);
	
	Route::get('kuesioner', [
	'as' => 'kuesioner.index',
	'roles' => ['administrator', 'p2m (pusat penjaminan mutu)'],
	'uses' => 'KuesionerController@index'
	]);
	Route::get('kuesioner/results', [
	'as' => 'kuesioner.results',
	'roles' => ['administrator', 'p2m (pusat penjaminan mutu)'],
	'uses' => 'KuesionerController@results'
	]);
	Route::get('kuesioner/result/{matkul_tapel_id}/detail', [
	'as' => 'kuesioner.result.detail',
	'roles' => ['administrator', 'p2m (pusat penjaminan mutu)'],
	'uses' => 'KuesionerController@detail'
	]);
	Route::get('kuesioner/result/{matkul_tapel_id}/detail2', [
	'as' => 'kuesioner.result.detail2',
	'roles' => ['administrator', 'p2m (pusat penjaminan mutu)'],
	'uses' => 'KuesionerController@detail2'
	]);
	Route::get('kuesioner/result/{tapel_id}/{mode?}', [
	'as' => 'kuesioner.result',
	'roles' => ['administrator', 'p2m (pusat penjaminan mutu)'],
	'uses' => 'KuesionerController@result'
	]);
	
	/* 	Route::get('kuesioner/result/prodi/{tapel_id}/{prodi_id?}/{mode?}/', [
	'as' => 'kuesioner.result.prodi',
	'roles' => ['administrator', 'p2m (pusat penjaminan mutu)'],
	'uses' => 'KuesionerController@result'
	]);
	Route::get('kuesioner/result/dosen/{tapel_id}/{dosen_id?}/{mode?}/', [
	'as' => 'kuesioner.result.dosen',
	'roles' => ['administrator', 'p2m (pusat penjaminan mutu)'],
	'uses' => 'KuesionerController@result'
	]); */
	
	Route::get('kuesioner/create', [
	'as' => 'kuesioner.create',
	'roles' => ['administrator', 'p2m (pusat penjaminan mutu)'],
	'uses' => 'KuesionerController@create'
	]);
	Route::post('kuesioner', [
	'as' => 'kuesioner.store',
	'roles' => ['administrator', 'p2m (pusat penjaminan mutu)'],
	'uses' => 'KuesionerController@store'
	]);
	Route::get('kuesioner/{id}/edit', [
	'as' => 'kuesioner.edit',
	'roles' => ['administrator', 'p2m (pusat penjaminan mutu)'],
	'uses' => 'KuesionerController@edit'
	]);
	Route::patch('kuesioner/{id}', [
	'as' => 'kuesioner.update',
	'roles' => ['administrator', 'p2m (pusat penjaminan mutu)'],
	'uses' => 'KuesionerController@update'
	]);
	
	/*
	Program Kerja
	*/
	Route::get('/program', [
	'as' => 'program.index',
	'roles' => ['keuangan / administrasi', 'akademik', 'kemahasiswaan', 'prodi', 'manajemen mutu'], 
	'uses' =>'ProgramController@index'
	]);
	Route::post('/program', [
	'as' => 'program.store',
	'roles' => ['keuangan / administrasi', 'akademik', 'kemahasiswaan', 'prodi', 'manajemen mutu'], 
	'uses' =>'ProgramController@store'
	]);
	Route::get('/program/edit', [
	'as' => 'program.edit',
	'roles' => ['keuangan / administrasi', 'akademik', 'kemahasiswaan', 'prodi', 'manajemen mutu'], 
	'uses' =>'ProgramController@edit'
	]);
	Route::post('/program/edit', [
	'as' => 'program.update',
	'roles' => ['keuangan / administrasi', 'akademik', 'kemahasiswaan', 'prodi', 'manajemen mutu'], 
	'uses' =>'ProgramController@update'
	]);
	
	/*
	Absensi
	*/
	Route::post('/kuliah/absensi/submit', [
	'as' => 'absensi.submit',
	'roles' => ['dosen'], 
	'uses' =>'AbsensiController@store'
	]);
	Route::get('/kelaskuliah/{matkul_tapel_id}/absensi', [
	'roles' => ['dosen'], 
	'uses' =>'AbsensiController@index'
	]);
	Route::get('/kelaskuliah/{matkul_tapel_id}/absensi/cetak', [
	'roles' => ['dosen', 'administrator', 'akademik'], 
	'uses' =>'AbsensiController@cetak'
	]);
	
	/*
	Jurnal perkuliahan
	*/
	Route::get('/kelaskuliah/{matkul_tapel_id}/jurnal', [
	'as' => 'matkul.tapel.jurnal.index',
	'roles' => ['dosen'], 
	'uses' =>'JurnalController@index'
	]);
	Route::get('/kelaskuliah/{matkul_tapel_id}/jurnal/create', [
	'as' => 'matkul.tapel.jurnal.create',
	'roles' => ['dosen'], 
	'uses' =>'JurnalController@create'
	]);
	Route::post('/kelaskuliah/{matkul_tapel_id}/jurnal', [
	'as' => 'matkul.tapel.jurnal.store',
	'roles' => ['dosen'], 
	'uses' =>'JurnalController@store'
	]);
	Route::get('/kelaskuliah/{matkul_tapel_id}/jurnal/{jurnal_id}/edit', [
	'as' => 'matkul.tapel.jurnal.edit',
	'roles' => ['dosen'], 
	'uses' => 'JurnalController@edit'
	]);
	Route::patch('/kelaskuliah/{matkul_tapel_id}/jurnal/{jurnal_id}', [
	'as' => 'matkul.tapel.jurnal.update',
	'roles' => ['dosen'], 
	'uses' => 'JurnalController@update'
	]);
	Route::get('/kelaskuliah/{matkul_tapel_id}/cetak/formjurnal', [
	'roles' => ['administrator', 'dosen'], 
	'uses' => 'JurnalController@printFormJurnal'
	]);
	Route::get('/kelaskuliah/{matkul_tapel_id}/jurnal/cetak', [
	'as' => 'matkul.tapel.jurnal.print',
	'roles' => ['dosen'], 
	'uses' => 'JurnalController@printJurnal'
	]);
	Route::get('/kelaskuliah/{matkul_tapel_id}/jurnal/{jurnal_id}', [
	'as' => 'matkul.tapel.jurnal.show',
	'roles' => ['dosen'], 
	'uses' => 'JurnalController@show'
	]);
	Route::get('/kelaskuliah/{matkul_tapel_id}/jurnal/{jurnal_id}/hapus', [
	'as' => 'matkul.tapel.jurnal.delete',
	'roles' => ['dosen'], 
	'uses' => 'JurnalController@destroy'
	]);
	
	/**
	* Dosen
	**/
	
	// profil dosen
	/* Route::get('/dosen/absensi', [
	'as' => 'dosen.absensi',
	'roles' => ['administrator', 'prodi'],
	'uses' => 'DosenController@absensi'
	]);	 */
	
	//Penelitian Dosen
	Route::get('/dosen/penelitian', [
	'as' => 'dosen.penelitian.index',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPenelitianController@index'
	]);
	Route::get('/dosen/{dosen}/penelitian', [
	'as' => 'dosen.penelitian',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPenelitianController@riwayat'
	]);
	Route::get('/dosen/penelitian/create', [
	'as' => 'dosen.penelitian.create',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPenelitianController@create'
	]);
	Route::post('/dosen/penelitian', [
	'as' => 'dosen.penelitian.store',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPenelitianController@store'
	]);
	Route::get('/dosen/penelitian/{penelitian}/edit', [
	'as' => 'dosen.penelitian.edit',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPenelitianController@edit'
	]);		
	Route::patch('/dosen/penelitian/{penelitian}', [
	'as' => 'dosen.penelitian.update',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPenelitianController@update'
	]);	
	Route::get('/dosen/penelitian/{penelitian}', [
	'as' => 'dosen.penelitian.show',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPenelitianController@show'
	]);	
	Route::get('/dosen/penelitian/export', [
	'as' => 'dosen.penelitian.export',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPenelitianController@export'
	]);

	//Buku Dosen
	Route::get('/dosen/buku', [
	'as' => 'dosen.buku.index',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenBukuController@index'
	]);
	Route::get('/dosen/{dosen}/buku', [
	'as' => 'dosen.buku',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenController@buku'
	]);
	Route::get('/dosen/buku/create', [
	'as' => 'dosen.buku.create',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenBukuController@create'
	]);
	Route::post('/dosen/buku', [
	'as' => 'dosen.buku.store',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenBukuController@store'
	]);
	Route::get('/dosen/buku/{buku}/edit', [
	'as' => 'dosen.buku.edit',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenBukuController@edit'
	]);		
	Route::patch('/dosen/buku/{buku}', [
	'as' => 'dosen.buku.update',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenBukuController@update'
	]);	
	Route::get('/dosen/buku/export', [
	'as' => 'dosen.buku.export',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenBukuController@export'
	]);	
	
	//Jurnal Dosen
	Route::get('/dosen/jurnal', [
	'as' => 'dosen.jurnal.index',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenJurnalController@index'
	]);
	Route::get('/dosen/{dosen}/jurnal', [
	'as' => 'dosen.jurnal',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenController@jurnal'
	]);
	Route::get('/dosen/jurnal/create', [
	'as' => 'dosen.jurnal.create',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenJurnalController@create'
	]);
	Route::post('/dosen/jurnal', [
	'as' => 'dosen.jurnal.store',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenJurnalController@store'
	]);
	Route::get('/dosen/jurnal/{jurnal}/edit', [
	'as' => 'dosen.jurnal.edit',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenJurnalController@edit'
	]);		
	Route::patch('/dosen/jurnal/{jurnal}', [
	'as' => 'dosen.jurnal.update',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenJurnalController@update'
	]);	
	Route::get('/dosen/jurnal/export', [
	'as' => 'dosen.jurnal.export',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenJurnalController@export'
	]);	
	
	//Penugasan Dosen
	Route::get('/dosen/penugasan/filter', [
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPenugasanController@filter'
	]);
	Route::get('/dosen/penugasan', [
	'as' => 'dosen.penugasan.index',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPenugasanController@index'
	]);
	Route::get('/dosen/{dosen}/penugasan', [
	'as' => 'dosen.penugasan',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPenugasanController@riwayat'
	]);
	Route::get('/dosen/penugasan/create', [
	'as' => 'dosen.penugasan.create',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPenugasanController@create'
	]);
	Route::post('/dosen/penugasan', [
	'as' => 'dosen.penugasan.store',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPenugasanController@store'
	]);
	Route::get('/dosen/penugasan/{penugasan}/edit', [
	'as' => 'dosen.penugasan.edit',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPenugasanController@edit'
	]);		
	Route::patch('/dosen/penugasan/{penugasan}', [
	'as' => 'dosen.penugasan.update',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPenugasanController@update'
	]);	
	Route::get('/dosen/penugasan/{id}/delete', [
	'as' => 'dosen.penugasan.delete',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPenugasanController@destroy'
	]);		
	Route::get('/dosen/penugasan/export', [
	'as' => 'dosen.penugasan.export',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPenugasanController@export'
	]);	
	Route::get('/dosen/penugasan/export', [
	'as' => 'dosen.penugasan.export',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPenugasanController@export'
	]);	
	
	//Pendidikan Dosen
	Route::get('/dosen/pendidikan', [
	'as' => 'dosen.pendidikan.index',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPendidikanController@index'
	]);
	Route::get('/dosen/{dosen}/pendidikan', [
	'as' => 'dosen.pendidikan',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPendidikanController@riwayat'
	]);
	Route::get('/dosen/{dosen}/pendidikan/create', [
	'as' => 'dosen.pendidikan.create',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPendidikanController@create'
	]);
	Route::post('/dosen/{dosen}/pendidikan', [
	'as' => 'dosen.pendidikan.store',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPendidikanController@store'
	]);
	Route::get('/dosen/{dosen}/pendidikan/{pendidikan}/edit', [
	'as' => 'dosen.pendidikan.edit',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPendidikanController@edit'
	]);		
	Route::patch('/dosen/{dosen}/pendidikan/{pendidikan}', [
	'as' => 'dosen.pendidikan.update',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPendidikanController@update'
	]);	
	Route::get('/dosen/{dosen}/pendidikan/{pendidikan}', [
	'as' => 'dosen.pendidikan.show',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPendidikanController@show'
	]);	
	
	//Riwayat Fungsional Dosen
	Route::get('/dosen/fungsional', [
	'as' => 'dosen.fungsional.index',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenFungsionalController@index'
	]);
	Route::get('/dosen/{dosen}/fungsional', [
	'as' => 'dosen.fungsional',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenFungsionalController@riwayat'
	]);
	Route::get('/dosen/{dosen}/fungsional/create', [
	'as' => 'dosen.fungsional.create',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenFungsionalController@create'
	]);
	Route::post('/dosen/{dosen}/fungsional', [
	'as' => 'dosen.fungsional.store',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenFungsionalController@store'
	]);
	Route::get('/dosen/{dosen}/fungsional/{fungsional}/edit', [
	'as' => 'dosen.fungsional.edit',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenFungsionalController@edit'
	]);		
	Route::patch('/dosen/{dosen}/fungsional/{fungsional}', [
	'as' => 'dosen.fungsional.update',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenFungsionalController@update'
	]);	
	Route::get('/dosen/{dosen}/fungsional/{fungsional}', [
	'as' => 'dosen.fungsional.show',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenFungsionalController@show'
	]);	
	
	//Riwayat Kepangkatan Dosen
	Route::get('/dosen/kepangkatan', [
	'as' => 'dosen.kepangkatan.index',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenKepangkatanController@index'
	]);
	Route::get('/dosen/{dosen}/kepangkatan', [
	'as' => 'dosen.kepangkatan',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenKepangkatanController@riwayat'
	]);
	Route::get('/dosen/{dosen}/kepangkatan/create', [
	'as' => 'dosen.kepangkatan.create',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenKepangkatanController@create'
	]);
	Route::post('/dosen/{dosen}/kepangkatan', [
	'as' => 'dosen.kepangkatan.store',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenKepangkatanController@store'
	]);
	Route::get('/dosen/{dosen}/kepangkatan/{kepangkatan}/edit', [
	'as' => 'dosen.kepangkatan.edit',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenKepangkatanController@edit'
	]);		
	Route::patch('/dosen/{dosen}/kepangkatan/{kepangkatan}', [
	'as' => 'dosen.kepangkatan.update',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenKepangkatanController@update'
	]);	
	Route::get('/dosen/{dosen}/kepangkatan/{kepangkatan}', [
	'as' => 'dosen.kepangkatan.show',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenKepangkatanController@show'
	]);	
	
	//Riwayat Sertifikasi Dosen
	Route::get('/dosen/sertifikasi', [
	'as' => 'dosen.sertifikasi.index',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenSertifikasiController@index'
	]);
	Route::get('/dosen/{dosen}/sertifikasi', [
	'as' => 'dosen.sertifikasi',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenSertifikasiController@riwayat'
	]);
	Route::get('/dosen/{dosen}/sertifikasi/create', [
	'as' => 'dosen.sertifikasi.create',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenSertifikasiController@create'
	]);
	Route::post('/dosen/{dosen}/sertifikasi', [
	'as' => 'dosen.sertifikasi.store',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenSertifikasiController@store'
	]);
	Route::get('/dosen/{dosen}/sertifikasi/{sertifikasi}/edit', [
	'as' => 'dosen.sertifikasi.edit',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenSertifikasiController@edit'
	]);		
	Route::patch('/dosen/{dosen}/sertifikasi/{sertifikasi}', [
	'as' => 'dosen.sertifikasi.update',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenSertifikasiController@update'
	]);	
	Route::get('/dosen/{dosen}/sertifikasi/{sertifikasi}', [
	'as' => 'dosen.sertifikasi.show',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenSertifikasiController@show'
	]);	
	
	
	//
	Route::get('/profildosen', [
	'as' => 'dosen.public',
	'roles' => ['keuangan / administrasi', 'prodi', 'administrator'],
	'uses' => 'DosenController@index'
	]);	
	Route::get('/gajidosen', [
	'as' => 'dosen.gaji',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'DosenController@gaji'
	]);	
	
	
	Route::get('/dosen/{dosen}/delete', [
	'as' => 'dosen.delete',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenController@delete'
	]);
	Route::post('/dosen/search', [
	'roles' => ['administrator', 'akademik', 'keuangan / administrasi'],
	'uses' => 'DosenController@preSearch'
	]);
	Route::get('/dosen/search/{q}', [
	'roles' => ['administrator', 'akademik', 'keuangan / administrasi'],
	'as' => 'dosen.search', 
	'uses' => 'DosenController@search'
	]);
	
	
	/* Akademik */
	/** File **/
	Route::post('/upload/file',[ 
	'as' => 'uploadfile', 
	'roles' => ['administrator', 'akademik'],
	'uses' => 'FileEntryController@upload'
	]);
	Route::get('file/delete/{id}', [
	'roles' => ['administrator', 'akademik'],
	'uses' => 'FileEntryController@delete'
	]);
	Route::get('file', [
	'as' => 'indexfile',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'FileEntryController@index'
	]);
	Route::get('/upload/file',[ 
	'roles' => ['administrator', 'akademik'],
	'uses' => 'FileEntryController@uploadForm'
	]);
	
	/** Kalender **/
	Route::get('/kalender/create', [
	'as' => 'kalender.create',
	'roles' => ['administrator', 'akademik', 'kemahasiswaan'],
	'uses' => 'KalenderController@create'
	]);	
	Route::post('/kalender', [
	'as' => 'kalender.store',
	'roles' => ['administrator', 'akademik', 'kemahasiswaan'],
	'uses' => 'KalenderController@store'
	]);	
	Route::get('/kalender/{id}/edit', [
	'as' => 'kalender.edit',
	'roles' => ['administrator', 'akademik', 'kemahasiswaan'],
	'uses' => 'KalenderController@edit'
	]);	
	Route::patch('/kalender/{id}', [
	'as' => 'kalender.update',
	'roles' => ['administrator', 'akademik', 'kemahasiswaan'],
	'uses' => 'KalenderController@update'
	]);	
	Route::delete('/kalender/{id}', [
	'as' => 'kalender.destroy',
	'roles' => ['administrator', 'akademik', 'kemahasiswaan'],
	'uses' => 'KalenderController@destroy'
	]);		
	Route::get('/kalender/{tahun?}', [
	'as' => 'kalender.index',
	'roles' => ['administrator', 'akademik', 'kemahasiswaan'],
	'uses' => 'KalenderController@index'
	]);	
	
	/*
	Admin
	*/
	Route::get('/mahasiswa/{mahasiswa_id}/delete', [
	'as' => 'mahasiswa.delete', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'MahasiswaController@destroy'
	]);
	
	/* 
	//TSemester
	Route::post('/mahasiswa/moveto/semester', [
	'as' => 'mahasiswa.movetosemester', 
	'roles' => ['administrator'],
	'uses' =>'MahasiswaController@moveToSemester'
	]);
	Route::get('/mahasiswa/semester', [
	'roles' => ['administrator'],
	'uses' => 'MahasiswaController@semesterTransfers'
	]);
	Route::post('/mahasiswa/semester/', [
	'roles' => ['administrator'],
	'uses' => 'MahasiswaController@getBySemester'
	]);
	
	//TKelas
	Route::post('/mahasiswa/moveto/kelas', [
	'as' => 'mahasiswa.movetokelas', 
	'roles' => ['administrator'],
	'uses' =>'MahasiswaController@moveToKelas'
	]);
	Route::get('/mahasiswa/kelas', [
	'roles' => ['administrator'],
	'uses' => 'MahasiswaController@kelasTransfers'
	]);
	Route::post('/mahasiswa/kelas/', [
	'roles' => ['administrator'],
	'uses' => 'MahasiswaController@getByKelas'
	]);
	*/
	
	
	/** Users **/
	
	//Reset Password 
	//target =mahasiswa||dosen
	Route::get('/pengguna/cetakpassword', [
	'as' => 'password.print',
	'roles' => ['administrator'],
	'uses' => 'UsersController@printPassword'
	]);	
	Route::get('/pengguna/resetpassword/{target}/{filter?}', [
	'as' => 'password.reset',
	'roles' => ['administrator'],
	'uses' => 'UsersController@resetPassword'
	]);	
	Route::post('/pengguna/resetpassword/{target}/{filter?}', [
	'as' => 'password.reset.result',
	'roles' => ['administrator'],
	'uses' => 'UsersController@resetPasswordProses'
	]);	
	Route::post('/pengguna/cari', [
	'as' => 'password.reset.caripengguna',
	'roles' => ['administrator'],
	'uses' => 'UsersController@cariPengguna'
	]);	
	
	Route::get('/pengguna/create', [
	'as' => 'pengguna.create',
	'roles' => ['administrator'],
	'uses' => 'UsersController@create'
	]);	
	Route::post('/pengguna', [
	'as' => 'pengguna.store',
	'roles' => ['administrator'],
	'uses' => 'UsersController@store'
	]);	
	Route::get('/pengguna/{id}/edit', [
	'as' => 'pengguna.edit',
	'roles' => ['administrator'],
	'uses' => 'UsersController@edit'
	]);	
	Route::get('/pengguna/{id}', [
	'as' => 'pengguna.show',
	'roles' => ['administrator'],
	'uses' => 'UsersController@show'
	]);	
	Route::patch('/pengguna/{id}', [
	'as' => 'pengguna.update',
	'roles' => ['administrator'],
	'uses' => 'UsersController@update'
	]);	
	Route::delete('/pengguna/{id}', [
	'as' => 'pengguna.destroy',
	'roles' => ['administrator'],
	'uses' => 'UsersController@destroy'
	]);		
	Route::get('/pengguna', [
	'as' => 'pengguna.index',
	'roles' => ['administrator'],
	'uses' => 'UsersController@index'
	]);	
	Route::post('/users/search', [
	'roles' => ['administrator'],
	'uses' => 'UsersController@preSearch'
	]);
	Route::get('/users/search/{q}', [
	'roles' => ['administrator'],
	'as' => 'user.search', 
	'uses' => 'UsersController@search'
	]);
	
	//Dosen
	
	Route::get('/dosen/export', [
	'as' => 'dosen.export',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenController@getExport'
	]);
	Route::post('/dosen/export', [
	'as' => 'dosen.export',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenController@postExport'
	]);	
	
	Route::get('/perwalian', [
	'as' => 'dosen.custodian',
	'roles' => ['dosen'],
	'uses' => 'MahasiswaController@custodian'
	]);	
	
	Route::get('/dosen/create', [
	'as' => 'dosen.create',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenController@create'
	]);	
	Route::post('/dosen', [
	'as' => 'dosen.store',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenController@store'
	]);	
	Route::get('/dosen/{id}', [
	'as' => 'dosen.show',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenController@show'
	]);	
	Route::get('/dosen/{id}/edit', [
	'as' => 'dosen.edit',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenController@edit'
	]);	
	Route::patch('/dosen/{id}', [
	'as' => 'dosen.update',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenController@update'
	]);	
	Route::delete('/dosen/{id}', [
	'as' => 'dosen.destroy',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenController@destroy'
	]);		
	Route::get('/dosen', [
	'as' => 'dosen.index',
	'roles' => ['administrator', 'akademik', 'ketua', 'keuangan / administrasi'],
	'uses' => 'DosenController@index'
	]);	
	
	//MATKUL
	Route::get('/matkul/search', [
	'roles' => ['administrator', 'akademik'], 		
	'uses' => 'MatkulController@preSearch'
	]);
	Route::get('/matkul/search/{q}', [
	'as' => 'matkul.search', 
	'roles' => ['administrator', 'akademik'], 		
	'uses' => 'MatkulController@search'
	]);
	
	Route::get('/matkul/filter', [
	'as' => 'matkul.filtering',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'MatkulController@filtering'
	]);	
	
	Route::get('/matkul/create', [
	'as' => 'matkul.create',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'MatkulController@create'
	]);	
	Route::post('/matkul', [
	'as' => 'matkul.store',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'MatkulController@store'
	]);	
	Route::get('/matkul/{id}', [
	'as' => 'matkul.show',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'MatkulController@show'
	]);	
	Route::get('/matkul/{id}/edit', [
	'as' => 'matkul.edit',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'MatkulController@edit'
	]);	
	Route::patch('/matkul/{id}', [
	'as' => 'matkul.update',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'MatkulController@update'
	]);	
	Route::get('/matkul/{id}/delete', [
	'as' => 'matkul.delete',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'MatkulController@destroy'
	]);		
	Route::get('/matkul', [
	'as' => 'matkul.index',
	'roles' => ['administrator', 'akademik', 'ketua', 'keuangan / administrasi'],
	'uses' => 'MatkulController@index'
	]);	
	
	//MAIL	
	Route::get('/mail', [
	'as' => 'mail.index',
	'roles' => ['administrator', 'ketua', 'keuangan / administrasi'],
	'uses' => 'MailController@index'
	]);
	Route::get('/mail/compose', [
	'as' => 'mail.create',
	'roles' => ['administrator', 'ketua', 'keuangan / administrasi'],
	'uses' => 'MailController@create'
	]);
	Route::post('/mail/compose', [
	'as' => 'mail.store',
	'roles' => ['administrator', 'ketua', 'keuangan / administrasi'],
	'uses' => 'MailController@store'
	]);
	
	//BACKUP	
	Route::get('/backup', [
	'as' => 'backup.index',
	'roles' => ['root'],
	'uses' => 'BackupController@index'
	]);
	Route::get('/backup/create', [
	'as' => 'backup.create',
	'roles' => ['root'],
	'uses' => 'BackupController@create'
	]);
	
	Route::get('/backup/restore/{id}', [
	'as' => 'backup.restore',
	'roles' => ['root'],
	'uses' => 'BackupController@restore'
	]);
	
	Route::get('/backup/delete/{id}', [
	'as' => 'backup.delete',
	'roles' => ['root'],
	'uses' => 'BackupController@destroy'
	]);
	
	Route::get('/backup/export/{id}', [
	'as' => 'backup.export',
	'roles' => ['root'],
	'uses' => 'BackupController@export'
	]);
	
	Route::get('/backup/import', [
	'as' => 'backup.importform',
	'roles' => ['root'],
	'uses' => 'BackupController@importForm'
	]);
	Route::post('/backup/import', [
	'as' => 'backup.import',
	'roles' => ['root'],
	'uses' => 'BackupController@import'
	]);
	});
	
	Route::model('tapel', 'Tapel');
	Route::bind('tapel', function($value, $route){
	return Siakad\Tapel::whereId($value)->first();
	});
	Route::resource('tapel', 'TapelController');																																																																		
