<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mano produktai</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        h2 { color: #00ffff; }
    </style>
</head>
<body>
    <h2>Vartotojas: {{ $user->name }}</h2>
    <h3>Nusipirkti produktai:</h3>
    <ul>
        @foreach($purchasedProducts as $product)
            <li>{{ $product->name }} – {{ $product->price }} €</li>
        @endforeach
    </ul>
</body>
</html>