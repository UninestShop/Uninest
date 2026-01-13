<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function index()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            Artisan::call('config:clear');

            $data = array();
            $user=Auth::user();
            
            $data['categories'] = Category::all();
            
            if(is_null($user)){
            $data['featuredProducts'] = Product::with('user')
                ->where('status', 'approved')
                ->where('is_sold', 0)
                ->latest()
                ->take(8)
                ->get();
            }else{
                $lat = (float) $user->latitude;
                $lng = (float) $user->longitude; // Fixed typo in longitude
                $radius = 8; 
                $data['featuredProducts'] = Product::selectRaw("products.*, ( 6371 * acos( cos( radians($lat) ) * cos( radians( lat ) ) * cos( radians( `long` ) - radians($lng)) + sin( radians($lat) ) * sin( radians( lat ) ) ) ) AS distance")
                    ->where('status', 'approved')
                    ->where('is_sold', 0)
                    // ->having('distance', '<=', $radius)
                    ->with('user')
                    ->latest()
                    ->take(8)
                    ->get();
            }
            return view('welcome', $data);
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred: ' . $th->getMessage()]);
            //throw $th;
        }
    }

    public function saveLocation(Request $request)
    {
        try {
            $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);
      
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');

            $user = auth()->user();
            
            $user->latitude = $latitude;
            $user->longitude = $longitude;
            $user->save();

            // return response()->json([
            //     'message' => 'Location updated successfully',
            //     'latitude' => $latitude,
            //     'longitude' => $longitude,
            //     'updated_at' => $user->location_updated_at,
            // ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update location',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
