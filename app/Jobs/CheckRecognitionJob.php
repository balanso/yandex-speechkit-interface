<?php

namespace App\Jobs;

use App\Classes\Speechkit;
use App\Models\Recognition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\File;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CheckRecognitionJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $rec;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($rec)
	{
		//
		$this->rec = $rec;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function handle()
	{
		$data = Speechkit::getData($this->rec);

		if ($data['done'] === true) {
			$text = Speechkit::getRecognitedText($data['response']['chunks']);
			$this->rec->update(['text'=>$text, 'status'=>Recognition::STATUS_PROCESSED]);
			$this->rec->removeFile();
		}
	}
}
