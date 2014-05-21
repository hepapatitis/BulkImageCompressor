<?php
// Default Value
$DEFAULT_IMAGE_QUALITY = 85;

// Init Variable
$dir = $_POST['d']; // d = folder directory

if(isset($_POST['iq']))
	$iq = $_POST['iq']; // iq = image quality
else
	$iq = $DEFAULT_IMAGE_QUALITY;

// Check Image Quality
if(!is_numeric($iq))
	$iq = $DEFAULT_IMAGE_QUALITY;
else if($iq < 0)
	$iq = 0;
else if($iq > 100)
	$iq = 100;

// *** Include the class
include("resize-class.php");
include("function-zip.php");
include("function-etc.php");

//--------------
$INPUT_DIR_ROOT = "input";
$OUTPUT_DIR_ROOT = "output";
$INPUT_DIR = $INPUT_DIR_ROOT . "/" . $dir;
$OUTPUT_DIR = $OUTPUT_DIR_ROOT . "/" . $dir;
$IMAGE_QUALITY = 85;
$COMPRESS_TO_ZIP = TRUE;

// Define for compression
$compressed_files = array();

// Recurse through folder
$di = new RecursiveDirectoryIterator($INPUT_DIR);
foreach (new RecursiveIteratorIterator($di) as $filename => $file) {
	if($file->getType() == 'file')
	{
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
			$destination_folder = preg_replace('/'.$INPUT_DIR_ROOT.'/', $OUTPUT_DIR_ROOT, $file->getPath(), 1);
			
			if (!file_exists($destination_folder))
				mkdir($destination_folder, 0755);
			
			// *** 4) Save image
			$destination_filename = preg_replace('/'.$INPUT_DIR_ROOT.'/', $OUTPUT_DIR_ROOT, $filename, 1);	
			$resizeObj -> saveImage($destination_filename, $IMAGE_QUALITY);
			
			// Store in ZIP array
			array_push($compressed_files, $destination_filename);
		}
	}
}

$zip_file = "";
if($COMPRESS_TO_ZIP)
{
	$zip_file = "{$destination_folder}/compressed.zip";
	create_zip($compressed_files, "{$destination_folder}/compressed.zip", TRUE);
?>
Tadaaa! <a href="<?php echo $zip_file; ?>">Download your compressed zip file here</a> (<?php echo FileSizeConvert(filesize($zip_file)); ?>)
<?php	
}
?>