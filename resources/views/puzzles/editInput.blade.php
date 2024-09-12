<x-layout>
    <x-dayHeader :puzzle="$puzzle"></x-dayHeader>

    <p>
        Set the puzzle input:
    </p>

    <form method="POST" action="/puzzles/{{$puzzle->id}}/editInput">
        <label for="input">Input</label><textarea id="input" name="input">{{$puzzle->input}}</textarea>

        @csrf
        @method("PATCH")
        <input type="submit">
    </form>
</x-layout>