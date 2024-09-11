@props(['puzzle'])

<span>{{ "$puzzle->day" }}</span>
<span><a href="https://adventofcode.com/{{$puzzle->year}}/day/{{$puzzle->day}}" target="_blank">🎅</a></span>
<span><a href="/puzzles/{{$puzzle->id}}">🧮</a></span>
<span>{{ $puzzle->part1 ?? "TODO" }}</span>
<span>{{ $puzzle->part2 ?? "TODO" }}</span>