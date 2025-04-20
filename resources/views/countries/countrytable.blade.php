<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Country Details</title>
    @vite(['resources/js/country.js'])
    @vite(['resources/css/countrytable.css'])
</head>
<body style="background-color: aliceblue;">
    <div id="app">
        <country-table :initial-countries="{{ $countries->toJson() }}"></country-table>
    </div>
</body>
</html>
