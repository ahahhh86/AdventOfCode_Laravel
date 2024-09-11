<x-layout>
    <h1>Advent of code</h1>
    <h2>2021</h2>

    <x-dayTable>
        @foreach($puzzles as $puzzle)
            <x-dayRow :puzzle="$puzzle"></x-day>
        @endforeach
    </x-dayTable>
</x-layout>

