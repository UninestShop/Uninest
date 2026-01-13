<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Product;
use App\Http\Requests\ChatMessageRequest;
use App\Http\Requests\ChatConversationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ChatController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();
            
            $chats = Chat::with(['product', 'product.user', 'sender', 'receiver'])
                ->where(function($query) use ($user) {
                    $query->where(function($q) use ($user) {
                        $q->where('sender_id', $user->id)
                          ->whereNotNull('message')
                          ->where('message', '!=', '');
                    })
                    ->orWhere(function($q) use ($user) {
                        $q->where('receiver_id', $user->id)
                          ->whereNotNull('message')
                          ->where('message', '!=', '');
                    });
                })
                ->latest()
                ->get()
                ->filter(function($chat) {
                    return $chat->product !== null;
                })
                ->groupBy('product_id');
                
            return view('messages.index', compact('chats'));
        } catch (\Exception $e) {
            \Log::error('Error loading chat index: ' . $e->getMessage());
            return view('messages.index', ['chats' => collect([])])->with('error', 'Unable to load messages. Please try again later.');
        }
    }

    public function show(Chat $chat)
    {
        try {
            abort_if(!$this->canViewChat($chat), 403);

            Chat::where(function($query) use ($chat) {
                    $query->where('product_id', $chat->product_id)
                          ->where(function($q) use ($chat) {
                              $q->where(function($subq) use ($chat) {
                                  $subq->where('sender_id', $chat->sender_id)
                                       ->where('receiver_id', $chat->receiver_id);
                              })->orWhere(function($subq) use ($chat) {
                                  $subq->where('sender_id', $chat->receiver_id)
                                       ->where('receiver_id', $chat->sender_id);
                              });
                          });
                })
                ->where('receiver_id', auth()->id())
                ->when(Schema::hasColumn('chats', 'is_read'), function($query) {
                    $query->where('is_read', false)
                          ->update(['is_read' => true]);
                });

            if ($chat->product && $chat->product->user_id === auth()->id()) {
                if (Schema::hasColumn('chats', 'has_unread_seller_messages')) {
                    Chat::where('product_id', $chat->product_id)
                        ->update(['has_unread_seller_messages' => false]);
                }
            }

            $chat->load(['product', 'product.user', 'sender.roles', 'receiver.roles']);
            
            $messages = Chat::where('product_id', $chat->product_id)
                ->where(function($query) use ($chat) {
                    $query->where(function($q) use ($chat) {
                        $q->where('sender_id', $chat->sender_id)
                          ->where('receiver_id', $chat->receiver_id);
                    })->orWhere(function($q) use ($chat) {
                        $q->where('sender_id', $chat->receiver_id)
                          ->where('receiver_id', $chat->sender_id);
                    });
                })
                ->whereNotIn('message', ['', ' ']) 
                ->orderBy('created_at')
                ->get();
            
            $isSeller = $chat->product && $chat->product->user_id === auth()->id();
            $viewData = [
                'chat' => $chat,
                'messages' => $messages,
                'isSeller' => $isSeller,
            ];

            return view('messages.show', $viewData);
        } catch (\Exception $e) {
            \Log::error('Error showing chat conversation: ' . $e->getMessage());
            return redirect()->route('messages.index')->with('error', 'Unable to load conversation. Please try again later.');
        }
    }

    public function startConversation(ChatConversationRequest $request, Product $product)
    {
        try {
            abort_if($product->user_id === auth()->id(), 403);

            $chat = Chat::create([
                'product_id' => $product->id,
                'sender_id' => auth()->id(),
                'receiver_id' => $product->user_id,
                'message' => $request->has('message') ? $request->message : ''
            ]);

            return redirect()->route('messages.show', $chat);
        } catch (\Exception $e) {
            \Log::error('Error starting conversation: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to start conversation. Please try again later.');
        }
    }

    public function send(ChatMessageRequest $request, Chat $chat)
    {
        abort_if(!$this->canViewChat($chat), 403);
        
        $senderId = auth()->id();
        $receiverId = $senderId === $chat->sender_id ? $chat->receiver_id : $chat->sender_id;
        
        $newMessage = Chat::create([
            'product_id' => $chat->product_id,
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $request->message,
            'is_read' => false
        ]);
        
        $product = Product::find($chat->product_id);
        $isSeller = $product && $receiverId === $product->user_id;
        
        $receiver = \App\Models\User::find($receiverId);
        if ($receiver) {
            try {
                $receiver->notify(new \App\Notifications\NewMessageReceived($newMessage, $isSeller));
                
                if ($isSeller) {
                    if (Schema::hasColumn('chats', 'has_unread_seller_messages')) {
                        $chat->update(['has_unread_seller_messages' => true]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send message notification: ' . $e->getMessage());
            }
        }

        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => $newMessage
            ], 200, ['Content-Type' => 'application/json']);
        }
        return back();
    }

    public function pollMessages(Chat $chat, Request $request)
    {
        if (!$this->canViewChat($chat)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        
        try {
            $lastId = $request->input('last_id', 0);
            $userId = auth()->id();
            
            $messages = Chat::where('product_id', $chat->product_id)
                ->where(function($query) use ($chat, $userId) {
                    $query->where(function($q) use ($chat) {
                        $q->where('sender_id', $chat->sender_id)
                          ->where('receiver_id', $chat->receiver_id);
                    })
                    ->orWhere(function($q) use ($chat) {
                        $q->where('sender_id', $chat->receiver_id)
                          ->where('receiver_id', $chat->sender_id);
                    });
                })
                ->where('id', '>', $lastId)
                ->whereNotIn('message', ['', ' '])
                ->orderBy('created_at', 'asc')
                ->get();
            
            if ($messages->count() > 0) {
                Chat::where('receiver_id', $userId)
                    ->where('is_read', false)
                    ->whereIn('id', $messages->pluck('id'))
                    ->update(['is_read' => true]);
                
                $product = Product::find($chat->product_id);
                if ($product && $product->user_id === $userId) {
                    $chat->update(['has_unread_seller_messages' => false]);
                }
            }
            
            $formattedMessages = $messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender_id' => $message->sender_id,
                    'created_at' => $message->created_at->format('M d, g:i a')
                ];
            });
            
            return response()->json([
                'success' => true,
                'messages' => $formattedMessages
            ], 200, ['Content-Type' => 'application/json']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching messages: ' . $e->getMessage()
            ], 500, ['Content-Type' => 'application/json']);
        }
    }

    protected function canViewChat($chat)
    {
        $userId = auth()->id();
        return $userId === $chat->sender_id || $userId === $chat->receiver_id;
    }


    public function checkNewMessages()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $unreadCount = Chat::query()
                ->where('receiver_id', $user->id)
                ->whereNotIn('message', ['', ' '])
                ->where('is_read', false)
                ->count();
            
            return response()->json([
                'unreadCount' => $unreadCount,
                'status' => 'success'
            ]);
        } 
        
        return response()->json([
            'unreadCount' => 0,
            'status' => 'error',
            'message' => 'User not authenticated'
        ], 401);
    }

    public function getUnreadCountByProduct()
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            $unreadCounts = Chat::where('receiver_id', $user->id)
                ->where('is_read', false)
                ->whereNotIn('message', ['', ' '])
                ->select('product_id', \DB::raw('count(*) as count'))
                ->groupBy('product_id')
                ->get()
                ->mapWithKeys(function($item) {
                    return [$item->product_id => $item->count];
                });
            
            return response()->json([
                'status' => 'success',
                'unreadCounts' => $unreadCounts
            ]);
        } 
        
        return response()->json([
            'status' => 'error',
            'message' => 'User not authenticated'
        ], 401);
    }
}
