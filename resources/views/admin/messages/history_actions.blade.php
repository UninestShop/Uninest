<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="btn-group">
    <a href="{{ route('admin.messages.show', [
           'productId' => $chat->product_id, 
           'userId1' => $chat->sender_id, 
           'userId2' => $chat->receiver_id
       ]) }}" 
       class="btn btn-sm btn-outline-primary" title="View Full Conversation">
        <i class="fas fa-comments"></i>
    </a>
    
    @if($chat->flags->count() > 0)
        <form action="{{ route('admin.messages.review', $chat) }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="action" value="clear">
            <button type="submit" class="btn btn-sm btn-outline-success" title="Clear Flag">
                <i class="fas fa-check"></i>
            </button>
        </form>
    @else
        <button type="button" class="btn btn-sm btn-outline-warning" 
                onclick="openFlagModal('{{ $chat->id }}', '{{ addslashes($chat->message) }}')"
                title="Flag Message">
            <i class="fas fa-flag"></i>
        </button>
    @endif
</div>
