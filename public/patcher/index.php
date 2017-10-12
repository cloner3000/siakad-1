<?php
	$version = 'v2.5';
	date_default_timezone_set('Asia/Jakarta');
?>
<!DOCTYPE html>
<head>
	<title>Siakad <?php echo $version; ?> Patcher</title>
	<style>
		body { padding: 30px }
		form { 
		display: block; 
		margin: 20px auto; 
		background: #eee; 
		border-radius: 10px; 
		padding: 15px 
		}
		h1 img{
		height: 32px;
		vertical-align: bottom;
		}
		
		.progress { position:relative; width:100%; border: 1px solid #ddd; padding: 1px; border-radius: 3px; }
		.bar { background-color: #B4F5B4; width:0%; height:20px; border-radius: 3px; }
		.percent { position:absolute; display:inline-block; top:3px; left:48%; }
		
	</style>
</head>
<body>
	<?php
		/*
			
			USING 7z
			28Jan2017
			
		*/
		function logger($str)
		{
			//write log
			$log = $str = '' ? PHP_EOL : '[' . date('Y-m-d H:i:s') . '] ' . $str . PHP_EOL;
			file_put_contents('patcher.log', $log, FILE_APPEND | LOCK_EX);
		}
		
		error_reporting(E_ALL | E_STRICT);
		if(isset($_POST['pass']) and $_POST['pass'] != '' and $_FILES["myfile"]["name"] != '')
		{
			$start = microtime(true); 
			$hash = '$2y$10$2u7R0Bqvaj9lGmVUjq7FcOqfPBjm5mUpv4Y/mRx47tgFukZ0xPll2';
			if (password_verify($_POST['pass'], $hash)) 
			{
				$now = date('YmdHis');
				$filename = $_FILES["myfile"]["name"];
				$dir = __DIR__;
				$file = $dir . '/' . $filename;
				$sql = $dir .'/../../' . basename($filename, '.7z') . '.sql';
				$config = parse_ini_file (__DIR__ . '/../../.env');
				
				logger('');
				logger('Start patching with ' . $filename);
				
				//bring application down
				logger('Bringing Application down.');
				@touch($dir . '/../../storage/framework/down');
				
				if (move_uploaded_file($_FILES["myfile"]["tmp_name"], $file)) 
				{
					logger('Upload file OK');
				} 
				else 
				{
					echo "Sorry, there was an error uploading your file.";
					logger('Upload file FAILED');
					exit;
				}			
				
				if(file_exists($file))
				{
					//rename
					if(file_exists('../../app')) rename('../../app', '../../_bak/app_' . $now);
					if(file_exists('../../config')) rename('../../config', '../../_bak/config_' . $now);
					if(file_exists('../../resources')) rename('../../resources', '../../_bak/resources_' . $now);
					
					if (stripos(PHP_OS, 'Linux') !== false) {
						logger('Linux OS detected.');
						// if (PHP_INT_SIZE == 4) {
						$exe = '7za';
						$exe2 = 'mysql';
						// } elseif (PHP_INT_SIZE == 8) {
						// $exe = './bin/nix/genbarcode64';
						// } else {
						// $exe = './bin/nix/genbarcode';
						// }
						} else {
						logger('Not linux, should be Windows OS.');
						$exe = '7z.exe';
						$exe2 = 'mysql.exe';
					}
					
					try {
						exec($dir . '/../../bin/' . $exe .' x -o'. $dir .'/../../ ' . $filename . ' 2>&1', $output);
						logger('Extracting file OK');
						
						} catch (Exception $e) {
						echo $e -> getMessage();
						logger('Extracting file FAILED. Error: ' . $e -> getMessage());
					}	
					
					if(file_exists($sql))
					{
						logger('Database file found, Importing ....');
						try {
							exec($dir . '/../../bin/' . $exe2 .' -u ' . $config['DB_USERNAME'] . ' -p' . $config['DB_PASSWORD'] . ' ' . $config['DB_DATABASE'] . ' < '. $sql . ' 2>&1', $output);
							logger('Importing database file OK');
							
							} catch (Exception $e) {
							echo $e -> getMessage();
							logger('Importing database file FAILED. Error: ' . $e -> getMessage());
						}	
					}
					
					@chmod('../../app', 0755);
					@chmod('../../config', 0755);
					@chmod('../../resources', 0755);
					@unlink($filename);
					@unlink($sql);
					
					//bring application up
					logger('Bringing Application up.');
					@unlink($dir . '/../../storage/framework/down');
					
					logger('Patching success !');
				}			
				else
				{
					echo 'File not found';	
					logger('Patching FAILED. File not found');
				}				
			}				
			else
			{
				echo 'Wrong Password';	
				logger('Patching FAILED. Wrong Password');
			}
			echo "Patch finished (" . round((microtime(true) - $start), 2) . " sec)";	
		}			
		else
		{
			if(isset($_POST['submit']) and $_POST['submit'] == 'Upload') 
			{
				if($_FILES['myfile']['name'] == '') echo 'Please choose file';
				echo 'Please Enter password';
			}
		?>
		<h1>Siakad <?php echo $version; ?> Patcher <?php if($_SERVER['SERVER_ADDR'] != '127.0.0.1') {?><img class="blink" src="satellite.gif" /><?php } ?></h1>
		<form action="#" method="post" enctype="multipart/form-data">
			<input type="file" name="myfile"><br>
			<input type="password" name="pass" placeholder="Password" /><br>
			<input type="submit" name="submit" value="Upload">
		</form>
		
		<div class="progress">
			<div class="bar"></div >
			<div class="percent">0%</div >
		</div>
		
		<div id="status"></div>
		
		<script src="/js/jquery-2.2.3.min.js"></script>
		<script src="/js/jquery.form.min.js"></script>
		<script>
			(function() {
				
				var bar = $('.bar');
				var percent = $('.percent');
				var status = $('#status');
				
				$('form').ajaxForm({
					beforeSend: function() {
						status.empty();
						var percentVal = '0%';
						bar.width(percentVal)
						percent.html(percentVal);
					},
					uploadProgress: function(event, position, total, percentComplete) {
						var percentVal = percentComplete + '%';
						bar.width(percentVal)
						percent.html(percentVal);
					},
					success: function() {
						var percentVal = '100%';
						bar.width(percentVal)
						percent.html(percentVal);
					},
					complete: function(xhr) {
						status.html(xhr.responseText);
					}
				}); 
				
			})();       
		</script>	
		<?php
		}
	?>
</body>
</html>																											