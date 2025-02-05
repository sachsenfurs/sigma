@section("style")
@media (prefers-color-scheme: light) {
.msg_sender {
    color: #fff;
}
}
@endsection
<x-mail::message>

@foreach ($introLines ?? [] as $line)
{{ $line }}
@endforeach


@foreach($messages as $message)
@if($loop->index == 0)
@if($previous = $message->chat->messages()->to($message->user)->where("id", "<", $message->id)->latest()->first())
{{--<!-- Empfangen (Receiver) -->--}}
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:10px 0;">
<tr>
{{--<!-- Nachricht-Bubble: Rechts ausgerichtet -->--}}
<td align="right" valign="bottom">
<table border="0" cellspacing="0" cellpadding="0" style="background:#f7f7f7; padding:10px; border-radius:5px; text-align:right; color:#151515; display:inline-block;">
<tr>
<td style="word-break: break-word; text-wrap: wrap; word-wrap: break-word">
<p style="margin:0; padding:0; word-break: break-word; text-wrap: wrap; word-wrap: break-word; overflow-wrap: break-word;">{!! nl2br(e($previous->text)) !!}</p>
</td>
</tr>
<tr>
<td style="padding-top:5px;">
<span style="font-size:12px; color:#626262;">{{ $previous->created_at->format("d.m.Y, H:i") }}</span>
</td>
</tr>
</table>
</td>
{{--<!-- Avatar -->--}}
<td valign="bottom" style="padding-left:10px;width: 50px; min-width: 50px" width="50">
@if($previous->user->avatar_thumb)
<img src="{{ $previous->user->avatar_thumb }}" width="40" height="40" alt="" style="display:block; border-radius:50%;">
@endif
</td>
</tr>
</table>
@endif
@endif

{{--<!-- Gesendete Nachricht (Sender) -->--}}
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:10px 0;">
<tr>
<!-- Avatar -->
<td valign="bottom" style="padding-right:10px;" width="50">
@if($message->user->avatar_thumb)
<img src="{{ $message->user->avatar_thumb }}" width="40" height="40" alt="" style="display:block; border-radius:50%;">
@endif
</td>
{{--<!-- Nachricht-Bubble: Links ausgerichtet -->--}}
<td valign="bottom">
<table border="0" cellspacing="0" cellpadding="0" style="max-width:80%; background:#0d47a1; padding:10px; border-radius:5px; text-align:left; display:inline-block;">
<tr>
<td>
<p style="margin:0; padding:0; color:#ffffff; word-break: break-word; text-wrap: wrap; word-wrap: break-word">{!! nl2br(e($message->text)) !!}</p>
</td>
</tr>
<tr>
<td style="padding-top:5px;">
<span style="font-size:12px; color:#bbbbbb;">{{ $message->user->name }} - {{ $message->created_at->format("d.m.Y, H:i") }}</span>
</td>
</tr>
</table>
</td>
</tr>
</table>
@endforeach

{{-- Action Button --}}
@isset($actionText)
@component("vendor.mail.html.button", ['url' => $actionUrl, 'align' => 'center'])
{{ $actionText }}
@endcomponent
@endisset
</x-mail::message>
