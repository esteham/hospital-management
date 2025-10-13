@component('mail::message')
	# welcome, Dr. {{ $name }}

	Your doctor account for **Clinic App** has been created

@component('mail::panel')
	**Login URL:** {{ $loginUrl }}
	**Email:** {{ $email }}
	**Password:** {{ $password }}
@endcomponent

> For security, please log in and change your password immediately

Thanks,
{{ config('app.name') }}
@endcomponent
