<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductReportRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductReport;
use App\Models\Admin;
use App\Notifications\ProductReported;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class ProductController extends Controller
{
    public function __construct()
    {
        try {
            $this->middleware('auth')->except(['index', 'show', 'byCategory']);
        } catch (Exception $e) {
            Log::error('Error in ProductController constructor: ' . $e->getMessage());
            throw $e;
        }
    }

    public function index(Request $request)
    {
        try {
            $query = Product::with('category');
            
            $query->where(function($q) {
                $q->where('status', 'approved')->where('is_sold',0);
                
                if (auth()->check()) {
                    $q->orWhere('user_id', auth()->id());
                }
            });
            
            // Apply search filters
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('name', 'like', "%{$search}%");
            }
         
            if ($request->filled('category')) {
                $query->where('category_id', $request->category);
            }
            
            if ($request->filled('condition')) {
                $query->where('condition', $request->condition);
            }
           
            if ($request->filled('price')) {
                list($min, $max) = explode('-', $request->price);
                $query->whereBetween('selling_price', [$min, $max]);
            }
            
            // Location-based filtering
            $radius = $request->filled('radius') ? (int)$request->radius : 8; // Default radius is 8km
            
            // Get coordinates either from request or from authenticated user
            $lat = null;
            $lng = null;
            
            if ($request->filled('lat') && $request->filled('long')) {
                $lat = (float) $request->lat;
                $lng = (float) $request->long;
            } elseif (auth()->check() && auth()->user()->latitude && auth()->user()->longitude) {
                $lat = (float) auth()->user()->latitude;
                $lng = (float) auth()->user()->longitude;
            }
            
            if ($lat && $lng) {
                $query = Product::selectRaw("products.*, 
                    ( 6371 * acos( cos( radians($lat) ) * 
                      cos( radians( lat ) ) * 
                      cos( radians( `long` ) - radians($lng)) + 
                      sin( radians($lat) ) * 
                      sin( radians( lat ) ) 
                    ) ) AS distance")
                    ->with('category')
                    ->where(function($q) {
                        $q->where('status', 'approved')->where('is_sold', 0);
                        if (auth()->check()) {
                            $q->orWhere('user_id', auth()->id());
                        }
                    });
                    
                // Re-apply all filters for the new query
                if ($request->filled('search')) {
                    $search = $request->search;
                    $query->where('name', 'like', "%{$search}%");
                }
                
                if ($request->filled('category')) {
                    $query->where('category_id', $request->category);
                }
                
                if ($request->filled('condition')) {
                    $query->where('condition', $request->condition);
                }
                
                if ($request->filled('price')) {
                    list($min, $max) = explode('-', $request->price);
                    $query->whereBetween('selling_price', [$min, $max]);
                }
                
                $query->having('distance', '<=', $radius);
            }
            
            // Apply sorting
            if ($request->has('sort')) {
                switch ($request->sort) {
                    case 'price_asc':
                        $query->orderBy('selling_price', 'asc');
                        break;
                    case 'price_desc':
                        $query->orderBy('selling_price', 'desc');
                        break;
                    case 'nearest':
                        if (isset($lat) && isset($lng)) {
                            $query->orderBy('distance', 'asc');
                        } else {
                            $query->orderBy('created_at', 'desc');
                        }
                        break;
                    case 'newest':
                    default:
                        $query->orderBy('created_at', 'desc');
                }
            } else {
                $query->orderBy('created_at', 'desc');
            }
            
            $products = $query->paginate(12);
            $categories = Category::all();
            
            return view('products.index', compact('products', 'categories', 'radius'));
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function create()
    {
        try {
            $categories = Category::all();
            return view('products.create', compact('categories'));
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function store(ProductRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            $product = Product::create($data);

            return redirect()->route('products.show', $product->id)
                ->with('success', 'Product created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function show(Product $product)
    {
        try {
            Log::info('Product ID: ' . $product->id);
            Log::info('Product Status: ' . $product->status);
            Log::info('Product User ID: ' . $product->user_id);
            Log::info('Current User ID: ' . (auth()->id() ?? 'Not logged in'));
            
            if ($product->status !== 'approved' && (!auth()->check() || $product->user_id !== auth()->id())) {
                if (config('app.debug')) {
                    $reason = $product->status !== 'approved' ? 'Product not approved' : 'Access denied';
                    abort(404, $reason);
                } else {
                    abort(404);
                }
            }
            
            $product->increment('views_count');
            $product->update(['last_viewed_at' => now()]);

            $canBuy = auth()->check() && auth()->id() !== $product->user_id;

            return view('products.show', [
                'product' => $product->load(['user', 'category']),
                'canBuy' => $canBuy,
                'relatedProducts' => Product::where('category_id', $product->category_id)
                    ->where('id', '!=', $product->id)
                    ->where(function($q) {
                        $q->where('status', 'approved');
                        if (auth()->check()) {
                            $q->orWhere('user_id', auth()->id());
                        }
                    })
                    ->where('is_sold', false)
                    ->take(4)
                    ->get()
            ]);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function byCategory(Category $category)
    {
        try {
            $products = Product::with(['user', 'category'])
                ->where('category_id', $category->id)
                ->where('status', 'approved')
                ->where('is_sold', false)
                ->latest()
                ->paginate(12);

            $category->loadCount(['products' => function($query) {
                $query->where('status', 'approved')
                     ->where('is_sold', false);
            }]);

            return view('products.category', compact('category', 'products'));
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function edit(Product $product)
    {
        try {
            if ($product->user_id != Auth::id()) {
                return redirect()->route('products.index')
                    ->with('error', 'You are not authorized to edit this product.');
            }

            $categories = Category::all();
            return view('products.edit', compact('product', 'categories'));
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function update(ProductRequest $request, Product $product)
    {
        try {
            if ($product->user_id != Auth::id()) {
                return redirect()->route('products.index')
                    ->with('error', 'You are not authorized to update this product.');
            }

            $data = $request->validated();
            
            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            $product->update($data);

            return redirect()->route('products.show', $product->id)
                ->with('success', 'Product updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function destroy(Product $product)
    {
        try {
            if ($product->user_id != Auth::id()) {
                return redirect()->route('products.index')
                    ->with('error', 'You are not authorized to delete this product.');
            }

            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $product->delete();

            return redirect()->route('products.index')
                ->with('success', 'Product deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function report(ProductReportRequest $request, Product $product)
    {
        try {
            $validated = $request->validated();
            
            $report = ProductReport::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'reason' => $validated['report_reason'],
                'details' => $validated['report_details'] ?? null,
                'status' => 'pending'
            ]);
            
            return redirect()->back()->with('success', 'Thank you for reporting this listing. Our team will review it shortly.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
}
