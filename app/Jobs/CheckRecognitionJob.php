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

			if (isset($data['response']['chunks'])) {
				$text = Speechkit::getRecognitedText($data['response']['chunks']);
				$this->rec->update(['text' => $text, 'status' => Recognition::STATUS_PROCESSED]);
			} elseif (isset($data['error'])) {
			    $code = $data['error']['code'] ?? '';
			    $error = $data['error']['message'] ?? '';

			    $message = "$error, код $code";

				$this->rec->update([
					'status' => Recognition::STATUS_ERROR,
					'text'   => $message,
				]);
			}

			$this->rec->removeFile();
		}
	}

	public function failed($exception)
	{
		$this->rec->update([
			'status' => Recognition::STATUS_ERROR,
			'text'   => $exception->getMessage(),
		]);

		$this->rec->removeFile();
	}
}
