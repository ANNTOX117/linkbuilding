<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<meta name="csrf-token" content="{{ csrf_token() }}" />
		<!-- CSRF Token -->
		<title>HOME | Linkbuilding</title>

		<!-- Bootstrap core CSS -->
		<link href="<?=phpb_theme_asset('frontend/css/bootstrap.min.css');?>" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link rel="stylesheet" href="<?=phpb_theme_asset('css/all.min.css');?>">
		<link rel="stylesheet" href="<?=phpb_theme_asset('css/aos.css');?>">
		<link rel="stylesheet" href="<?=phpb_theme_asset('css/style.css');?>">

		<link rel="stylesheet" href="<?=phpb_theme_asset('frontend/css/addons.css'); ?>">

	</head>
	<body>
        <?=$body?>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="<?=phpb_theme_asset('frontend/js/bootstrap.bundle.min.js');?>"></script>
		<script type="text/javascript" src="<?=phpb_theme_asset('js/aos.js');?>"></script>
		<script type="text/javascript" src="<?=phpb_theme_asset('js/app.js');?>"></script>
		<script type="text/javascript" src="<?=phpb_theme_asset('js/theme.js')?>?v=<?=time()?>"></script>
	  </body>
</html>
