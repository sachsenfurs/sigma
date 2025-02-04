@component("vendor.mail.html.message")

@foreach ($introLines as $line)
    {{ $line }}
@endforeach

@foreach($messages AS $message)
    @if($loop->index == 0)
        @if($previous = $message->chat->messages()->to($message->user)->where("id", "<", $message->id)->latest()->first())
            {{-- Empfangen (Receiver) --}}
            <div style="display: flex; align-items: center; margin: 10px 0;">
                <div style="padding: 10px; border-radius: 5px; max-width: 80%; background: #f7f7f7; color: #151515; text-align: right; margin-left: auto;">
                    <p>{!! nl2br(e($previous->text)) !!}</p>
                    <span style="font-size: 12px; color: #626262; display: block; margin-top: 5px;">
                    {{ $previous->created_at->format("d.m.Y, H:i") }}
                </span>
                </div>
                @if($previous->user->avatar_thumb)
                    <img style="width: 40px; height: 40px; border-radius: 50%; margin-right: 0; margin-left: 10px; align-self: end;" src="{{ $previous->user->avatar_thumb }}" alt="">
                @endif
            </div>
        @endif
    @endif

    {{-- Gesendete Nachricht (Sender) --}}
    <div style="display: flex; align-items: center; margin: 10px 0;">
        @if($message->user->avatar_thumb)
            <img style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; align-self: end;" src="{{ $message->user->avatar_thumb }}" alt="">
        @endif
        <div style="padding: 10px; border-radius: 5px; max-width: 80%; background: #0d47a1; text-align: left;">
            <p>{!! nl2br(e($message->text)) !!}</p>
            <span style="font-size: 12px; color: #bbbbbb; display: block; margin-top: 5px;">
            {{ $message->user->name }} - {{ $message->created_at->format("d.m.Y, H:i") }}
        </span>
        </div>
    </div>
@endforeach


    {{-- Action Button --}}
    @isset($actionText)
        @component("vendor.mail.html.button", ['url' => $actionUrl, 'align' => 'center'])
            {{ $actionText }}
        @endcomponent
    @endisset
@endcomponent
