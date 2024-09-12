<x-layout>
    {{-- <h2>{{ "$puzzle->year day $puzzle->day" }}</h2> --}}
    <x-dayHeader :puzzle="$puzzle"></x-dayHeader>

    <?php
        $includeFile = __DIR__."/../../../resources/php/year{$puzzle->year}/day{$puzzle->day}.php";
        if (file_exists($includeFile)) {
            include $includeFile;
        }
    ?> {{-- TODO: find better way--}}

    @if(empty($input))
        <p>
            This puzzle has not (yet) been solved
        </p>
    @endif

    @if(!empty($tests))
        <p>
            Tests:
            <ol>
                @foreach($tests as $test)
                    <x-showResult :result="$test['result']" :expected="$test['expected']"></x-showResult>
                @endforeach
            </ol>
        </p>
    @endif

    @if(!empty($parts))
        <p>
            Results:
            <ol>
                @foreach($parts as $part)
                    <x-showResult :result="$part['result']" :expected="$part['expected']"></x-showResult>
                @endforeach
            </ol>
        </p>

        <form method="POST" action="/puzzles/{{$puzzle->id}}">
            <input type="hidden" name="part1" value="{{ $parts[0]['result'] ?? null }}">
            <input type="hidden" name="part2" value="{{ $parts[1]['result'] ?? null }}">

            @csrf
            @method('PATCH')
            <input type="submit">
        </form>
    @endif
</x-layout>