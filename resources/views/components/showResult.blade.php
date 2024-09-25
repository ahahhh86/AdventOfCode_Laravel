@props(['result', 'expected', 'data'])

<?php
    $passed = $data['result'] === $data['expected'];
?>

<li style="color: {{ $passed ? "lime" : "red" }}">{{ $data['result'] }} ({{ $data['expected'] }} expected {{ $passed ? '👍' : '☠' }})</li>