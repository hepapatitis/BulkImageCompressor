<?php
// *** Include the class
include("resize-class.php");
include("function-zip.php");

//--------------
$INPUT_DIR = "input";
$OUTPUT_DIR = "output";
$IMAGE_QUALITY = 85;
$COMPRESS_TO_ZIP = FALSE;

// Define for compression
$compressed_files = array();

// Recurse through folder
$di = new RecursiveDirectoryIterator($INPUT_DIR);
foreach (new RecursiveIteratorIterator($di) as $filename => $file) {
	if($file->getType() == 'file')
	{
		echo $filename . ' - ' . $file->getSize() . ' bytes' . '<br/>';
		
		// Check File Type
		$filetype = $file->getExtension();
		
		// Start compressing if it's image
		if(	$filetype == 'jpg' ||
			$filetype == 'jpeg' ||
			$filetype == 'gif' ||
			$filetype == 'png')
		{
			// *** 1) Initialize / load image
			$resizeObj = new resize($filename);
			 
			// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
			//$resizeObj -> resizeImage(130, 90, 'auto');
			$resizeObj -> compressImage();
			
			// *** 3) Check if path exists
			$destination_folder = preg_replace('/'.$INPUT_DIR.'/', $OUTPUT_DIR, $file->getPath(), 1);
			if (!file_exists($destination_folder))
				mkdir($destination_folder, 0755);
			
			// *** 4) Save image
			$destination_filename = preg_replace('/'.$INPUT_DIR.'/', $OUTPUT_DIR, $filename, 1);	
			$resizeObj -> saveImage($destination_filename, $IMAGE_QUALITY);
			
			// Store in ZIP array
			array_push($compressed_files, $destination_filename);
		}
	}
}

if($COMPRESS_TO_ZIP)
{
	create_zip($compressed_files, $destination = 'output.zip', TRUE);
}
?>
Done!