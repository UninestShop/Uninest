<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Http\Requests\ProductLimitRequest;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        try {
            $settings = Setting::pluck('value', 'key')->toArray();
            return view('admin.settings.index', compact('settings'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load settings: ' . $e->getMessage());
        }
    }

    public function update(SettingRequest $request)
    {
        try {
            $validated = $request->validated();
            Setting::updateOrCreate(
                ['key' => 'max_products_per_user'],
                ['value' => $validated['max_products_per_user']]
            );
            return redirect()->back()->with('success', 'Product limit updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update setting: ' . $e->getMessage());
        }
    }

    /**
     * Update the global product limit setting.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProductLimit(ProductLimitRequest $request)
    {
        try {
            $validated = $request->validated();
            // Update the global product limit setting
            Setting::updateOrCreate(
                ['key' => 'max_products_user'],
                ['value' => $validated['product_limit']]
            );

            return redirect()->back()->with('success', 'Product limit updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update product limit: ' . $e->getMessage());
        }
    }
}
