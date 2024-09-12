@props(['result', 'expected'])

<?php
    $passed = $result === $expected;
?>

<li style="color: {{ $passed ? "lime" : "red" }}">{{ $result }} ({{ $expected }} expected {{ $passed ? '👍' : '☠' }})</li>