<x-layout>
    <x-dayHeader :puzzle="$puzzle"></x-dayHeader>

    @php($day = new App\Models\Puzzles\Day($puzzle))

    @if(!$day->isSolved())
        <p>
            This puzzle has not (yet) been solved
        </p>
    @endif

    @if($day->hasTests())
        <p>
            Tests:
            <ol>
                @foreach($day->getTests() as $test)
                    <x-showResult :data="$test"></x-showResult>
                @endforeach
            </ol>
        </p>
    @endif

    @if($day->hasResults())
        @php($results = $day->getResults())
        <p>
            Results:
            <ol>
                @foreach($results as $result)
                    <x-showResult :data="$result"></x-showResult>
                @endforeach
            </ol>
        </p>

        <form method="POST" action="/puzzles/{{$puzzle->id}}">
            <input type="hidden" name="part1" value="{{ $results[0]['result'] ?? null }}">
            <input type="hidden" name="part2" value="{{ $results[1]['result'] ?? null }}">

            <a href="/" style="text-align: center">back</a>
            @csrf
            @method('PATCH')
            <input type="submit">
        </form>
    @endif
</x-layout>