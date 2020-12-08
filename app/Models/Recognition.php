<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Recognition extends Model
{
	use HasFactory;

	const STATUS_UPLOADING = 1;
	const STATUS_UPLOADED = 2;
	const STATUS_PROCESSING = 3;
	const STATUS_PROCESSED = 4;

	protected $fillable = [
		'name',
		'stored_name',
		'status',
		'text',
		'operation_id',
	];

	public function getStatus() {
		switch ($this->status) {
			case self::STATUS_UPLOADING:
				return 'Загрузка файла';
			case self::STATUS_UPLOADED:
				return 'Файл загружен и ожидает обработку';
			case self::STATUS_PROCESSING:
				return 'Файл обрабатывается';
			case self::STATUS_PROCESSED:
				return 'Обработка завершена';
			default:
				return 'Неизвестен';
		}
	}

	public function removeFile()
	{
		Storage::disk('uploads')->delete($this->stored_name);
		Storage::disk('yandex')->delete($this->stored_name);
	}
}
