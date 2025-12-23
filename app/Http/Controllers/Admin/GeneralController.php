<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\System\Service;
use App\Models\Gallery;
use App\Models\Video;
use App\Models\WebTraffic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class GeneralController extends Controller
{

    public function __construct()
    {
        $this->middleware('gen-auth');
        $this->middleware('admin-auth');
    }
    
    public function dashboard()
    {
        return Inertia::render('Admin/Pages/Dashboard');
    }

    public function statistics()
    {
        try {
            // Basic statistics - customize based on your retained models
            $totalServices = Service::where('status', 2)->count();
            
            // For website visits, use a simple counter or implement proper analytics
            $websiteVisits = rand(1000, 5000); // Placeholder - replace with actual analytics
            
            // Get recent activity - customize based on your needs
            $recentActivity = [];
            
            return response()->json([
                'totalServices' => $totalServices,
                'websiteVisits' => $websiteVisits,
                'recentActivity' => $recentActivity
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load statistics',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function whatsappConfig()
    {
        return response()->json([
            'phone' => config('app.whatsapp.phone'),
            'defaultMessage' => config('app.whatsapp.default_message'),
            'enabled' => config('app.whatsapp.enabled')
        ]);
    }

    public function webTraffic(Request $request)
    {
        try {
            $range = $request->query('range', 'month');
            
            // Get traffic data from database
            $trafficRecords = WebTraffic::forRange($range)->get();
            
            // If no data exists, return empty array (or generate sample data for demo)
            if ($trafficRecords->isEmpty()) {
                // Generate sample data for demonstration
                $days = $range === 'week' ? 7 : ($range === 'month' ? 30 : 365);
                $data = [];
                
                for ($i = $days - 1; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $data[] = [
                        'date' => $date->format('M d'),
                        'visitors' => rand(100, 500),
                        'page_views' => rand(200, 1000),
                    ];
                }
            } else {
                // Format database records
                $data = $trafficRecords->map(function ($record) {
                    return [
                        'date' => $record->date->format('M d'),
                        'visitors' => $record->visitors,
                        'page_views' => $record->page_views,
                    ];
                })->toArray();
            }
            
            return response()->json([
                'data' => $data,
                'range' => $range
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load web traffic data',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function mediaGallery()
    {
        try {
            // Get recent galleries (published)
            $galleries = Gallery::where('status', 2)
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get(['id', 'uuid', 'title', 'description', 'cover_image', 'created_at']);
            
            // Get recent videos (published)
            $videos = Video::where('status', 2)
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get(['id', 'uuid', 'title', 'description', 'video_url', 'thumbnail_url', 'cover_image', 'created_at']);
            
            return response()->json([
                'galleries' => $galleries,
                'videos' => $videos
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load media gallery',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
