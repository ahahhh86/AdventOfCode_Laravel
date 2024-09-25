<x-layout>
    <form method="POST" action="/puzzles">
        <label for="year">Year</label><input type="number" id="year" name="year">
        <label for="day">Day</label><input type="number" id="day" name="day">

        @csrf
        <input type="submit">
    </form>
</x-layout>