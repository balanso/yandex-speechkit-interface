<?php

namespace App\Console\Commands;

use App\Jobs\CheckRecognitionJob;
use App\Models\Recognition;
use Illuminate\Console\Command;

class CheckRecognitionsCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'recognitions:check';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Проверяет завешилась ли обработка аудио и сохраняет текст';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle()
	{
		$items = Recognition::where('status', Recognition::STATUS_PROCESSING);

		$items->each(function ($item) {
			CheckRecognitionJob::dispatch($item);
		});
	}
}
