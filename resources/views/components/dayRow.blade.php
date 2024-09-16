@props(['puzzle'])

<span>
    <a href="https://adventofcode.com/{{$puzzle->year}}/day/{{$puzzle->day}}" target="_blank">{{ "$puzzle->day" }}</a>
</span>

<span>
    <a href="/puzzles/{{$puzzle->id}}/editInput">{{ empty($puzzle->input) ? '❌' : '✔' }}</a>
</span>

<span>
    <a href="/puzzles/{{$puzzle->id}}">{{ empty($puzzle->part1) ? '🧮' : (empty($puzzle->part2) ? '⭐' : '⭐⭐') }}</a>
</span>

<span>
    {{ $puzzle->part1 ?? '🤯' }}
</span>

<span>
    {{ $puzzle->part2 ?? '🤯' }}
</span>