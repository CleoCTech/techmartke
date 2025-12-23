<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlbumCollection;
use Illuminate\Http\Request;
use App\Traits\Admin\LayoutTrait;
use App\Traits\Admin\UploadFileTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AlbumCollectionController extends Controller
{
    use LayoutTrait;
    use UploadFileTrait;
    
    public $settings = [
        'model' => '\\App\\Models\\AlbumCollection',
        'caption' => "Album Collections",
        'xFolder' => "Admin/Pages/AlbumCollections",
        'indexRoute' => "/admin/album-collections",
        'storageName' => "album-collections",
        'isList' => true,
        'isCreate' => true,
        'isView' => true,
        'isEdit' => true,
        'isDelete' => true,
        'isActions' => true,
        'isAttachments' => true,
        'isReports' => false,
        'isCharts' => false,
        'isSearch' => true,
        'isSelect' => true,
        'isMoreActions' => false,
        'xMoreActions' => null,
        'isExport' => false,
        'orderBy' => ['column' => 'sort_order', 'order' => 'ASC'],
    ];

    public function __construct()
    {
        $this->middleware('gen-auth');
        $this->middleware('admin-auth');
        $this->defaultModel = $this->settings['model'];
        $this->setup['categories'] = $this->defaultModel::options('category');
        $this->setup['tableName'] = $this->defaultModel::getTableName();
        $this->def_constructor();
    }

    public function index()
    {
        $this->def_index();
        return Inertia::render($this->settings['xFolder'].'/Index', $this->viewData);
    }

    public function create()
    {
        $this->def_create();
        return Inertia::render($this->settings['xFolder'].'/CreateEdit', $this->viewData);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_published' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'metadata' => 'nullable|array'
        ]);

        DB::beginTransaction();
        try {
            $this->pKey = $request->uuid == 'null' ? null : $request->uuid;
            
            $record = [
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category ?? 'General',
                'sort_order' => $request->sort_order ?? 0,
                'is_published' => filter_var($request->is_published, FILTER_VALIDATE_BOOLEAN),
                'is_featured' => filter_var($request->is_featured ?? false, FILTER_VALIDATE_BOOLEAN),
                'metadata' => $request->metadata ?? [],
            ];

            $albumCollection = $this->defaultModel::updateOrCreate(["uuid" => $this->pKey], $record);

            if ($request->hasFile('cover_image')) {
                $fileName = $this->generateFileName($request->file('cover_image'));
                $fileData = [
                    'file' => $request->file('cover_image'),
                    'fileName' => $fileName,
                    'storageName' => $this->settings['storageName'],
                    'prevFile' => $albumCollection->cover_image
                ];
                
                if ($this->uploadFile($fileData)) {
                    $albumCollection->update(['cover_image' => $fileName]);
                }
            }

            DB::commit();
            $response = $this->notification('success');
            return response()->json($response, 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            $response = $this->notification('error');
            return response()->json($response, 500);
        }
    }

    public function show($uuid)
    {
        $this->def_show($uuid);
        return Inertia::render($this->settings['xFolder'].'/Show', $this->viewData);
    }

    public function edit($uuid)
    {
        $this->def_edit($uuid);
        return Inertia::render($this->settings['xFolder'].'/CreateEdit', $this->viewData);
    }

    public function update(Request $request, $uuid)
    {
        return $this->store($request);
    }

    public function destroy($uuid)
    {
        $this->def_destroy($uuid);
        return response()->json($this->notification('success'), 200);
    }

    public function deleteImage($albumUuid, $imageId)
    {
        try {
            $albumCollection = $this->defaultModel::where('uuid', $albumUuid)->firstOrFail();
            $image = $albumCollection->images()->findOrFail($imageId);
            
            // Delete the image file if it exists
            if ($image->image_path && Storage::exists($image->image_path)) {
                Storage::delete($image->image_path);
            }
            
            $image->delete();
            
            return response()->json(['message' => 'Image deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['message' => 'Error deleting image'], 500);
        }
    }

    public function report($name)
    {
        $this->defReport();
        $this->viewData['name'] = $name;
        $this->viewData['fileName'] = 'album-collections-report.pdf';
        return $this->viewData;
    }
}

