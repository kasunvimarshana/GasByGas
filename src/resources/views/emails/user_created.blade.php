@component('mail::message')

# {{ __('Welcome, :name!', ['name' => $user->name]) }}

Hi {{ $user->name }},

We are excited to let you know that your account has been successfully created.
Your registered email address is: **{{ $user->email }}**.

Account Created On: **{{ $user->created_at->format('M d, Y') }}**

@component('mail::button', ['url' => url('/')])
Click Here
@endcomponent

Thank you for joining us!

---

&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.

@endcomponent
