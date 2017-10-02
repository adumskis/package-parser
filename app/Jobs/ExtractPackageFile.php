<?php

namespace App\Jobs;

use App\Feed;
use App\Package;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

class ExtractPackageFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $package;
    protected $packagePath;

    /**
     * Create a new job instance.
     * @param Package $package
     */
    public function __construct(Package $package, $packagePath)
    {
        $this->package = $package;
        $this->packagePath = $packagePath;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            $feed = $this->package->feed;
            $feed->status = Feed::EXTRACTING;
            $feed->save();
            $packageFile = Storage::path($this->packagePath);
            $destinationPath = storage_path('app/feeds_xmls/' . uniqid() . '.xml');
            $gz = gzopen($packageFile, 'rb');
            $destinationFile = fopen($destinationPath, 'wb');
            while (!gzeof($gz)) {
                fwrite($destinationFile, gzread($gz, 4096));
            }

            gzclose($gz);
            fclose($destinationFile);

            // dispatch job to parse extracted XML file and delete gz file
            ParsePackageXml::dispatch($this->package, $destinationPath);
            unlink($packageFile);
        } catch (\Exception $e) {
            $this->package->feed->update([
                'status' => Feed::ERROR
            ]);

            return;
        }
    }
}
