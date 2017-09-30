<?php

namespace App\Http\Controllers;

use App\Feed;
use App\Jobs\ExtractPackageFile;
use App\Package;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index()
    {
        $feeds = Feed::orderBy('created_at', 'DESC')->get();

        return view('feed.index', compact('feeds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file.*' => 'required|file',
        ]);

        $feed = Feed::create([
            'status' => 'in_queue',
        ]);

        foreach ($request->file('file', []) as $uploadedFile) {
            if ($uploadedFile->isValid()) {
                $path = $uploadedFile->store('packages');
                $originalName = $uploadedFile->getClientOriginalName();
                $takenAt = Carbon::createFromTimestamp(substr($originalName, 0, -13));
                $package = Package::create([
                    'original_filename' => $originalName,
                    'taken_at'          => $takenAt->toDateTimeString(),
                    'feed_id'           => $feed->id,
                ]);

                ExtractPackageFile::dispatch($package,$path);
            }
        }

        return redirect()->back();
    }
}
