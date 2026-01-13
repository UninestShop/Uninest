@component('mail::message')

Hi {{ $data['name'] }},

Welcome to <b>UniNest</b> – your go-to spot for affordable, second-hand furniture made just for students!

From study desks and chairs to comfy beds and storage solutions, we've got quality pieces at student-friendly prices.

Browse, buy, and furnish your space without breaking the bank.

@component('mail::button', ['url' => url('/shop')])
Start Shopping Now
@endcomponent

Need help? We're always here to support you.

Cheers,<br>
The UniNest Team
@endcomponent
