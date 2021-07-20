<!DOCTYPE html>
<html lang="{{ $locale }}-CA">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		{!! trans('share.email.body', [
			'url' => $url
		]) !!}
	</body>
</html>
