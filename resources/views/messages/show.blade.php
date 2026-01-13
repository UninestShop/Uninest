@extends('layouts.app')

@section('content')
<style>
    /* Custom styling for chat messages */
    .message-sender {
        text-align: right;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }
    
    .message-receiver {
        text-align: left;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }
    
    .message-bubble {
        max-width: 75%;
        border-radius: 0.375rem;
        padding: 0.5rem;
        display: inline-block;
    }
    
    .message-time {
        font-size: 0.75rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
    
    .sender-bubble {
        background-color: #0d6efd;
        color: white;
    }
    
    .receiver-bubble {
        background-color: #f8f9fa;
        color: #212529;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            @if (isset($chat->otherUser))
                                @if ($chat->otherUser->profile_picture)
                                    <img src="{{ asset($chat->otherUser->profile_picture) }}" alt="{{ $chat->otherUser->name }}" class="rounded-circle me-2" width="40" height="40">
                                @else
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                        <span class="text-white" style="font-size: 16px;">{{ substr($chat->otherUser->name ?? 'U', 0, 1) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <h5 class="mb-0">{{ $chat->otherUser->name }} ({{ $chat->otherUser->id }})</h5>
                                    <!-- User role information -->
                                    @php
                                        $isSeller = isset($chat->product) && isset($chat->otherUser) && 
                                                   $chat->product && $chat->otherUser && 
                                                   $chat->product->user_id === $chat->otherUser->id;
                                        $roleLabel = $isSeller ? 'Seller' : 'Buyer';
                                        $roleBadgeClass = $isSeller ? 'bg-success' : 'bg-info';
                                        
                                        // Product status indicators
                                        $statusBadge = '';
                                        if (isset($chat->product) && $chat->product) {
                                            if ($chat->product->is_sold) {
                                                $statusBadge = '<span class="badge bg-dark ms-1">Sold</span>';
                                            } elseif ($chat->product->is_reserved) {
                                                $reservedFor = $chat->product->reserved_for == auth()->id() 
                                                    ? 'for you' 
                                                    : (isset($chat->otherUser) && $chat->product->reserved_for == $chat->otherUser->id ? 'for them' : '');
                                                $statusBadge = '<span class="badge bg-warning ms-1">Reserved ' . $reservedFor . '</span>';
                                            }
                                        }
                                    @endphp
                                    <div class="d-flex align-items-center">
                                        <span class="badge {{ $roleBadgeClass }} me-1">{{ $roleLabel }}</span>
                                        @if(isset($chat->product) && $chat->product)
                                            <small class="text-muted">Regarding: {{ $chat->product->name }}</small>
                                            {!! $statusBadge !!}
                                        @endif
                                    </div>
                                </div>
                            @else
                            {{-- @dd($chat->receiver) --}}
                                <div class="rounded-circle overflow-hidden mr-2 bg-secondary d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                    {{-- <span class="text-white" style="font-size: 16px;">{{asset($chat->receiver->profile_picture)}}</span> --}}
                                    {{-- <figure> --}}
                                        <img style="max-width: 100%" src="{{ $chat->receiver->profile_picture ? asset('storage/' . $chat->receiver->profile_picture) : asset('images/profile-img.png') }}" alt="User Profile">
                                      {{-- </figure> --}}
                                </div>
                                <div>
                                    <h5 class="mb-0">
                                        @if(isset($chat->sender) && isset($chat->receiver))
                                            {{-- Conversation between  --}}
                                            {{-- <span class="text-primary">{{ $chat->sender->name }}</span>  --}}
                                            {{-- and  --}}
                                            <span class="">{{ $chat->receiver->name }}</span>
                                        @else
                                            Unknown Users
                                        @endif
                                    </h5>
                                    @if(isset($chat->product) && $chat->product)
                                        {{-- <small class="text-muted">Regarding: {{ $chat->product->name }}</small> --}}
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="d-flex">
                            {{-- <div class="dropdown me-2">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="chatActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="chatActionsDropdown">
                                    @if(isset($chat->product) && $chat->product)
                                        <li><a class="dropdown-item" href="{{ route('products.show', $chat->product) }}">
                                            <i class="fas fa-eye me-2"></i> View Product
                                        </a></li>
                                        @if($chat->product->user_id === auth()->id())
                                            <li><a class="dropdown-item" href="{{ route('seller.products.edit', $chat->product) }}">
                                                <i class="fas fa-edit me-2"></i> Edit Product
                                            </a></li>
                                        @endif
                                        <li><hr class="dropdown-divider"></li>
                                    @endif --}}
                                    {{-- <li><button class="dropdown-item text-danger">
                                        <i class="fas fa-flag me-2"></i> Report Issue
                                    </button></li> --}}
                                {{-- </ul> --}}
                            </div>
                            <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Back to Messages
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body" id="chat-messages" style="height: 400px; overflow-y: auto;">
                    @foreach ($messages as $message)
                        <div class="message mb-3 {{ $message->sender_id == auth()->id() ? 'message-sender' : 'message-receiver' }}"> 
                            <div class="message-bubble  {{ $message->sender_id == auth()->id() ? 'sender-bubble' : 'receiver-bubble' }}">
                                {{ $message->message }}
                                <div class="small {{ $message->sender_id == auth()->id() ? 'text-white-50' : 'text-muted' }}">
                                    {{-- From: {{ $message->sender->name ?? 'Unknown' }}  --}}
                                    {{-- (ID: {{ $message->sender_id }}) --}}
                                </div>
                            </div>
                            <div class="message-time">
                                {{ $message->created_at->format('M d, g:i a') }}
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Quick Replies Section -->
                @if(isset($chat->product) && $chat->product)
                    <div class="border-top px-3 py-2 bg-light">
                        <div class="d-flex flex-wrap gap-2">
                            @php
                                $isProductSeller = isset($chat->product) && $chat->product && $chat->product->user_id === auth()->id();
                                $quickReplies = $isProductSeller 
                                    ? ['Yes, it\'s available', 'Price is firm', 'When can you pick it up?', 'I can deliver it']
                                    : ['Is this still available?', 'Would you accept $'.number_format($chat->product->selling_price * 0.9, 2).'?', 'Where can we meet?', 'When is it available?'];
                            @endphp
                            
                            @foreach($quickReplies as $reply)
                                <button type="button" class="btn btn-sm btn-outline-secondary quick-reply-btn">{{ $reply }}</button>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <div class="card-footer bg-white">
                    <form action="{{ route('messages.send', $chat) }}" method="POST" id="message-form">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="message" class="form-control" placeholder="Type your message..." required>
                            <button class="btn btn-primary" type="submit" id="send-button">
                                <i class="fas fa-paper-plane"></i> Send
                            </button>
                        </div>
                        <div class="mt-2 text-danger d-none" id="message-error"></div>
                    </form>
                </div>
            </div>
            
            @if(isset($chat->product) && $chat->product)
                <div class="card mt-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Product Details</h5>
                        
                        @php
                            $isProductSeller = isset($chat->product) && $chat->product && $chat->product->user_id === auth()->id();
                            $otherUserIsBuyer = !$isProductSeller;
                            $productStatus = '';
                            
                            if (isset($chat->product) && $chat->product) {
                                if ($chat->product->is_sold) {
                                    $productStatus = '<span class="badge bg-dark">Sold</span>';
                                } elseif ($chat->product->is_reserved) {
                                    $reservedFor = $chat->product->reserved_for == auth()->id() 
                                        ? '<span class="badge bg-warning">Reserved for you</span>'
                                        : (isset($chat->otherUser) && $chat->product->reserved_for == $chat->otherUser->id 
                                            ? '<span class="badge bg-warning">Reserved for this buyer</span>'
                                            : '<span class="badge bg-warning">Reserved</span>');
                                    $productStatus = $reservedFor;
                                } else {
                                    $productStatus = '<span class="badge bg-success">Available</span>';
                                }
                            }
                        @endphp
                        
                        <div class="d-flex align-items-center">
                            {!! $productStatus !!}
                            
                            @if($isProductSeller && isset($chat->product) && $chat->product && !$chat->product->is_sold)
                                <!-- Seller actions -->
                                <div class="dropdown ms-2">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Seller Actions
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            @if(isset($chat->otherUser) && $chat->otherUser)
                                            <form action="{{ route('seller.products.reserve', $chat->product) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="buyer_id" value="{{ $chat->otherUser->id }}">
                                                <button type="submit" class="dropdown-item {{ isset($chat->product->reserved_for) && isset($chat->otherUser) && $chat->product->reserved_for == $chat->otherUser->id ? 'active' : '' }}">
                                                    <i class="fas fa-bookmark me-2"></i> 
                                                    {{ isset($chat->product->reserved_for) && isset($chat->otherUser) && $chat->product->reserved_for == $chat->otherUser->id ? 'Product Reserved' : 'Reserve for this Buyer' }}
                                                </button>
                                            </form>
                                            @endif
                                        </li>
                                        <li>
                                            <a href="{{ route('seller.products.edit', $chat->product) }}" class="dropdown-item">
                                                <i class="fas fa-edit me-2"></i> Edit Product
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#confirmSoldModal">
                                                <i class="fas fa-check-circle me-2"></i> Mark as Sold
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            @elseif($otherUserIsBuyer && isset($chat->product) && $chat->product && !$chat->product->is_sold)
                                <!-- Buyer actions -->
                                <div class="ms-2">
                                    {{-- <a href="{{ route('transactions.initiate.form', $chat->product) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-shopping-cart me-1"></i> Buy Now
                                    </a> --}}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                @php
                                    $photos = isset($chat->product) && $chat->product ? $chat->product->photos : null;
                                    // Ensure photos is an array that can be counted
                                    if (!is_array($photos) && !($photos instanceof Countable)) {
                                        $photos = $photos ? [$photos] : [];
                                    }
                                @endphp
                                
                                @if($photos && count($photos) > 0)
                                    <img src="{{ asset($photos[0]) }}" alt="{{ isset($chat->product) ? $chat->product->name : 'Product' }}" class="img-fluid rounded" style="max-height: 150px;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 150px;">
                                        <span class="text-muted">No image available</span>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h5>{{ isset($chat->product) ? $chat->product->name : 'Unknown Product' }}</h5>
                                @if(isset($chat->product))
                                <p class="text-success font-weight-bold">${{ number_format($chat->product->mrp, 2) }}</p>
                                <p class="text-muted mb-2">{{ \Illuminate\Support\Str::limit($chat->product->description, 100) }}</p>
                                <a href="{{ route('products.show', $chat->product) }}" class="btn btn-sm btn-outline-primary">View Product</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if(isset($chat->product) && $chat->product && $chat->product->is_reserved)
                        <div class="card-footer bg-warning bg-opacity-10">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-warning me-2"><i class="fas fa-bookmark"></i></span>
                                <small>This item is reserved{{ isset($chat->product->reserved_for) && $chat->product->reserved_for == auth()->id() ? ' for you' : '' }}</small>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Mark as Sold Modal -->
@if(isset($chat->product) && $chat->product && isset($chat->otherUser) && $chat->product->user_id === auth()->id())
<div class="modal fade" id="confirmSoldModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark Product as Sold</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Was this product sold to <strong>{{ $chat->otherUser->name }}</strong>?</p>
                <p class="text-muted small">This will mark the product as sold and remove it from listings.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('seller.products.mark-sold', $chat->product) }}" method="POST">
                    @csrf
                    <input type="hidden" name="buyer_id" value="{{ $chat->otherUser->id }}">
                    <button type="submit" class="btn btn-success">Yes, Mark as Sold</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
    // Scroll to bottom of chat messages on page load
    document.addEventListener('DOMContentLoaded', function() {
        const chatMessages = document.getElementById('chat-messages');
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    });
    
    // Submit form via AJAX to prevent page refresh
    const messageForm = document.getElementById('message-form');
    if (messageForm) {
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = e.target;
            const formData = new FormData(form);
            const messageInput = form.querySelector('input[name="message"]');
            const sendButton = document.getElementById('send-button');
            const errorElement = document.getElementById('message-error');
            
            // Disable button and show loading state
            if (sendButton) sendButton.disabled = true;
            if (sendButton) sendButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...';
            if (errorElement) errorElement.classList.add('d-none');
            
            // Add the message to the chat immediately with a "sending" indicator
            const messageText = messageInput.value;
            const tempMessageId = 'temp-msg-' + Date.now();
            const chatMessages = document.getElementById('chat-messages');
            if (chatMessages) {
                const messageDiv = document.createElement('div');
                messageDiv.id = tempMessageId;
                messageDiv.className = 'message mb-3 message-sender';
                messageDiv.innerHTML = `
                    <div class="message-bubble sender-bubble">
                        ${messageText}
                        <div class="message-status small text-white-50" style="opacity: 0.7">
                            <i class="fas fa-clock"></i> Sending...
                        </div>
                    </div>
                    <div class="message-time">
                        Just now
                    </div>
                `;
                chatMessages.appendChild(messageDiv);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
            
            // Clear input field immediately for better UX
            messageInput.value = '';
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Update the temporary message with the server response data
                    const tempMessage = document.getElementById(tempMessageId);
                    if (tempMessage) {
                        // Remove the "sending" indicator and show a checkmark
                        const statusDiv = tempMessage.querySelector('.message-status');
                        if (statusDiv) {
                            statusDiv.innerHTML = '<i class="fas fa-check"></i> Sent';
                            // Fade out the status after 2 seconds
                            setTimeout(() => {
                                statusDiv.style.opacity = 0;
                                setTimeout(() => {
                                    statusDiv.remove();
                                }, 500);
                            }, 2000);
                        }
                    }
                } else {
                    throw new Error(data.message || 'Failed to send message');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Show error message
                if (errorElement) {
                    errorElement.textContent = error.message || 'Failed to send message. Please try again.';
                    errorElement.classList.remove('d-none');
                }
                
                // Remove the temporary message
                const tempMessage = document.getElementById(tempMessageId);
                if (tempMessage) {
                    tempMessage.remove();
                }
                
                // Restore the message in the input
                if (messageInput) messageInput.value = messageText;
            })
            .finally(() => {
                // Re-enable the send button
                if (sendButton) sendButton.disabled = false;
                if (sendButton) sendButton.innerHTML = '<i class="fas fa-paper-plane"></i> Send';
            });
        });
    }
    
    // Initialize lastMessageId safely
    let lastMessageId = 0;
    try {
        // Define a safe way to get the last message ID
        const getLastMessageId = () => {
            @if(isset($messages) && $messages->count() > 0 && $messages->last())
                return {{ $messages->last()->id }};
            @else
                return 0;
            @endif
        };
        lastMessageId = getLastMessageId();
    } catch(e) {
        console.error('Error initializing lastMessageId:', e);
    }
    
    let pollInterval = null;
    
    function pollForMessages() {
        // Define a safe way to get the chat ID
        const getChatId = () => {
            @if(isset($chat) && $chat)
                return {{ $chat->id }};
            @else
                return null;
            @endif
        };
        
        const chatId = getChatId();
        if (!chatId) {
            console.error('Invalid chat ID');
            return;
        }

        // Safety check for CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('CSRF token not found');
            return;
        }
        
        // Create a proper URL with the current chat ID
        const pollUrl = '{{ isset($chat) && $chat ? route("messages.poll", $chat->id) : "#" }}';
        if (pollUrl === '#') {
            console.error('Invalid poll URL');
            return;
        }
        
        fetch(`${pollUrl}?last_id=${lastMessageId}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content')
            },
            credentials: 'same-origin'
        })
        .then(response => {
            // Check if response is JSON before parsing
            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                throw new Error(`Expected JSON response but got ${contentType}`);
            }
            
            if (!response.ok) {
                throw new Error(`Server responded with status: ${response.status}`);
            }
            
            return response.json();
        })
        .then(data => {
            if (data.success && data.messages && data.messages.length > 0) {
                const chatMessages = document.getElementById('chat-messages');
                if (!chatMessages) return;
                
                data.messages.forEach(message => {
                    // Only add messages from the other person (our own messages are added immediately)
                    const currentUserId = {{ auth()->id() }};
                    if (message.sender_id != currentUserId) {
                        const messageDiv = document.createElement('div');
                        messageDiv.className = 'message mb-3 message-receiver';
                        messageDiv.innerHTML = `
                            <div class="message-bubble receiver-bubble">
                                ${message.message}
                                <div class="small text-muted">
                            
                                </div>
                            </div>
                            <div class="message-time">
                                ${message.created_at}
                            </div>
                        `;
                        chatMessages.appendChild(messageDiv);
                    }
                    
                    // Update the last message ID with null check
                    if (message && message.id) {
                        lastMessageId = Math.max(lastMessageId, message.id);
                    }
                });
                
                // Scroll to bottom if there are new messages
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        })
        .catch(error => {
            console.error('Polling error:', error);
            
            // Log more detailed error information to help debug
            console.log('Last message ID:', lastMessageId);
            console.log('Chat ID:', getChatId());
            
            // Stop polling if there's an error to prevent flooding the console
            if (pollInterval) {
                clearInterval(pollInterval);
                pollInterval = null;
                console.log('Polling stopped due to errors. Refresh the page to restart.');
            }
        });
    }
    
    // Start polling only if we have valid chat data
    document.addEventListener('DOMContentLoaded', function() {
        // Check if necessary elements exist
        const chatMessages = document.getElementById('chat-messages');
        if (!chatMessages) {
            console.error('Chat messages container not found');
            return;
        }
        
        // Check if we have a valid chat
        const hasChatId = {{ isset($chat) && $chat && $chat->id ? 'true' : 'false' }};
        if (!hasChatId) {
            console.error('Invalid chat data, polling disabled');
            return;
        }
        
        // Initial delay before starting polling
        setTimeout(() => {
            try {
                if (!pollInterval) {
                    pollInterval = setInterval(pollForMessages, 5000);
                    // Initial poll
                    pollForMessages();
                }
                
                // Clean up when the page is unloaded
                window.addEventListener('beforeunload', function() {
                    if (pollInterval) {
                        clearInterval(pollInterval);
                        pollInterval = null;
                    }
                });
            } catch (e) {
                console.error('Error setting up polling:', e);
            }
        }, 1000);
    });
    
    // Quick reply buttons
    document.querySelectorAll('.quick-reply-btn').forEach(button => {
        button.addEventListener('click', function() {
            const messageInput = document.querySelector('input[name="message"]');
            if (messageInput) {
                messageInput.value = this.textContent;
                messageInput.focus();
            }
        });
    });
</script>
@endpush
@endsection
