<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@hasSection("title")@yield("title") - @endif{{ config('app.name') }}</title>

    <link rel="shortcut icon" href="/images/favicon.png">

    <link rel="apple-touch-icon" href="/icons/east_sigma_appicon.png">
    <link rel="icon" href="/icons/east_sigma_appicon.png">
</head>
