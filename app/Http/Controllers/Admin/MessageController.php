<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use App\Models\Product;
use App\Models\ChatFlag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;
use App\Http\Requests\ReviewChatRequest;

class MessageController extends Controller
{
    /**
     * Display a listing of messages with potential issues.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = Chat::with(['sender', 'receiver', 'product'])
                    ->select('chats.*')
                    ->addSelect(DB::raw('(SELECT COUNT(*) FROM chat_flags WHERE chat_flags.chat_id = chats.id) as flag_count'))
                    ->orderBy('created_at', 'desc');
                    
                return DataTables::of($query)
                    ->addColumn('sender_name', function($chat) {
                        return $chat->sender->name ?? 'Unknown User';
                    })
                    ->addColumn('receiver_name', function($chat) {
                        return $chat->receiver->name ?? 'Unknown User';
                    })
                    ->addColumn('product_name', function($chat) {
                        return $chat->product->name ?? 'Unknown Product';
                    })
                    ->addColumn('flags', function($chat) {
                        return $chat->flag_count ?? 0;
                    })
                    ->addColumn('actions', function($chat) {
                        return view('admin.messages.actions', compact('chat'))->render();
                    })
                    ->rawColumns(['actions', 'message'])
                    ->make(true);
            } catch (\Exception $e) {
                \Log::error('DataTables error: ' . $e->getMessage());
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
        
        return view('admin.messages.index');
    }
    
    /**
     * Display a listing of all message history with advanced filters.
     */
    public function chatHistory(Request $request)
    {
        $users = User::orderBy('name')->get(['id', 'name']);
        $products = Product::orderBy('name')->get(['id', 'name']);
        
        if ($request->ajax()) {
            try {
                $query = Chat::with(['sender', 'receiver', 'product']);
                
                if ($request->filled('sender_id')) {
                    $query->where('sender_id', $request->sender_id);
                }
                
                if ($request->filled('receiver_id')) {
                    $query->where('receiver_id', $request->receiver_id);
                }
                
                if ($request->filled('product_id')) {
                    $query->where('product_id', $request->product_id);
                }
                
                if ($request->filled('flagged') && $request->flagged == 'yes') {
                    $query->whereHas('flags');
                }
                
                if ($request->filled('date_from')) {
                    $query->whereDate('created_at', '>=', $request->date_from);
                }
                
                if ($request->filled('date_to')) {
                    $query->whereDate('created_at', '<=', $request->date_to);
                }
                
                if ($request->filled('search_text')) {
                    $query->where('message', 'like', '%' . $request->search_text . '%');
                }
                
                return DataTables::of($query)
                    ->addColumn('sender_name', function($chat) {
                        return $chat->sender->name ?? 'Unknown User';
                    })
                    ->addColumn('receiver_name', function($chat) {
                        return $chat->receiver->name ?? 'Unknown User';
                    })
                    ->addColumn('product_name', function($chat) {
                        return $chat->product->name ?? 'Unknown Product';
                    })
                    ->addColumn('actions', function($chat) {
                        return view('admin.messages.history_actions', compact('chat'))->render();
                    })
                    ->rawColumns(['actions'])
                    ->make(true);
            } catch (\Exception $e) {
                \Log::error('Chat history DataTables error: ' . $e->getMessage());
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
        
        return view('admin.messages.history', compact('users', 'products'));
    }
    
    /**
     * Display the specified chat conversation.
     */
    public function show(Request $request, $productId, $userId1, $userId2)
    {
        $user1 = User::findOrFail($userId1);
        $user2 = User::findOrFail($userId2);
        $product = Product::findOrFail($productId);
        
        $messages = Chat::where('product_id', $productId)
            ->where(function($query) use ($userId1, $userId2) {
                $query->where(function($q) use ($userId1, $userId2) {
                    $q->where('sender_id', $userId1)
                      ->where('receiver_id', $userId2);
                })->orWhere(function($q) use ($userId1, $userId2) {
                    $q->where('sender_id', $userId2)
                      ->where('receiver_id', $userId1);
                });
            })
            ->orderBy('created_at')
            ->get();
            
        return view('admin.messages.show', compact('messages', 'user1', 'user2', 'product'));
    }
    
    public function review(ReviewChatRequest $request, Chat $chat)
    {
        if ($request->action == 'flag') {
            ChatFlag::create([
                'chat_id' => $chat->id,
                'flagged_by' => auth()->id(),
                'reason' => $request->reason
            ]);
            return redirect()->back()->with('success', 'Message has been flagged.');
        } 
        else if ($request->action == 'clear') {
            ChatFlag::where('chat_id', $chat->id)->delete();
            return redirect()->back()->with('success', 'All flags have been cleared from this message.');
        }
        else if ($request->action == 'block_user') {
            $user = User::find($chat->sender_id);
            if ($user) {
                $user->is_blocked = true;
                $user->blocked_at = now();
                $user->blocked_reason = $request->reason ?? 'Inappropriate messaging';
                $user->save();
                return redirect()->back()->with('success', 'User has been blocked.');
            }
        }
        return redirect()->back()->with('error', 'Invalid action requested.');
    }
    
    public function export(Request $request)
    {
        $query = Chat::with(['sender', 'receiver', 'product']);
        
        if ($request->filled('sender_id')) {
            $query->where('sender_id', $request->sender_id);
        }
        
        if ($request->filled('receiver_id')) {
            $query->where('receiver_id', $request->receiver_id);
        }
        
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $chats = $query->orderBy('created_at')->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="chat_history_' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($chats) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Sender', 'Receiver', 'Product', 'Message', 'Created At', 'Flagged']);
            
            foreach ($chats as $chat) {
                $flagged = $chat->flags->count() > 0 ? 'Yes' : 'No';
                
                fputcsv($file, [
                    $chat->id,
                    $chat->sender->name ?? 'Unknown',
                    $chat->receiver->name ?? 'Unknown',
                    $chat->product->name ?? 'Unknown',
                    $chat->message,
                    $chat->created_at,
                    $flagged
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
