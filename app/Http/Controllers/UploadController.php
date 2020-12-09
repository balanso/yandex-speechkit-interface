<?php

namespace App\Http\Controllers;

use App\Classes\Speechkit;
use App\Http\Requests\uploadAudioRequest;
use App\Jobs\SendToRecognitionJob;
use App\Jobs\UploadToCloudJob;
use App\Models\Recognition;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{

	public function uploadAudio(uploadAudioRequest $request)
	{
		$file = $request->file('file');
		$storedName = $file->store('', 'uploads');

		$rec = Recognition::create([
			'name'        => $file->getClientOriginalName(),
			'stored_name' => $storedName,
			'status'      => Recognition::STATUS_UPLOADING
		]);

		Bus::chain([
			new UploadToCloudJob($rec),
			new SendToRecognitionJob($rec),
		])->dispatch();

		return response()->json(['status' => 'success', 'message' => 'ok']);
	}
}
