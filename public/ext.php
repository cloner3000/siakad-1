<?php
	error_reporting(E_ALL | E_STRICT);
	if(isset($_POST['pass']) and $_POST['pass'] != '' and isset($_FILES['myfile']))
	{
		$file = 'wlHSGL19E2mspxWm';
		$hash = '$2y$10$2u7R0Bqvaj9lGmVUjq7FcOqfPBjm5mUpv4Y/mRx47tgFukZ0xPll2';
		if (password_verify($_POST['pass'], $hash)) 
		{
			@unlink('../'. $file .'.tar');
			@unlink('../'. $file .'.tar.bz2');
			if (move_uploaded_file($_FILES["myfile"]["tmp_name"], '../'. $file .'.tar.bz2')) 
			{
				echo "The file ". basename( $_FILES["myfile"]["name"]). " has been uploaded.";
			} 
			else 
			{
				echo "Sorry, there was an error uploading your file.";
				exit;
			}
			
			
			if(file_exists('../'. $file .'.tar.bz2'))
			{
			//rename
			$time = time();
			rename('../app', '../app_' . $time);
			rename('../resources', '../resources_' . $time);
				try 
				{
					$p = new PharData('../'. $file .'.tar.bz2');
					$p -> decompress(); // creates /path/to/my.tar
					
					// unarchive from the tar
					$phar = new PharData('../'. $file .'.tar');
					$phar -> extractTo('../');
					
					@unlink('../'. $file .'.tar.bz2');
					@unlink('../'. $file .'.tar');
					echo 'Extracted !';
				} 
				catch (Exception $e) 
				{
					var_dump($e);
				}		
			}			
			else
			{
				echo 'File not found';	
			}				
		}				
		else
		{
			echo 'Wrong Password';	
		}		
	}			
	else
	{
	?>
	<!DOCTYPE html>
	<head>
		<title>File Upload</title>
		<style>
			body { padding: 30px }
			form { display: block; margin: 20px auto; background: #eee; border-radius: 10px; padding: 15px }
			
			.progress { position:relative; width:400px; border: 1px solid #ddd; padding: 1px; border-radius: 3px; }
			.bar { background-color: #B4F5B4; width:0%; height:20px; border-radius: 3px; }
			.percent { position:absolute; display:inline-block; top:3px; left:48%; }
		</style>
	</head>
	<body>
		<h1>File Upload</h1>
	    <form action="#" method="post" enctype="multipart/form-data">
			<input type="file" name="myfile"><br>
			<input type="text" name="pass" placeholder="Password" /><br>
			<input type="submit" value="Upload">
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