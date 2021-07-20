<!DOCTYPE html>
<html lang="{{ App::getLocale() }}-CA">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
        {!! trans('invitation.email.body',[
            'role' => mb_convert_case($role->name, MB_CASE_LOWER, 'utf-8'),
            'organisation' => $organisation->name,
            'link_url' => route(Localizer::routeName('organisation.invitation', $locale), [$organisation->slug, $invitation->invitation_key])
        ]) !!}
	</body>
</html>
