<x-layout>
    <h2>{{ "$puzzle->year day $puzzle->day" }}</h2>

    <?php
        $input = $puzzle->input;
        require __DIR__."/../../../resources/php/year{$puzzle->year}/day{$puzzle->day}.php"
    ?> {{-- TODO: find better way--}}

    <p>
        Tests:
        <ol>
            @foreach($tests as $test)
                <x-showResult :result="$test['result']" :expected="$test['expected']"></x-showResult>
            @endforeach
        </ol>
    </p>

    <p>
        Results:
        <ol>
            @foreach($parts as $part)
                <x-showResult :result="$part['result']" :expected="$part['expected']"></x-showResult>
            @endforeach
        </ol>
    </p>

    <form method="POST" action="/puzzles/{{$puzzle->id}}">
        <input type="text" value="{{ $parts[0]['result'] }}">
        <input type="text" value="{{ $parts[1]['result'] }}">

        @csrf
        @method('PATCH')
        <input type="submit">
    </form>
</x-layout>