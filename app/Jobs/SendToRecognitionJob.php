<?php

namespace App\Jobs;

use App\Classes\Speechkit;
use App\Models\Recognition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendToRecognitionJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $rec;

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
	 * @throws \Exception
	 */
	public function handle()
	{
		Speechkit::tryRecognite($this->rec);
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
