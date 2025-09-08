<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlbumCollection;
use App\Models\AlbumImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Traits\Admin\LayoutTrait;
use App\Traits\Admin\UploadFileTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AlbumCollectionController extends Controller
{
    use LayoutTrait;
    use UploadFileTrait;

    public $settings = [
        'model' => '\\App\\Models\\AlbumCollection',
        'caption' => "Album Collection",
        'xFolder' => "Admin/Pages/AlbumCollections",
        'indexRoute' => "/admin/album-collections",
        'storageName' => "album_collections",
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
        'isReltionship' => true,
        'relationName' => 'images',
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
        $this->isReltionship = $this->settings['isReltionship'];
        $this->relationName = $this->settings['relationName'];
        $this->setup['statuses'] = $this->defaultModel::options('is_published');
        $this->setup['categories'] = $this->defaultModel::options('category');
        $this->setup['tableName'] = $this->defaultModel::getTableName();
        $this->def_constructor();
    }
    public function index()
    {
        $this->def_index();
        return Inertia::render($this->settings['xFolder'] . '/Index', $this->viewData);
    }

    public function create()
    {
        $this->def_create();
        return Inertia::render($this->settings['xFolder'] . '/CreateEdit', $this->viewData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Cast boolean fields
        $request->merge([
            'is_featured' => filter_var($request->is_featured, FILTER_VALIDATE_BOOLEAN),
            'is_published' => filter_var($request->is_published, FILTER_VALIDATE_BOOLEAN),
        ]);

        $validated = $request->validate([
            'uuid' => 'nullable|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'is_featured' => 'required|boolean',
            'is_published' => 'required|boolean',
            'sort_order' => 'required|integer|min:0',
            'metadata' => 'nullable|array'
        ]);

        DB::beginTransaction();
        try {
            $this->pKey = $request->uuid == 'null' ? null : $request->uuid;
            
            $record = [
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'is_featured' => $request->is_featured,
                'is_published' => $request->is_published,
                'sort_order' => $request->sort_order,
                'metadata' => $request->metadata ?? []
            ];

            $albumCollection = $this->defaultModel::updateOrCreate(["uuid" => $this->pKey], $record);

            // Handle cover image
            if ($request->hasFile('cover_image')) {
                $coverImagePath = $this->storeImage($request->file('cover_image'), 'album_covers');
                $albumCollection->update(['cover_image' => $coverImagePath]);
            }

            // Handle multiple images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $imagePath = $this->storeImage($image, 'album_images');
                    $metadata = $this->getImageMetadata($image);
                    
                    AlbumImage::create([
                        'album_collection_id' => $albumCollection->id,
                        'image_path' => $imagePath,
                        'original_filename' => $image->getClientOriginalName(),
                        'alt_text' => $request->input("alt_text.{$index}"),
                        'caption' => $request->input("caption.{$index}"),
                        'sort_order' => $index,
                        'image_metadata' => $metadata
                    ]);
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
        return Inertia::render($this->settings['xFolder'] . '/Show', $this->viewData);
    }

    public function edit($uuid)
    {
        $this->def_edit($uuid);
        return Inertia::render($this->settings['xFolder'] . '/CreateEdit', $this->viewData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AlbumCollection $albumCollection)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'metadata' => 'nullable|array'
        ]);

        $albumCollection->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'is_featured' => $request->boolean('is_featured'),
            'is_published' => $request->boolean('is_published'),
            'metadata' => $request->metadata
        ]);

        // Handle cover image update
        if ($request->hasFile('cover_image')) {
            // Delete old cover image
            if ($albumCollection->cover_image) {
                Storage::delete($albumCollection->cover_image);
            }
            
            $coverImage = $request->file('cover_image');
            $coverPath = $this->storeImage($coverImage, 'album-covers');
            $albumCollection->update(['cover_image' => $coverPath]);
        }

        // Handle additional images
        if ($request->hasFile('images')) {
            $maxSortOrder = $albumCollection->images()->max('sort_order') ?? -1;
            
            foreach ($request->file('images') as $index => $image) {
                $imagePath = $this->storeImage($image, 'album-images');
                
                AlbumImage::create([
                    'album_collection_id' => $albumCollection->id,
                    'image_path' => $imagePath,
                    'original_filename' => $image->getClientOriginalName(),
                    'sort_order' => $maxSortOrder + $index + 1,
                    'image_metadata' => $this->getImageMetadata($image)
                ]);
            }
        }

        return redirect()->route('admin.album-collections.index')
            ->with('success', 'Album collection updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($uuid)
    {
        $record = $this->defaultModel::where('uuid', $uuid)->first();
        
        // Delete cover image
        if ($record->cover_image) {
            Storage::delete($record->cover_image);
        }

        // Delete all album images
        foreach ($record->images as $image) {
            Storage::delete($image->image_path);
            $image->delete();
        }

        $record->delete();
        $response = $this->notification('deleteSuccess');
        return response()->json($response, 200);
    }

    /**
     * Delete a specific image from an album
     */
    public function deleteImage(AlbumCollection $albumCollection, AlbumImage $image)
    {
        if ($image->album_collection_id !== $albumCollection->id) {
            abort(404);
        }

        Storage::delete($image->image_path);
        $image->delete();

        return back()->with('success', 'Image deleted successfully!');
    }

    /**
     * Update image sort order
     */
    public function updateImageOrder(Request $request, AlbumCollection $albumCollection)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*.id' => 'required|exists:album_images,id',
            'images.*.sort_order' => 'required|integer'
        ]);

        foreach ($request->images as $imageData) {
            AlbumImage::where('id', $imageData['id'])
                ->where('album_collection_id', $albumCollection->id)
                ->update(['sort_order' => $imageData['sort_order']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Store image and return path
     */
    private function storeImage($image, $folder)
    {
        $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs($folder, $filename, 'public');
        
        // Generate thumbnail if needed
        $this->generateThumbnail($path);
        
        return $path;
    }

    /**
     * Generate thumbnail for image
     */
    private function generateThumbnail($imagePath)
    {
        try {
            $fullPath = Storage::disk('public')->path($imagePath);
            $thumbnailPath = str_replace('.', '_thumb.', $imagePath);
            
            // Create ImageManager instance with GD driver
            $manager = new ImageManager(new Driver());
            
            $image = $manager->read($fullPath);
            $image->resize(300, 300);
            $image->save(Storage::disk('public')->path($thumbnailPath));
        } catch (\Exception $e) {
            // Log error but don't fail the upload
            \Log::error('Thumbnail generation failed: ' . $e->getMessage());
        }
    }

    /**
     * Get image metadata
     */
    private function getImageMetadata($image)
    {
        try {
            $imageInfo = getimagesize($image->getRealPath());
            return [
                'width' => $imageInfo[0] ?? null,
                'height' => $imageInfo[1] ?? null,
                'mime_type' => $imageInfo['mime'] ?? null,
                'file_size' => $image->getSize()
            ];
        } catch (\Exception $e) {
            return [
                'file_size' => $image->getSize()
            ];
        }
    }
}
