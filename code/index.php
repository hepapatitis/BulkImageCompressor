<?php
$d = date("Y-m-d-H-i-s");
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/main.css">
		<link href="css/dropzone.css" type="text/css" rel="stylesheet" />
		
		<script src="js/dropzone.min.js"></script>
		<script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Project name</a>
        </div>
		<!--/.navbar-collapse -->
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-xs-12">
		  <form action="upload.php?d=<?php echo $d;?>" class="dropzone" id="dropzone"></form>
		  <div id="process-button" style="display:none;">
			<button type="button" onclick="xz()" class="btn btn-default">Process</button>
		  </div>
		  
		  <h3>Output</h3>
		  <div id="result">
		  </div>
        </div>
      </div>

      <hr>

      <footer>
        <p>&copy; Company 2014</p>
      </footer>
    </div> <!-- /container -->        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.0.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>
        <script src="js/main.js"></script>
		<script>
			var total = 0;
			var Dropzone = window.Dropzone;
			Dropzone.options.dropzone = {
				acceptedFiles: '.zip,.jpeg,.jpg,.gif,.png',
				success: function(file, response){
					if(response.code == 501){ // succeeded
						return file.previewElement.classList.add("dz-success"); // from source
					}
					else if (response.code == 403){    //  error
						// below is from the source code too
						var node, _i, _len, _ref, _results;
						var message = response.msg // modify it to your error message
						file.previewElement.classList.add("dz-error");
						_ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
						_results = [];
						for (_i = 0, _len = _ref.length; _i < _len; _i++) {
							node = _ref[_i];
							_results.push(node.textContent = message);
						}
						return _results;
					}
					
					total++;
					if(total > 0)
					{
						$("#process-button").fadeIn();
					}
				}
			};
			
			function xz()
			{
				$.post( "call-resize.php", { d:'<?php echo $d; ?>' }, function( data ) {
					$( "#result" ).html( data );
				});
			}
		</script>
    </body>
</html>
