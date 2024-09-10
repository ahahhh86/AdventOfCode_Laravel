@props(['day' => '', 'year' => ''])

<span style="grid-column: 1; text-align: right;">{{ "$day" }}</span>
<span style="grid-column: 2; text-align: center;"><a href="https://adventofcode.com/{{$year}}/day/{{$day}}" target="_blank">🎅</a></span>
<span style="grid-column: 3; text-align: center;"><a href="\puzzles\create\{{ $year }}\{{ $day }}">🎁</a></span>
<span style="grid-column: 4; text-align: center"><a href="\puzzles\calc\{{ $year }}\{{ $day }}">🧮</a></span>
<span style="grid-column: 5; text-align: right;">solution part 1</span>
<span style="grid-column: 6; text-align: right;">solution part 2</span>