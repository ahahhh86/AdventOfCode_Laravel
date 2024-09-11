@props(['result', 'expected'])

<?php
    $passed = $result === $expected;
?>

<li style="color: {{ $passed ? "lime" : "red" }}">{{ $passed ? '👍' : '☠' }} {{ $result }} | {{ $expected }}</li>