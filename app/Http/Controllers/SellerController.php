<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Setting;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Http\Request;
use App\Models\Chat;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Exception;

class SellerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        try {
            $user = auth()->user();
            $products = $user->products()->where('university_id',$user->university_id)->where('status','approved')->where('is_sold',0)->latest()->paginate(10);
        
            $stats = [
                'total' => $user->products()->where('status', 'approved')->count(),
                'active' => $user->products()->where('status', 'approved')->where('is_sold', false)->count(),
                'pending' => $user->products()->where('status', 'pending')->count(),
                'sold' => $user->products()->where('is_sold', true)->count(),
                'rejected' => $user->products()->where('status', 'rejected')->count(),
                'flagged' => $user->products()->where('status', 'flagged')->count()
            ];
            
            $setting = Setting::where('key','max_products_user')->first();
            $product = Product::where('user_id',$user->id)->where('status', 'approved')->where('is_sold',0)->count();
            $productLimit = $setting->value;
            $canAddMore = $stats['total'] >= intval($productLimit);

             $chats = Chat::with(['product', 'product.user', 'sender', 'receiver'])
                ->where(function($query) use ($user) {
                    $query->where('sender_id', $user->id)
                          ->orWhere('receiver_id', $user->id);
                })
                ->latest()
                ->get()
                ->filter(function($chat) {
                    return $chat->product !== null;
                })
                ->groupBy('product_id');
            
            return view('seller.products.index', compact(
                'product',
                'products', 
                'stats', 
                'productLimit', 
                'canAddMore',
                'chats'
            ));
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function create()
    {
        try {
            $user = auth()->user();
            $setting = Setting::where('key','max_products_user')->first();
            $productLimit = $setting->value;
            
            $product = Product::where('user_id',$user->id)->where('status','!=','rejected')->where('status','!=','flagged')->count();
            
            if (intval($productLimit) <= $product) {
                return redirect()->route('seller.products')
                ->withErrors(['limit' => 'You have reached your maximum product upload limit of ' . $productLimit]);
            }
            
            $categories = Category::all();
            $chats = Chat::with(['product', 'product.user', 'sender', 'receiver'])
                ->where(function($query) use ($user) {
                    $query->where('sender_id', $user->id)
                          ->orWhere('receiver_id', $user->id);
                })
                ->latest()
                ->get()
                ->filter(function($chat) {
                    return $chat->product !== null;
                })
                ->groupBy('product_id');

            return view('seller.products.create', compact('categories','chats'));
        } catch (Exception $e) {
            return redirect()->route('seller.products')->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $user = auth()->user();
            $validated = $request->validated();
            
            $photos = [];
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('product-photos', 'public');
                    $photos[] = '/storage/' . str_replace('public/', '', $path);
                }
            }
            
            $product = Product::create([
                'user_id' => auth()->id(),
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'description' => $validated['description'],
                'condition' => $validated['condition'],
                'mrp' => $validated['mrp'],
                'selling_price' => $validated['mrp'],
                'price' => $validated['mrp'],
                'photos' => !empty($photos) ? $photos : null,
                'location' => $validated['location'] ?? null,
                'lat'     =>$request->location_lat ?? null,
                'long'     =>$request->location_long ?? null,
                'status' => 'approved',
                'payment_method' => json_encode($validated['payment_method']),
                'university_id' => $user->university_id,
            ]);

            return redirect()->route('seller.products')
                ->with('success', 'Product listed successfully');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function edit(Product $product)
    {  
        try {
            $user = auth()->user();
            $product = Product::where('id', $product->id)->first();
            $categories = Category::all();
            $chats = Chat::with(['product', 'product.user', 'sender', 'receiver'])
                ->where(function($query) use ($user) {
                    $query->where('sender_id', $user->id)
                          ->orWhere('receiver_id', $user->id);
                })
                ->latest()
                ->get()
                ->filter(function($chat) {
                    return $chat->product !== null;
                })
                ->groupBy('product_id');
            
            return view('seller.products.edit', compact('categories','product','chats'));
        } catch (Exception $e) {
            return redirect()->route('seller.products')->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, Product $product)
    {
        try {
            if ($request->isMethod('get')) {
                return redirect()->route('seller.products');
            }

            if ($product->user_id !== auth()->id()) {
                return redirect()->route('seller.products')
                    ->with('error', 'You are not authorized to edit this product.');
            }
            
            $validated = Validator::make($request->all(), [
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'condition' => 'required|in:New,Like New,Good,Fair,Poor',
                'mrp' => 'required|numeric|min:0',
                'payment_method.*' =>'required',
                // 'photos.*' => 'mimes:jpeg,jpg,png,gif|max:2048',
                'location' => 'required',
            ]);
            if ($validated->fails()) {
                return redirect()->back()->withInput()->withErrors($validated->errors());
            }

            $photos = [];
            
            if ($product->photos) {
                if (is_string($product->photos)) {
                    $photos = json_decode($product->photos, true) ?: [];
                } elseif (is_array($product->photos)) {
                    $photos = $product->photos;
                } elseif (is_object($product->photos)) {
                    $tempPhotos = json_decode(json_encode($product->photos), true);
                    if (is_array($tempPhotos)) {
                        $photos = array_values($tempPhotos);
                    }
                }
            }
            
            if ($request->has('removed_photos') && is_array($request->removed_photos)) {
                foreach ($request->removed_photos as $removedPhoto) {
                    $path = str_replace('/storage/', 'public/', $removedPhoto);
                    if (Storage::exists($path)) {
                        Storage::delete($path);
                    }
                    
                    $photos = array_values(array_filter($photos, function($photo) use ($removedPhoto) {
                        return $photo !== $removedPhoto;
                    }));
                }
            }
            
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('product-photos', 'public');
                    $photos[] = '/storage/' . str_replace('public/', '', $path);
                }
            }
            
            $data = [
                'user_id' => auth()->id(),
                'category_id' => $request->category_id,
                'name' => $request->name,
                'description' => $request->description,
                'condition' => $request->condition,
                'mrp' => $request->mrp,
                'selling_price' => $request->mrp,
                'price' => $request->mrp,
                'location' => $request->location ?? null,
                'lat'     =>$request->location_lat ?? null,
                'long'     =>$request->location_long ?? null,
                'photos' => $photos,
                'payment_method' => json_encode($request->payment_method),
            ];
            
            $product->update($data);
            return redirect()->route('seller.products.edit', $product)
                ->with('success', 'Product updated successfully');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function reserve(Request $request, Product $product)
    {
        try {
            $this->authorize('update', $product);
            
            $request->validate([
                'buyer_id' => 'required|exists:users,id'
            ]);
            
            $product->is_reserved = true;
            $product->reserved_for = $request->buyer_id;
            $product->reserved_at = now();
            $product->save();
            
            $buyer = \App\Models\User::find($request->buyer_id);
            $buyer->notify(new \App\Notifications\ProductReserved($product));
            
            return back()->with('success', 'Product has been reserved for the buyer.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function markAsSold(Request $request, Product $product)
    {
        try {
            $product->is_sold = true;
            $product->save();
            
            return redirect()->route('seller.products')->with('success', 'Product has been marked as sold.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function destroy(Product $product)
    { 
        try {
            if ($product->user_id !== auth()->id()) {
                return redirect()->route('seller.products')
                    ->with('error', 'You are not authorized to delete this product.');
            }
            
            $product->delete();
            
            return redirect()->route('seller.products')
                ->with('success', 'Product has been deleted successfully.');
        } catch (Exception $e) {
            return redirect()->route('seller.products')->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
}
