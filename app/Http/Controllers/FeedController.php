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
        $feeds = Feed::orderBy('created_at', 'DESC')->paginate();

        return view('feed.index', compact('feeds'));
    }

    public function show(Feed $feed)
    {
        return view('feed.show', compact('feed'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file'   => 'required',
            'file.*' => 'required|mimetypes:application/x-gzip',
        ]);

        $feed = Feed::create([
            'status' => Feed::IN_QUEUE,
        ]);

        foreach ($request->file('file', []) as $uploadedFile) {
            if ($uploadedFile->isValid()) {
                $path = $uploadedFile->store('packages');
                $originalName = $uploadedFile->getClientOriginalName();
                $package = Package::create([
                    'original_filename' => $originalName,
                    'taken_at'          => null,
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

        if ($request->has('total')) {
            $datasets[] = [
                'label' => 'Total',
                'data'  => $feed->packages->pluck('etot_kwh'),
            ];
        } else {
            foreach ($feed->units as $unit) {
                $datasets[] = [
                    'label' => $unit->uid,
                    'data'  => $feed->logs()
                        ->where('unit_id', $unit->id)
                        ->orderBy('taken_at')
                        ->pluck('logs.etot_kwh'),
                ];
            }
        }

        return response()->json([
            'datasets' => $datasets,
            'labels'   => $labels,
        ]);
    }
}
