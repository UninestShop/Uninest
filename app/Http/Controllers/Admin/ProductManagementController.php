<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Category;
use App\Models\User;
use App\Models\ProductReport;
use App\Models\Contact;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Mail;
use App\Mail\RejectedProduct;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProductManagementRequest;
use App\Http\Requests\ProductStatusRequest;

class ProductManagementController extends Controller
{
    // Message constants for strict message management
    const MESSAGE_PRODUCT_APPROVED = 'Product has been approved successfully.';
    const MESSAGE_PRODUCT_REJECTED = 'Product has been rejected successfully.';
    const MESSAGE_PRODUCT_FLAGGED = 'Product has been flagged successfully.';
    const MESSAGE_UPLOAD_LIMIT_UPDATED = 'Upload limit has been updated successfully.';
    const MESSAGE_PRODUCT_CREATED = 'Product has been created successfully.';
    const MESSAGE_PRODUCT_UPDATED = 'Product has been updated successfully.';
    const MESSAGE_PRODUCT_DELETED = 'Product has been deleted successfully.';
    
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = Product::with('user')->orderBy('id', 'desc');
                
                if ($request->filled('status') && $request->status !== 'all') {
                    $query->where('status', $request->status);
                }
                
                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('actions', function($product) {
                        return view('admin.products.actions', compact('product'))->render();
                    })
                    ->rawColumns(['actions'])
                    ->make(true);
            }
            
            return view('admin.products.index');
        } catch (\Exception $e) {
            \Log::error('Error loading products index: ' . $e->getMessage());
            return $request->ajax() 
                ? response()->json(['error' => 'Failed to load products'], 500)
                : back()->with('error', 'Failed to load products. Please try again.');
        }
    }

    public function approve(Request $request, Product $product)
    {
        try {
            $oldStatus = $product->status;
            $product->status = 'approved';
            $product->save();
            
            if ($oldStatus === 'pending') {
                try {
                    $product->user->notify(new \App\Notifications\ProductApproved($product));
                } catch (\Exception $e) {
                    \Log::error('Failed to send notification: ' . $e->getMessage());
                }
            }
            
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => self::MESSAGE_PRODUCT_APPROVED]);
            }
            
            return back()->with('success', self::MESSAGE_PRODUCT_APPROVED);
        } catch (\Exception $e) {
            \Log::error('Failed to approve product: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to approve product'], 500);
            }
            return back()->with('error', 'Failed to approve product. Please try again.');
        }
    }

    public function approveById(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            return $this->approve($request, $product);
        } catch (\Exception $e) {
            \Log::error('Failed to approve product #' . $id . ': ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to find product'], 404);
            }
            return back()->with('error', 'Failed to find product. Please try again.');
        }
    }

    public function reject(Request $request, Product $product)
    {
        try {
            $oldStatus = $product->status;
            $product->status = 'rejected';
            $product->save();
            
            if ($oldStatus !== 'rejected') {
                try {
                    $product->user->notify(new \App\Notifications\ProductRejected($product));
                } catch (\Exception $e) {
                    \Log::error('Failed to send notification: ' . $e->getMessage());
                }
            }
            
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => self::MESSAGE_PRODUCT_REJECTED]);
            }
            
            return back()->with('success', self::MESSAGE_PRODUCT_REJECTED);
        } catch (\Exception $e) {
            \Log::error('Failed to reject product: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to reject product'], 500);
            }
            return back()->with('error', 'Failed to reject product. Please try again.');
        }
    }

    public function rejectById(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $user = User::where('id', $product->user_id)->first();
            $data = [
                'name' => $user->name,
                'product_name' => $product->name,
            ];
    
            return $this->reject($request, $product);
        } catch (\Exception $e) {
            \Log::error('Failed to reject product #' . $id . ': ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to find product'], 404);
            }
            return back()->with('error', 'Failed to find product. Please try again.');
        }
    }

    public function flag(Request $request, Product $product)
    {
        try {
            $product->status = 'flagged';
            $product->save();
            
            try {
                $product->user->notify(new \App\Notifications\ProductFlagged($product));
            } catch (\Exception $e) {
                \Log::error('Failed to send notification: ' . $e->getMessage());
            }
            
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => self::MESSAGE_PRODUCT_FLAGGED]);
            }
            
            return back()->with('success', self::MESSAGE_PRODUCT_FLAGGED);
        } catch (\Exception $e) {
            \Log::error('Failed to flag product: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to flag product'], 500);
            }
            return back()->with('error', 'Failed to flag product. Please try again.');
        }
    }

    public function flagById(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            return $this->flag($request, $product);
        } catch (\Exception $e) {
            \Log::error('Failed to flag product #' . $id . ': ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to find product'], 404);
            }
            return back()->with('error', 'Failed to find product. Please try again.');
        }
    }

    public function setUploadLimit(Request $request)
    {
        try {
            $request->validate([
                'product_limit' => 'required|integer|min:1'
            ]);

            Setting::updateOrCreate(
                ['key' => 'product_upload_limit'],
                ['value' => $request->product_limit]
            );

            return back()->with('success', self::MESSAGE_UPLOAD_LIMIT_UPDATED);
        } catch (\Exception $e) {
            \Log::error('Failed to update upload limit: ' . $e->getMessage());
            return back()->with('error', 'Failed to update upload limit. Please try again.');
        }
    }

    public function create()
    {
        try {
            $users = User::all(['id', 'name']);
            return view('admin.products.create', compact('users'));
        } catch (\Exception $e) {
            \Log::error('Failed to load create product page: ' . $e->getMessage());
            return back()->with('error', 'Failed to load create product page. Please try again.');
        }
    }

    public function store(ProductManagementRequest $request)
    {
        try {
            $validated = $request->validated();
            if ($request->hasFile('images')) {
                $imageArray = $this->handleProductImages($request->file('images'));
                $validated['photo'] = $imageArray;
                $validated['photos'] = $imageArray;
                \Log::info('Storing product with images', [
                    'image_count' => count($request->file('images')),
                    'photo_field' => $validated['photo']
                ]);
            }
            Product::create($validated);
            return redirect()->route('admin.products.index')->with('success', self::MESSAGE_PRODUCT_CREATED);
        } catch (\Exception $e) {
            \Log::error('Failed to store product: ' . $e->getMessage());
            return back()->with('error', 'Failed to create product. Please try again.')->withInput();
        }
    }

    public function edit(Product $product)
    {
        try {
            $categories = Category::all();
            $users = \App\Models\User::all(['id', 'name']);
            
            $productCount = Product::where('user_id', $product->user_id)->count();
            
            $productStats = [
                'total' => $productCount,
                'active' => Product::where('user_id', $product->user_id)
                    ->where('status', 'approved')
                    ->where('is_sold', false)
                    ->count(),
                'pending' => Product::where('user_id', $product->user_id)
                    ->where('status', 'pending')
                    ->count(),
                'sold' => Product::where('user_id', $product->user_id)
                    ->where('is_sold', true)
                    ->count()
            ];
            
            return view('admin.products.edit', compact('product', 'categories', 'users', 'productCount', 'productStats'));
        } catch (\Exception $e) {
            \Log::error('Failed to load edit product page: ' . $e->getMessage());
            return back()->with('error', 'Failed to load product edit page. Please try again.');
        }
    }

    public function update(ProductManagementRequest $request, Product $product)
    {
        try {
            \Log::info('Product update request data:', [
                'has_file_images' => $request->hasFile('images'),
                'has_file_photos' => $request->hasFile('photos'),
                'all_data' => $request->all(),
                'files' => $request->file(),
            ]);
            $validated = $request->validated();
            $hasNewImages = false;
            $newImagesArray = [];
            if ($request->hasFile('images')) {
                \Log::info('Processing images[] field', ['count' => count($request->file('images'))]);
                $newImagesArray = $this->handleProductImages($request->file('images'));
                $hasNewImages = true;
            } else if ($request->hasFile('photos')) {
                \Log::info('Processing photos[] field', ['count' => count($request->file('photos'))]);
                $newImagesArray = $this->handleProductImages($request->file('photos'));
                $hasNewImages = true;
            }
            $currentPhotos = $product->photos ?? $product->photo ?? [];
            if (!is_array($currentPhotos)) {
                if (is_string($currentPhotos) && $this->isJson($currentPhotos)) {
                    $currentPhotos = json_decode($currentPhotos, true);
                } else {
                    $currentPhotos = [];
                }
            }
            \Log::info('Current photos before update:', ['photos' => $currentPhotos]);
            if ($hasNewImages) {
                \Log::info('New images to add:', ['new_images' => $newImagesArray]);
                $combinedPhotos = array_merge($currentPhotos, $newImagesArray);
                $validated['photo'] = $combinedPhotos;
                $validated['photos'] = $combinedPhotos;
                \Log::info('Combined photos after adding new images:', ['combined' => $combinedPhotos]);
            } else {
                $validated['photo'] = $currentPhotos;
                $validated['photos'] = $currentPhotos;
            }
            if ($request->has('removed_photos') && !empty($request->removed_photos)) {
                try {
                    $removedPhotos = $request->removed_photos;
                    \Log::info('Raw removed_photos data:', [
                        'type' => gettype($removedPhotos),
                        'value' => $removedPhotos
                    ]);
                    if (!is_array($removedPhotos)) {
                        if (is_string($removedPhotos) && $this->isJson($removedPhotos)) {
                            $removedPhotos = json_decode($removedPhotos, true);
                        } else {
                            $removedPhotos = explode(',', $removedPhotos);
                        }
                    }
                    $removedPhotos = array_filter($removedPhotos);
                    \Log::info('Processing removed photos:', [
                        'removed_photos' => $removedPhotos,
                        'current_photos' => $validated['photos']
                    ]);
                    if (!empty($removedPhotos)) {
                        $photosToKeep = [];
                        $removedBasenames = array_map(function($path) {
                            return basename($path);
                        }, $removedPhotos);
                        \Log::info('Removed photo basenames:', ['basenames' => $removedBasenames]);
                        foreach ($validated['photos'] as $path) {
                            $currentBasename = basename($path);
                            if (!in_array($currentBasename, $removedBasenames)) {
                                $photosToKeep[] = $path;
                            } else {
                                \Log::info('Will remove photo from database:', ['path' => $path]);
                                try {
                                    $storagePath = str_replace('/storage/', '', $path);
                                    if (Storage::disk('public')->exists($storagePath)) {
                                        Storage::disk('public')->delete($storagePath);
                                        \Log::info('Deleted image file: ' . $storagePath);
                                    } else {
                                        $alternatePath = 'product-photos/' . basename($path);
                                        if (Storage::disk('public')->exists($alternatePath)) {
                                            Storage::disk('public')->delete($alternatePath);
                                            \Log::info('Deleted image file using alternate path: ' . $alternatePath);
                                        } else {
                                            \Log::warning('Could not find image file to delete: ' . $path);
                                        }
                                    }
                                } catch (\Exception $e) {
                                    \Log::error('Failed to delete file ' . $path . ': ' . $e->getMessage());
                                }
                            }
                        }
                        $validated['photos'] = $photosToKeep;
                        $validated['photo'] = $photosToKeep;
                        \Log::info('Photos after removal:', [
                            'photos_to_keep' => $photosToKeep,
                            'removed_count' => count($validated['photos']) - count($photosToKeep)
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to process removed photos: ' . $e->getMessage(), [
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }
            if (!isset($validated['photos'])) {
                $validated['photos'] = $currentPhotos;
                $validated['photo'] = $currentPhotos;
            }
            \Log::info('Final photo values before update:', [
                'photos' => $validated['photos'],
                'photo' => $validated['photo']
            ]);
            $product->update($validated);
            $updatedProduct = Product::find($product->id);
            \Log::info('Product updated', [
                'photo_field' => $updatedProduct->photo,
                'photos_field' => $updatedProduct->photos
            ]);
            return redirect()->route('admin.products.index')->with('success', self::MESSAGE_PRODUCT_UPDATED);
        } catch (\Exception $e) {
            \Log::error('Failed to update product: ' . $e->getMessage());
            return back()->with('error', 'Failed to update product. Please try again.')->withInput();
        }
    }

    private function handleProductImages($images)
    {
        $imageUrls = [];
        
        foreach ($images as $image) {
            try {
                if (!$image->isValid()) {
                    \Log::warning('Invalid image file uploaded', [
                        'name' => $image->getClientOriginalName(),
                        'error' => $image->getError()
                    ]);
                    continue;
                }
                
                $filename = time() . '_' . uniqid() . '_' . $image->getClientOriginalName();
                
                $path = $image->storeAs('product-photos', $filename, 'public');
                
                if (!$path) {
                    \Log::error('Failed to store image - path is empty', [
                        'original_name' => $image->getClientOriginalName(),
                        'mime_type' => $image->getMimeType(),
                        'size' => $image->getSize()
                    ]);
                    continue;
                }
                
                $imageUrl = '/storage/product-photos/' . $filename;
                $imageUrls[] = $imageUrl;
                
                \Log::info('Image successfully stored', [
                    'original' => $image->getClientOriginalName(),
                    'stored_path' => $path,
                    'url' => $imageUrl
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to store image', [
                    'name' => $image->getClientOriginalName(),
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
        
        \Log::info('Final processed image URLs', ['urls' => $imageUrls]);
        
        return $imageUrls;
    }

    public function destroy(Product $product)
    {
        try {
            $photos = $product->photos ?? $product->photo ?? [];
            if (is_array($photos) && count($photos) > 0) {
                foreach ($photos as $photoPath) {
                    try {
                        $storagePath = str_replace('/storage/', '', $photoPath);
                        
                        if (Storage::disk('public')->exists($storagePath)) {
                            Storage::disk('public')->delete($storagePath);
                            \Log::info('Deleted product image during product deletion: ' . $storagePath);
                        } else {
                       
                            $alternatePath = 'product-photos/' . basename($photoPath);
                            if (Storage::disk('public')->exists($alternatePath)) {
                                Storage::disk('public')->delete($alternatePath);
                                \Log::info('Deleted product image using alternate path: ' . $alternatePath);
                            }
                        }
                    } catch (\Exception $e) {
                        \Log::error('Failed to delete product image during product deletion: ' . $e->getMessage());
                    }
                }
            }
            
            $product->delete();
            return redirect()->route('admin.products.index')->with('success', self::MESSAGE_PRODUCT_DELETED);
        } catch (\Exception $e) {
            \Log::error('Failed to delete product: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete product. Please try again.');
        }
    }

    /**
     * Check if a string is valid JSON
     *
     * @param string $string
     * @return bool
     */
    private function isJson($string) {
        if (!is_string($string)) {
            return false;
        }
        
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Display product limits dashboard
     */
    public function productLimits()
    {
        try {
            $maxProducts = Setting::getValue('max_products_per_user', 10);
            
            $users = User::withCount('products')
                ->orderByDesc('products_count')
                ->paginate(15);
                
            $approachingLimit = User::withCount('products')
                ->having('products_count', '>=', $maxProducts * 0.8)
                ->orderByDesc('products_count')
                ->take(5)
                ->get();
                
            return view('admin.products.limits', compact('maxProducts', 'users', 'approachingLimit'));
        } catch (\Exception $e) {
            \Log::error('Failed to load product limits dashboard: ' . $e->getMessage());
            return back()->with('error', 'Failed to load product limits dashboard. Please try again.');
        }
    }
    
    /**
     * Set or update the product upload limit for a specific user
     */
    public function setUserProductLimit(Request $request, User $user)
    {
        try {
            $request->validate([
                'limit' => 'required|integer|min:0'
            ]);
            
            Setting::updateOrCreate(
                ['key' => 'max_products_user_' . $user->id],
                ['value' => $request->limit]
            );
            
            return redirect()->back()->with('success', "Product limit for {$user->name} has been set to {$request->limit}");
        } catch (\Exception $e) {
            \Log::error('Failed to set user product limit: ' . $e->getMessage());
            return back()->with('error', 'Failed to set user product limit. Please try again.')->withInput();
        }
    }
    
    /**
     * Reset user-specific product limit
     */
    public function resetUserProductLimit(User $user)
    {
        try {
            Setting::where('key', 'max_products_user_' . $user->id)->delete();
            
            return redirect()->back()->with('success', "Product limit for {$user->name} has been reset to system default");
        } catch (\Exception $e) {
            \Log::error('Failed to reset user product limit: ' . $e->getMessage());
            return back()->with('error', 'Failed to reset user product limit. Please try again.');
        }
    }

    public function productreport(){
        try {
            $data = ProductReport::with('user','product')->orderBy('id','desc')->get();
            
            return view('admin.productreport.index',compact('data'));
        } catch (\Exception $e) {
            \Log::error('Failed to load product reports: ' . $e->getMessage());
            return back()->with('error', 'Failed to load product reports. Please try again.');
        }
    }

    /**
     * Update the status of a product.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateStatus($id, ProductStatusRequest $request)
    {
        try {
            $product = Product::findOrFail($id);
            $product->status = $request->status;
            $product->save();
            return redirect()->back()->with('success', 'Product status has been updated to ' . ucfirst($request->status));
        } catch (\Exception $e) {
            \Log::error('Failed to update product status: ' . $e->getMessage());
            return back()->with('error', 'Failed to update product status. Please try again.');
        }
    }

    public function inquiry(){
        try {
            $data = Contact::orderBy('id','desc')->get();
            
            return view('admin.inquiry.index',compact('data'));
        } catch (\Exception $e) {
            \Log::error('Failed to load inquiries: ' . $e->getMessage());
            return back()->with('error', 'Failed to load inquiries. Please try again.');
        }
    }
}
