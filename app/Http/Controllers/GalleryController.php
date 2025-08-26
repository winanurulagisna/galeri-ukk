<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $galleries = Gallery::with('post')
                ->orderBy('id', 'desc')
                ->get();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Galleries retrieved successfully',
                'data' => $galleries
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve galleries',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:150',
                'caption' => 'nullable|string',
                'file_path' => 'required|string|max:255',
                'post_id' => 'nullable|integer|exists:posts,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $gallery = new Gallery();
            $gallery->title = $request->title;
            $gallery->caption = $request->caption;
            $gallery->file_path = $request->file_path;
            $gallery->post_id = $request->post_id;
            $gallery->save();

            // Load the post relationship
            $gallery->load('post');

            return response()->json([
                'status' => 'success',
                'message' => 'Gallery created successfully',
                'data' => $gallery
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create gallery',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $gallery = Gallery::with('post')->find($id);
            
            if (!$gallery) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gallery not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Gallery retrieved successfully',
                'data' => $gallery
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve gallery',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $gallery = Gallery::find($id);
            
            if (!$gallery) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gallery not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:150',
                'caption' => 'nullable|string',
                'file_path' => 'required|string|max:255',
                'post_id' => 'nullable|integer|exists:posts,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $gallery->title = $request->title;
            $gallery->caption = $request->caption;
            $gallery->file_path = $request->file_path;
            $gallery->post_id = $request->post_id;
            $gallery->save();

            // Load the post relationship
            $gallery->load('post');

            return response()->json([
                'status' => 'success',
                'message' => 'Gallery updated successfully',
                'data' => $gallery
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update gallery',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $gallery = Gallery::find($id);
            
            if (!$gallery) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gallery not found'
                ], 404);
            }

            // Optional: Delete the actual file from storage
            // if (Storage::exists($gallery->file_path)) {
            //     Storage::delete($gallery->file_path);
            // }

            $gallery->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Gallery deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete gallery',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get galleries by post
     */
    public function getByPost($postId)
    {
        try {
            $galleries = Gallery::with('post')
                ->where('post_id', $postId)
                ->orderBy('id', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Galleries by post retrieved successfully',
                'data' => $galleries
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve galleries by post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get galleries without post (standalone images)
     */
    public function getStandalone()
    {
        try {
            $galleries = Gallery::with('post')
                ->whereNull('post_id')
                ->orderBy('id', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Standalone galleries retrieved successfully',
                'data' => $galleries
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve standalone galleries',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search galleries by keyword
     */
    public function search($keyword)
    {
        try {
            $galleries = Gallery::with('post')
                ->where('title', 'LIKE', "%{$keyword}%")
                ->orWhere('caption', 'LIKE', "%{$keyword}%")
                ->orderBy('id', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Galleries search completed',
                'data' => $galleries
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to search galleries',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload file and create gallery entry
     */
    public function upload(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:150',
                'caption' => 'nullable|string',
                'file' => 'required|file|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'post_id' => 'nullable|integer|exists:posts,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('galleries', $fileName, 'public');

                $gallery = new Gallery();
                $gallery->title = $request->title;
                $gallery->caption = $request->caption;
                $gallery->file_path = $filePath;
                $gallery->post_id = $request->post_id;
                $gallery->save();

                // Load the post relationship
                $gallery->load('post');

                return response()->json([
                    'status' => 'success',
                    'message' => 'Gallery uploaded successfully',
                    'data' => $gallery
                ], 201);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'No file uploaded'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to upload gallery',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
