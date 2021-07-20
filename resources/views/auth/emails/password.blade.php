@lang('passwords.click_here')

<a href="{{ $link = route(Localizer::routeName('auth.reset.form'), $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>
