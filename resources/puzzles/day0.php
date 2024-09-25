<?php
        $includeFile = __DIR__."/year{$puzzle->year}/day{$puzzle->day}.php";
        if (file_exists($includeFile)) {
            $tests = [];
            $parts = [];

            require_once $includeFile;
        }