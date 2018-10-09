<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Backup Database</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style> 
            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            } 
			
            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            } 

            .m-b-md {
                margin-bottom: 30px;
            }
			
			#backupdb{
				cursor: pointer;
			}
			
			.message{
				color: #090;
			}
			
			.error{
				color: #F00;
			}
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height"> 
            <div class="content">
            	<div class="loader" id="loader"></div>
            	<div class="message" id="message"></div>
                <div class="error" id="error"></div>
                <div class="title m-b-md"> 
                    <input type="button" name="backupdb" id="backupdb" value="Backup Databases" onClick="return fn_dbbackup();" />
                </div>
            </div>
        </div>
        <script type="text/javascript">
			function fn_dbbackup(){
				
				document.getElementById("message").innerHTML = "In progress...";
				
				//script to call db backup method
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() { 
					if (this.readyState == 4 && this.status == 200) {
						var data = JSON.parse(this.responseText);
						document.getElementById("message").innerHTML = data.message;
					} 
				};
				xhttp.open("GET", "/backup", true);
				xhttp.send();
			}
		</script>
    </body>
</html>
