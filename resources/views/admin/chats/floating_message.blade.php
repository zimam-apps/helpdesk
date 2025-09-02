@if (!empty($messages))
    @if (count($messages) > 0)
        @foreach ($messages as $message)
            @if ($message->sender == 'user')
                <div>
                    <span class="chat_msg_item chat_msg_item_user">{!! $message->description !!}</span>
                    <span class="status">{{ $message->created_at->diffForHumans() }}</span>
                </div>
            @else
                <span class="chat_msg_item chat_msg_item_admin">
                    <div class="chat_avatar">
                        <img src="{{ getSidebarLogo() }}" alt="">
                    </div>{!! $message->description !!}

                </span>
                <span class="status2">{{ $message->created_at->diffForHumans() }}</span>
            @endif
        @endforeach
    @else
        <h3 class="text-center mt-5 pt-5">No Message Found.!</h3>
    @endif
@else
    <h3 class="text-center mt-5 pt-5">Something went wrong..!!</h3>
@endif
