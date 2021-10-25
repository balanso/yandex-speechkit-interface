<?php


namespace App\Classes;


use App\Models\Recognition;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Speechkit
{
	public static function tryRecognite(Recognition $rec)
	{
		$fileUrl = 'https://storage.yandexcloud.net/' . env('YANDEX_BUCKET_NAME') . '/' .
				   $rec->stored_name;
		$data = [
			'config' => [
				'specification' => [
					'languageCode'    => 'ru-RU',
					'model'           => 'general',
					'profanityFilter' => false,
					'audioEncoding'   => 'OGG_OPUS',
					/*
					'audioEncoding' => 'LINEAR16_PCM',
					'sampleRateHertz' => '48000, 16000, 8000,
					'audioChannelCount' => '1'
					*/
					'rawResults'      => true,
				]
			],
			'audio'  => [
				'uri' => $fileUrl
			]
		];

		$uuid = uniqid();
		$response = Http::withHeaders([
			'Authorization' => 'Api-Key ' . env('YANDEX_API_KEY'),
            'x-client-request-id' => $uuid,
            'x-data-logging-enabled' => true,
		])->post('https://transcribe.api.cloud.yandex.net/speech/stt/v2/longRunningRecognize',
			$data);

		$body = $response->json();

		if ($response->ok()) {
			$rec->update([
				'status'       => Recognition::STATUS_PROCESSING,
				'operation_id' => $body['id']
			]);

			return $body;
		} else {
			$message = $body['message'] ?: 'Message is not received';

			throw new \Exception($message);
		}
	}


	public static function getData(Recognition $rec)
	{
		$url = "https://operation.api.cloud.yandex.net/operations/{$rec->operation_id}";
		if ($rec->operation_id) {
			$response = Http::withHeaders([
				'Authorization' => 'Api-Key ' . env('YANDEX_API_KEY'),
			])->get($url);
		} else {
			throw new \Exception('Operation id not received on recognition');
		}

		$body = $response->json();

		if ($response->ok()) {
			return $body;
		}

		$message = $body['message'] ?: 'Message not recieved';
		throw new \Exception($message);
	}

	public static function getRecognitedText($chunks)
	{
		$text = '';

		foreach ($chunks as $chunk) {
			$text .= $chunk['alternatives'][0]['text'] . "\n";
		}

		return $text;
	}
}
