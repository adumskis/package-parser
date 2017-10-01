<?php

namespace App\Jobs;

use App\Feed;
use App\Log;
use App\Package;
use App\Unit;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ParsePackageXml implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $package;
    protected $xmlPath;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Package $package, $xmlPath)
    {
        $this->package = $package;
        $this->xmlPath = $xmlPath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $feed = $this->package->feed;
        if ($feed->status === Feed::ERROR) {
            return;
        }
        try {
            $feed->update([
                'status' => Feed::PARSING
            ]);

            $xmlObject = new \XMLReader();
            $xmlObject->open($this->xmlPath);

            while ($xmlObject->read()) {
                if ($xmlObject->nodeType == \XMLReader::ELEMENT) {
                    switch ($xmlObject->name) {
                        case ('tot'):
                            $this->package->etot_kwh = $xmlObject->getAttribute('Etot_kWh');
                            break;
                        case ('inv'):
                            $unit = Unit::firstOrCreate([
                                'uid'     => $xmlObject->getAttribute('InvID'),
                                'feed_id' => $feed->id,
                            ]);

                            Log::create([
                                'package_id' => $this->package->id,
                                'unit_id'    => $unit->id,
                                'etot_kwh'   => (float)$xmlObject->getAttribute('Etot_kWh'),
                            ]);
                    }
                }
            }
            $xmlObject->close();

            $this->package->is_parsed = 1;
            $this->package->save();
        } catch (\Exception $e) {
            $feed->update([
                'status' => Feed::ERROR,
            ]);
        }

        if ($feed->packages()->where('is_parsed', 0)->count() == 0) {
            $feed->status = Feed::DONE;
            $feed->save();
        }
    }
}
