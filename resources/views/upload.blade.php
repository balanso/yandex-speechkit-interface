<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{env('APP_NAME')}}</title>

    <script src="/dropzone/dropzone.min.js"></script>
    <link rel="stylesheet" href="/dropzone/basic.min.css">
    <link rel="stylesheet" href="/dropzone/dropzone.min.css">
</head>
<body class="antialiased">

<form action="/upload"
      class="dropzone"
      id="audio-uploader">
    <div class="dz-message" data-dz-message><h2>Загрузка файлов</h2>Перетащите файлы в эту область или кликните
        для выбора из списка</div>
    @csrf
</form>

@if($recognitions->count())
    <h2>Последние файлы</h2>
    @foreach($recognitions as $rec)
        <hr>
        {{$rec->name}}<br>
        {{$rec->getStatus()}}<br>
        Дата загрузки: {{$rec->created_at}}<br>
        Дата обновления: {{$rec->updated_at}}

        @if (!empty($rec->text))
        <br>
        <a href="{{route('download-text', $rec->id)}}">Скачать</a>
        @endif
    @endforeach
    <hr>
    <a href="{{route('clean-history')}}">Очистить историю завершённых обработок</a>

@endif
<script src="/dropzone/init.js?v=2"></script>
</body>
</html>
