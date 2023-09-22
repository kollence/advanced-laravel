<x-mail::message>
# Last Step

Pleas click the link so we could confirm that this is your verifyd email address.

<x-mail::button :url="$url">
Confirmation link
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
