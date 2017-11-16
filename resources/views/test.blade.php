<!DOCTYPE HTML>
<html>
  <head>
    <style>
      body {
        margin: 0px;
        padding: 0px;
      }
    </style>
  </head>
  <body>
<h1>X</h1>
    <canvas id="myCanvas" width="578" height="200"></canvas>
    <script>
      function loadCanvas(dataURL) {
        var canvas = document.getElementById('myCanvas');
        var context = canvas.getContext('2d');


		        var imageObj = new Image();
		        imageObj.onload = function() {
						          context.drawImage(this, 0, 0);
								          };

				        imageObj.src = dataURL;
				      }
	        

	        var request = new XMLHttpRequest();
	  request.open('GET', 'http://www.html5canvastutorials.com/demos/assets/dataURL.txt', true);

	        request.onreadystatechange = function() {

					        if(request.readyState == 4) {

									          if(request.status == 200) {
													              loadCanvas(request.responseText);
																            }
											          }
							      };
			      request.send(null);

			    </script>
						  </body>
						  </html>    
