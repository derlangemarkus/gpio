<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>EuLI - Eigentlich unsinniges LCD Interface</title>

		<!-- Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="container">

			<form class="form-signin">
				<h2 class="form-signin-heading">EuLI</h>
				<h6>Eigentlich unsinniges LCD Interface</h6>
				<button type="button" class="btn btn-primary btn-lg" style="width:95%" onclick="openEyes()">Augen auf</button><br />
				<br />
				<button type="button" class="btn btn-default btn-lg" style="width:95%" onclick="closeEyes()">Augen zu</button>
			</form>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script type="text/javascript">
			function openEyes()
			{
				jQuery.ajax("ajax.php?eyes=open");
			}
			
			function closeEyes()
			{
				jQuery.ajax("ajax.php?eyes=close");
			}
		</script>
	</body>
</html>