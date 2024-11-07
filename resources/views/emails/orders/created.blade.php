<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Your Order Has Been Created</title>
</head>
<body>
<h1>Thank you for your order!</h1>

<p>Your order has been successfully created. Here are the details:</p>

<ul>
    <li><strong>Order Number:</strong> {{ $order->order_number }}</li>
    <li><strong>Amount:</strong> {{ number_format($order->amount, 2) }}</li>
    <li><strong>Quantity:</strong> {{ $order->quantity }}</li>
    <li><strong>Product:</strong> {{ $order->product->name }}</li>
</ul>

<p>We will process your order shortly. Thank you for choosing our service!</p>
</body>
</html>
