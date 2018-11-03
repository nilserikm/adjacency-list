<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    </head>
    <body>
        <h1>adjacency list</h1>
        <div>
            count: {{ $count }} <br/>
            duration: {{ $duration }} <br/>
            add single: {{ $addDuration }} <br/>
            add count: {{ $addCount }} <br/>
            delete single: {{ $deleteDuration }} <br/>
            delete count: {{ $deleteCount }} <br/>
            delete subtree: {{ $deleteSubtreeDuration }} <br/>
            subtree count: {{ $subtreeCount }} <br/>
            root: {{ $root }} <br/>
            after count: {{ $afterCount }} <br/>
        </div>

        <div>
            {{ $tree }}
        </div>
    </body>
</html>