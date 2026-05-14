<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Services\LegacyFileService;
use Illuminate\Support\Facades\Log;

class AnnouncementController extends Controller
{
    protected $legacyFileService;

    public function __construct(LegacyFileService $legacyFileService)
    {
        $this->legacyFileService = $legacyFileService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ann_title' => 'required|string|max:255',
            'ann_category' => 'required|string|max:50',
            'ann_date' => 'required|date',
            'ann_article' => 'required|string',
            'ann_media' => 'nullable|image|max:5120',
        ]);

        try {
            $data = [
                'ann_title' => $validated['ann_title'],
                'ann_category' => $validated['ann_category'],
                'ann_date' => $validated['ann_date'],
                'ann_article' => $validated['ann_article'],
                'ann_media' => '',
            ];

            if ($request->hasFile('ann_media')) {
                $imagePath = $this->legacyFileService->uploadImage(
                    $request->file('ann_media'),
                    'Announcement'
                );
                if ($imagePath) {
                    $data['ann_media'] = $imagePath;
                }
            }

            Announcement::create($data);

            return redirect()->back()->with('success', 'Announcement created successfully!');
        } catch (\Exception $e) {
            Log::error('Announcement creation error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create announcement: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $announcement = Announcement::findOrFail($id);
            return response()->json($announcement);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'ann_title' => 'required|string|max:255',
            'ann_category' => 'required|string|max:50',
            'ann_date' => 'required|date',
            'ann_article' => 'required|string',
            'ann_media' => 'nullable|image|max:5120',
        ]);

        try {
            $announcement = Announcement::findOrFail($id);

            $data = [
                'ann_title' => $validated['ann_title'],
                'ann_category' => $validated['ann_category'],
                'ann_date' => $validated['ann_date'],
                'ann_article' => $validated['ann_article'],
            ];

            if ($request->hasFile('ann_media')) {
                $imagePath = $this->legacyFileService->uploadImage(
                    $request->file('ann_media'),
                    'Announcement'
                );
                if ($imagePath) {
                    $data['ann_media'] = $imagePath;
                }
            }

            $announcement->update($data);

            return redirect()->back()->with('success', 'Announcement updated successfully!');
        } catch (\Exception $e) {
            Log::error('Announcement update error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update announcement: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $announcement = Announcement::findOrFail($id);
            $announcement->delete();
            return redirect()->back()->with('success', 'Announcement deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Announcement deletion error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete announcement.');
        }
    }
}
