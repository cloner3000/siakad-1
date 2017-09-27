<strong>Selamat {{ $greeting }}</strong>,<br/>
Permintaan reset password {{ $data['config']['app']['abbr'] }} {{ htmlspecialchars_decode($data['config']['app']['title'], ENT_QUOTES) }} 
telah dikirimkan dari alamat IP {{ $data['ip'] }}, jika anda tidak melakukannya anda dapat mengabaikan email ini.<br/>
Jika anda ingin me-reset password anda, silahkan klik link berikut ini: <br/>
{!! link_to(url('/password/reset/' . $data['username'] . '/' . $data['reset_token']), url('/password/reset/' . $data['username'] . '/' . $data['reset_token'])) !!}<br/>
<br/>
Untuk pertanyaan lebih lanjut hubungi Sekretariat {{ $data['config']['profil']['nama'] }} di:<br/>
<address class="center-block">
	{{ $data['config']['profil']['alamat']['jalan'] }} {{ $data['config']['profil']['alamat']['kabupaten'] }}<br/>
	<strong>Telepon:</strong> {{ $data['config']['profil']['telepon'] }}<br/>
	<strong>Email:</strong> {!! HTML::mailto($data['config']['profil']['email']) !!}<br/>
	<strong>Website:</strong> {!! link_to($data['config']['profil']['website'], $data['config']['profil']['website']) !!}<br/>
	<strong>Fabecook:</strong> {!! link_to($data['config']['profil']['facebook'], $data['config']['profil']['facebook'], ['title' => 'Facebook ' . $data['config']['profil']['singkatan']]) !!}<br/>
	<strong>Twtiter:</strong> {!! link_to($data['config']['profil']['twitter'], $data['config']['profil']['twitter'], ['title' => 'Twitter ' . $data['config']['profil']['singkatan']]) !!}<br/>
</address>
<br/>
<br/>
<em><strong>Perhatian:</strong> Email ini dikirimkan secara otomatis oleh sistem, mohon untuk tidak mengirimkan pertanyaan atau balasan ke alamat email ini. </em>

