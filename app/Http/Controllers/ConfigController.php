<?php namespace Siakad\Http\Controllers;
	
	use Input;
	use Cache;
	use Redirect;
	
	use Imagine\Image\Box;
	use Imagine\Image\ImageInterface;
	use Orchestra\Imagine\Facade as Imagine;
	
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Contracts\Foundation\Application;
	use Illuminate\Http\Request;
	class ConfigController extends Controller {
		
		public function __construct(Application $app)
		{
			$this -> maintenis = $app -> storagePath().'/framework/maintenis';
		}
		
		public function edit()
		{
			$configs = config('custom');
			$status = '0';	
			$checked_roles = [];
			$message = '';
			
			if(file_exists($this -> maintenis))
			{
				$info = parse_ini_file($this -> maintenis);
				if($info)
				{
					$status = $info['mode'];	
					$checked_roles = isset($info['allowed']) ? $info['allowed'] : [];
					$message = isset($info['message']) ? $info['message'] : '';
				}
			}
			
			$tmp = \Siakad\Role::whereNotIn('id', [1, 2, 1024, 2048, 4096]) -> get();
			foreach($tmp as $a) $roles[$a->id] = $a -> name . ' ' . $a -> sub; 
			
			return view('config.edit', compact('configs', 'status', 'roles', 'checked_roles', 'message'));
		}
		
		public function update(Request $request)
		{
			$input = Input::except(['_token', '_method']);
			
			//Maintenis
			if($input['status'] == 0)
			{
				if(file_exists($this -> maintenis)) unlink($this -> maintenis);
			}
			else
			{
				$str = 'mode=' . $input['status'] . PHP_EOL;
				$str .= 'time=' . date('Y-m-d H:i:s') . PHP_EOL;
				$str .= 'admin=' . PHP_EOL;
				if($input['status'] == 1)
				{
					if(isset($input['roles']))
					{
						foreach($input['roles'] as $role)
						{
							$str .= 'allowed[]=' . $role . PHP_EOL;
						}
					}
					else $str .= 'allowed[]=' . PHP_EOL;
				}
				else
				{
					$str .= 'allowed[]=' . PHP_EOL;
				}
				$str .= 'message=' . $input['message'] . PHP_EOL;
				file_put_contents($this -> maintenis, $str);
			}
			
			unset($input['status']);
			unset($input['roles']);
			unset($input['message']);
			
			//Aplikasi
			if(isset($input['logo']))
			{
				$rules['logo'] = ['image'];
				$this -> validate($request, $rules);
				
				$image = Imagine::open($input['logo']);
				$logo = new Box(200, 200);
				$image -> thumbnail($logo, ImageInterface::THUMBNAIL_OUTBOUND) -> save('images/logo.png');
				
				$logo64 = new Box(64, 64);
				$image -> thumbnail($logo64, ImageInterface::THUMBNAIL_OUTBOUND) -> save('images/logo64px.png');
				
				unset($input['logo']);
			}
			if(isset($input['header']))
			{
				$rules['header'] = ['image'];
				$this -> validate($request, $rules);
				
				$image = Imagine::open($input['header']);
				$header = new Box(780, 150);
				$image -> thumbnail($header, ImageInterface::THUMBNAIL_OUTBOUND) -> save('images/header.png');
				
				unset($input['header']);
			}
			
			//CONFIG
			$input['app_updated'] = time();
			$configs = config('custom');
			$keys = array_keys($input);
			
			foreach($keys as $key)
			{
				array_set($configs, str_replace('_', '.', $key), strip_tags($input[$key], "<b><i><u><strong><em><br><center><ol><ul><li>"));
			}		
			
			$str = '<?php '. PHP_EOL .'$config = ' . var_export($configs, true) . ';';
			file_put_contents('../storage/app/config.php', $str);
			
			
			// return Redirect::route('config.edit') -> with('message', 'Pengaturan berhasil disimpan');
			return view('config.edited');
		}
	}
