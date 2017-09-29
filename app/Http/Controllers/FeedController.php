<?php

namespace App\Http\Controllers;

use App\Feed;
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
            'file' => 'required|file'
        ]);

        if ($request->file('file')->isValid()) {
            $newFileName = Carbon::now()->timestamp . '_' . $request->file->getClientOriginalName();
            $request->file->storeAs('public', 'uploads/feed' . $newFileName);

            Feed::create([
                'filename' => $newFileName,
                'status' => 'in_queue'
            ]);
        }

        return redirect()->back();
    }
}
