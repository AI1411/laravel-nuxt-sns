@component('mail::message')
    # こんにちは

    招待メールが届いています.
    **{{ $invitation->team->name }}**

    [登録はこちら]({{ $url }})

    @component('mail::button', ['url' => $url])
        ダッシュボードへ
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
