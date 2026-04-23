<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Tutorial;
use App\Models\User;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class TutorialController extends Controller
{
    use ApiResponse;
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = Tutorial::latest();
                    return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('description', function (Tutorial $tutorial) {
                            return e(Str::limit(strip_tags($tutorial->description), 100));
                        })
                        ->addColumn('video_url', function (Tutorial $tutorial) {
                            if (!$tutorial->video_url) {
                                return 'no video';
                            }
                            return '<video width="100" height="75" controls><source src="' . $tutorial->video_url . '" type="video/mp4"></video>';
                        })
                        ->addColumn('is_active', function (Tutorial $tutorial) {
                            $checked = $tutorial->is_active ? 'checked="checked"' : '';
                            return '
                            <a href="#" class="change_status d-inline-block"
                            data-id="' . $tutorial->id . '"
                            data-enabled="' . ($tutorial->is_active ? '0' : '1') . '"
                            data-title="Do you want to ' . ($tutorial->is_active ? 'Deactivate' : 'Activate') . ' it?"
                            data-description="This will change the Tutorial status."
                            data-bs-toggle="modal" data-bs-target="#statusModal" style="text-decoration: none;">
                                <label class="switch mb-0" style="transform: scale(0.65); transform-origin: center center; margin: 0; vertical-align: middle;">
                                    <input type="checkbox" ' . $checked . ' onclick="return false;">
                                    <span class="switch-state" style="transition: background-color 0.3s ease;"></span>
                                </label>
                            </a>';
                        })
                        ->addColumn('action', function (Tutorial $tutorial) {
                            return view('components.action-buttons', [
                                'id'     => $tutorial->id,
                                'show'   => 'admin.tutorials.show',
                                'edit'   => 'admin.tutorials.edit',
                                'delete' => true,
                            ])->render();
                        })
                        ->rawColumns(['description', 'video_url', 'is_active',   'action'])
                        ->make(true);
            } catch (Exception $e) {
                return $this->error([
                    'message' => 'Failed to fetch data.',
                    'error'   => $e->getMessage(),
                ], 'Error fetching data.', 500);
            }
        }

        return view('backend.layouts.tutorials.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         if (User::find(auth()->user()->id)) {
            return view('backend.layouts.tutorials.create');
        }
        return redirect()->route('admin.tutorials.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            if (User::find(auth()->user()->id)) {
                $validator = Validator::make($request->all(), [
                    'title'        => 'required|string|max:255',
                    'description'  => 'required|string|max:10000',
                    'video_url'    => 'nullable|file|mimes:mp4,avi,mov|max:51200', // 50 MB Maximum file size
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $video_url = null;
                if($request->hasFile('video_url')) {
                    $video_url = uploadFile($request->file('video_url'), 'uploads/tutorials');
                }

                $data               = new Tutorial();
                $data->title        = $request->title;
                $data->description  = $request->description;
                $data->video_url    = $video_url;

                $data->save();
            }
            flashMessage('Tutorial created successfully.', 'success');
            return redirect()->route('admin.tutorials.index');
        } catch (Exception $e) {
            flashMessage($e->getMessage(), 'error');
            return redirect()->route('admin.tutorials.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (User::find(auth()->user()->id)) {
            $tutorial = Tutorial::find($id);
            return view('backend.layouts.tutorials.show', compact('tutorial'));
        }
        return redirect()->route('admin.tutorials.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
         if (User::find(auth()->user()->id)) {
            $tutorial = Tutorial::find($id);
            return view('backend.layouts.tutorials.create', compact('tutorial'));
        }
        return redirect()->route('admin.tutorials.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            if (User::find(auth()->user()->id)) {
                $validator = Validator::make($request->all(), [
                    'title'        => 'required|string|max:255',
                    'description'  => 'required|string|max:10000',
                    'video_url'    => 'nullable|file|mimes:mp4,avi,mov|max:51200', // 50 MB Maximum file size
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $tutorial = Tutorial::find($id);
                if (!$tutorial) {
                    flashMessage('Tutorial not found.', 'error');
                    return redirect()->route('admin.tutorials.index');
                }

                $video_url = $tutorial->video_url;
                if($request->hasFile('video_url')) {
                    $video_url = uploadFile($request->file('video_url'), 'uploads/tutorials');
                }

                $tutorial->title        = $request->title;
                $tutorial->description  = $request->description;
                $tutorial->video_url    = $video_url;

                $tutorial->save();
            }
            flashMessage('Tutorial updated successfully.', 'success');
            return redirect()->route('admin.tutorials.index');
        } catch (Exception $e) {
            flashMessage($e->getMessage(), 'error');
            return redirect()->route('admin.tutorials.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tutorial = Tutorial::findOrFail($id);

        if (!$tutorial) {
            return response()->json([
                'success' => false,
                'message' => 'Tutorial not found.',
            ]);
        }

        if ($tutorial->video_url) {
            deleteFile($tutorial->video_url);
        }

        $tutorial->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully.',
        ]);
    }

    public function status(Request $request, Tutorial $tutorial)
    {
        try {
            $tutorial->is_active = !$tutorial->is_active;
            $tutorial->save();

            return response()->json([
                'success' => true,
                'message' => $tutorial->is_active ? 'Activated Successfully.' : 'Deactivated Successfully.',
                'data'    => $tutorial,
            ]);
        } catch (Exception $e) {
            return $this->error([
                'message' => 'Failed to update tutorial status.',
                'error'   => $e->getMessage(),
            ], 'Error updating status', 500);
        }
    }
}
