<?php

namespace App\Http\Controllers;

use App\Feed;
use App\Jobs\ExtractPackageFile;
use App\Log;
use App\Package;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedController extends Controller
{
    public function index()
    {
        $feeds = Feed::orderBy('created_at', 'DESC')->get();

        return view('feed.index', compact('feeds'));
    }

    public function show(Feed $feed)
    {
        return view('feed.show', compact('feed'));
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

                ExtractPackageFile::dispatch($package, $path);
            }
        }

        return redirect()->back();
    }

    public function chartData(Request $request)
    {
        $feed = Feed::findOrFail($request->get('feed_id'));

        $labels = $feed->packages->pluck('formatted_taken_at');
        $datasets = [];
        foreach ($feed->units as $unit) {
            $datasets[] = [
                'label' => $unit->uid,
                'data'  => $feed->logs()
                    ->where('unit_id', $unit->id)
                    ->pluck('logs.etot_kwh'),
            ];
        }

        return response()->json([
            'datasets' => $datasets,
            'labels' => $labels
        ]);
    }
}
