<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<div class="btn-group">
    <a href="{{ route('admin.messages.show', [
           'productId' => $chat->product_id, 
           'userId1' => $chat->sender_id, 
           'userId2' => $chat->receiver_id
       ]) }}" 
       class="btn btn-sm btn-outline-primary" title="View Conversation">
        <i class="fas fa-eye"></i>
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
    
    {{-- <form action="{{ route('admin.messages.review', $chat) }}" method="POST" class="d-inline"
          onsubmit="return confirm('Are you sure you want to block this user? This will prevent them from sending any more messages.')">
        @csrf
        <input type="hidden" name="action" value="block_user">
        <button type="submit" class="btn btn-sm btn-outline-danger" title="Block User">
            <i class="fas fa-user-slash"></i>
        </button>
    </form> --}}
</div>
