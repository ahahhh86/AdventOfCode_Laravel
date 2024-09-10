@props(['year' => '', 'day' => ''])

<x-layout>
    <form method="POST" action="/puzzles">
        <label for="year">Year</label><input type="number" id="year" name="year" value="{{ $year }}">
        <label for="day">Day</label><input type="number" id="day" name="day" value="{{ $day }}">
        <label for="input">Input</label><textarea id="input" name="input"></textarea>

        @csrf
        <input type="submit">
    </form>
</x-layout>