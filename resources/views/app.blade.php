<!DOCTYPE html>
<html>
<head>
    <title>Employee Management System</title>
    @vite(['resources/js/app.js'])
    @vite(['resources/css/detailtable.css'])
    @vite(['resources/css/registerform.css'])
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        // Make PHP variables available to Vue
        window.initialData = {
            userId: @json($user_id ?? null),
            countryId: @json($country_id ?? null),
            stateId: @json($state_id ?? null),
            cityId: @json($city_id ?? null),
            countries: @json($countries ?? []),
            states: @json($states ?? []),
            cities: @json($cities ?? []),
            detail: @json($detail ?? null),
            id: @json($id ?? null)
        };
    </script>
</head>
<body style="background-color: aliceblue;">
    <div id="app">
        <!-- Vue Router View -->
        <router-view></router-view>
    </div>
</body>
</html>