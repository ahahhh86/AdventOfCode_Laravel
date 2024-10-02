@props(['data'])

@php(extract($data)) {{-- Create $result and $expected --}}
@php($passed = $result === $expected)

<li style="color: {{ $passed ? "lime" : "red" }}">{{ $result }} ({{ $expected }} expected {{ $passed ? '👍' : '☠' }})</li>