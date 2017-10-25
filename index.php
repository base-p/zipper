<?php  
 echo '<!DOCTYPE html>
<html >
	<head>
        <title>page processor</title>
    </head>

    <body> ';
	
	
	echo '<form action="formprocessor.php" class="form" id="ccform" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
			<div style="display:none;"><input name="_method" value="POST" type="hidden"></div> 
			
			<div id="uploadentry"><input id="autobformf" name="image" type="file">    </div>
			<button type="submit" class="autoboursebtn">Submit</button>
		</form>';
		
	echo '</body>
</html>';

