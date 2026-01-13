<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CmsPageRequest;
use App\Models\CmsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CmsPageController extends Controller
{
    /**
     * Display a listing of the CMS pages.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $pages = CmsPage::orderBy('id','desc')->get();
            return view('admin.cms.index', compact('pages'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load CMS pages: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new CMS page.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            return view('admin.cms.create');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load create CMS page: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created CMS page in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CmsPageRequest $request)
    {
        try {
            $validated = $request->validated();
            CmsPage::create($validated);
            return redirect()->route('admin.cms.index')
                ->with('success', 'CMS page created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create CMS page: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified CMS page.
     *
     * @param  \App\Models\CmsPage  $cmsPage
     * @return \Illuminate\Http\Response
     */
    public function edit(CmsPage $cmsPage, $id)
    {
        try {
            $data = CmsPage::where('id',$id)->first();
            return view('admin.cms.edit', compact('data'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load edit CMS page: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified CMS page in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CmsPage  $cmsPage
     * @return \Illuminate\Http\Response
     */
    public function update(CmsPageRequest $request, CmsPage $cmsPage, $id)
    {
        try {
            $data = CmsPage::where('id',$id)->first();
            $validated = $request->validated();
            $data->update($validated);
            return redirect()->route('admin.cms.index')
                ->with('success', 'CMS page updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update CMS page: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified CMS page from storage.
     *
     * @param  \App\Models\CmsPage  $cmsPage
     * @return \Illuminate\Http\Response
     */
    public function destroy(CmsPage $cmsPage, $id)
    {
        try {
            $data = CmsPage::where('id',$id)->first();
            $data->delete();
            return redirect()->route('admin.cms.index')
                ->with('success', 'CMS page deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete CMS page: ' . $e->getMessage());
        }
    }
}
