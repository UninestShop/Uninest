<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\University;
use App\Http\Requests\UniversityRequest;
use Illuminate\Http\Request;
use DataTables;

class UniversityController extends Controller
{
    /**
     * Display a listing of universities.
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = University::query();
                
                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('status_badge', function($university) {
                        return $university->status_badge;
                    })
                    ->addColumn('students_count', function($university) {
                        return $university->students()->count();
                    })
                    ->addColumn('actions', function($university) {
                        return view('admin.universities.actions', compact('university'))->render();
                    })
                    ->rawColumns(['status_badge', 'actions'])
                    ->make(true);
            }
            
            return view('admin.universities.index');
        } catch (\Exception $e) {
            \Log::error('Error in university index: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while loading universities.');
        }
    }

    /**
     * Show the form for creating a new university.
     */
    public function create()
    {
        try {
            return view('admin.universities.create');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while loading the create form.');
        }
    }

    /**
     * Store a newly created university.
     */
    public function store(UniversityRequest $request)
    {
        try {
            $validated = $request->validated();
            
            // Ensure domains is properly formatted as JSON
            if (isset($validated['domains']) && !is_null($validated['domains'])) {
                if (is_string($validated['domains']) && !json_decode($validated['domains'])) {
                    // Convert string to array (assuming comma-separated domains)
                    $domainsArray = array_map('trim', explode(',', $validated['domains']));
                    $validated['domains'] = json_encode($domainsArray);
                } elseif (is_array($validated['domains'])) {
                    $validated['domains'] = json_encode($validated['domains']);
                }
            }

            if (!array_key_exists('country', $validated)) {
                $validated['country'] = 'United States';
                $validated['alpha_two_code'] = 'US';
            }
            
            University::create($validated);
            
            return redirect()->route('admin.universities.index')
                ->with('success', 'University created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while creating the university: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified university for editing.
     */
    public function edit(University $university)
    {
        try {
            return view('admin.universities.edit', compact('university'));
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while loading the edit form.');
        }
    }

    /**
     * Update the specified university.
     */
    public function update(UniversityRequest $request, University $university)
    {
        try {
            $validated = $request->validated();

            if (isset($validated['domains']) && !is_null($validated['domains'])) {
                if (is_string($validated['domains']) && !json_decode($validated['domains'])) {
                    // Convert string to array (assuming comma-separated domains)
                    $domainsArray = array_map('trim', explode(',', $validated['domains']));
                    $validated['domains'] = json_encode($domainsArray);
                } elseif (is_array($validated['domains'])) {
                    $validated['domains'] = json_encode($validated['domains']);
                }
            }
            
            $university->update($validated);
            
            return redirect()->route('admin.universities.index')
                ->with('success', 'University updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while updating the university.')->withInput();
        }
    }

    /**
     * Remove the specified university.
     */
    public function destroy(University $university)
    {   
        try {
            $university->delete();
            
            return redirect()->route('admin.universities.index')
                ->with('success', 'University deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting the university.');
        }
    }

    public function getData(Request $request)
    {
        try {
            // Fetch real universities from the database
            $universities = University::where('country','United States')->select(['id', 'name','domains'])->get();
            
            // Format data for DataTables
            $formattedData = [];
            foreach ($universities as $university) {
                $formattedData[] = [
                    'id' => $university->id,
                    'name' => $university->name,
                    'actions' => view('admin.universities.partials.actions', ['university' => $university])->render()
                ];
            }
            
            // Return in DataTables format
            return response()->json([
                'draw' => intval($request->input('draw', 1)),
                'recordsTotal' => count($formattedData),
                'recordsFiltered' => count($formattedData),
                'data' => $formattedData
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch data: ' . $e->getMessage()], 500);
        }
    }
}
