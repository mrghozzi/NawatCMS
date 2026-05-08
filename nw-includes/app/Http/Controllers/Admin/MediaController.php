<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function index(): View
    {
        $media = Media::latest()->get();
        return view('admin::media.index', compact('media'));
    }

    public function store(Request $request, MediaService $service): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'max:5120', 'mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,txt'], // 5MB max
            'alt_text' => ['nullable', 'string', 'max:255'],
        ]);

        $service->upload($request->file('file'), $request->input('alt_text'));

        return redirect()->back()->with('success', 'File uploaded successfully.');
    }

    public function destroy(Media $media, MediaService $service): RedirectResponse
    {
        $service->delete($media);

        return redirect()->back()->with('success', 'File deleted successfully.');
    }
}
