<!DOCTYPE html>
<html>
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
    <title>State Details</title>
    @vite(['resources/js/state.js'])
    @vite(['resources/css/statetable.css'])
</head>
<body style="background-color: aliceblue;">
<div id="app">
    <state-table :initial-states="{{ json_encode($states) }}"></state-table>
</div>
</body>
</html>