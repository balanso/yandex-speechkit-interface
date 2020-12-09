<?php

namespace App\Http\Controllers;

use App\Models\Recognition;
use Illuminate\Support\Facades\Storage;

class RecognitionController extends Controller
{
	//
	public function index()
	{
		$recognitions = Recognition::orderBy('id', 'desc')->limit(20)->get();

		return view('upload', compact('recognitions'));
	}

	public function removeFile(Recognition $rec)
	{
		$rec->removeFile();
	}

	public function cleanHistory()
	{
		$recognitions = Recognition::where('status', '=', Recognition::STATUS_PROCESSED)->get();

		$recognitions->each(function($r) {
			$r->delete();
		});

		return redirect()->route('index');
	}

	public function downloadText(Recognition $rec)
	{
		$headers = array(
			'Content-Type'        => 'plain/txt',
			'Content-Disposition' => sprintf('attachment; filename="%s"', $rec->name . '.txt'),
		);

		return response()->make($rec->text, '200', $headers);
	}
}
