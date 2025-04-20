<!DOCTYPE html>
<html>
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
    <title>City Details</title>
    @vite(['resources/js/city.js'])
    @vite(['resources/css/citytable.css'])
</head>
<body style="background-color: aliceblue;">
    <div id="app">
        <city-table :initial-cities="{{ $cities->toJson() }}"></city-table>
    </div>
</body>
</html>
