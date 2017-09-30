<?php

namespace App\Jobs;

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
        $feed->status = 'parsing';
        $feed->save();

        $xmlObject = new \XMLReader();
        $xmlObject->open($this->xmlPath);

        while ($xmlObject->read()) {
            if ($xmlObject->nodeType == \XMLReader::ELEMENT) {
                switch ($xmlObject->name) {
                    case ('tot'):
                        $this->package->total = ['Etot_kWh' => $xmlObject->getAttribute('Etot_kWh')];
                        break;
                    case ('inv'):
                        Unit::create([
                            'package_id' => $this->package->id,
                            'unit_id'    => $xmlObject->getAttribute('InvID'),
                            'data'       => [
                                'Etot_kWh' => $xmlObject->getAttribute('Etot_kWh'),
                            ],
                        ]);
                }
            }
        }
        $xmlObject->close();

        $this->package->is_parsed = 1;
        $this->package->save();

        if($feed->packages()->where('is_parsed', 0)->count() == 0){
            $feed->status = 'done';
            $feed->save();
        }
    }
}
