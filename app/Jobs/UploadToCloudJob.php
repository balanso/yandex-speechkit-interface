<?php

namespace App\Jobs;

use App\Models\Recognition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\File;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class UploadToCloudJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public $rec;

	/**
	 * Create a new job instance.
	 *
	 * @param Recognition $rec
	 */
	public function __construct(Recognition $rec)
	{
		$this->rec = $rec;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$fileName = $this->rec->stored_name;
		$filePath = Storage::disk('uploads')->path($fileName);
		$file = new File($filePath);
		Storage::disk('yandex')->putFileAs('', $file, $fileName);
		$this->rec->update(['status' => Recognition::STATUS_UPLOADED]);
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
