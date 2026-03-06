<!DOCTYPE html>
<html>
<head>
    <title>Contact Form</title>
</head>
<body>

<h2>Contact Form</h2>

<form method="POST" action="/contact-submit">

    {{-- CSRF TOKEN --}}
    <p>CSRF Token: {{ csrf_token() }}</p>
    @csrf

    <label>Name:</label>
    <input type="text" name="name"><br><br>

    <label>Email:</label>
    <input type="email" name="email"><br><br>

    <label>Message:</label>
    <textarea name="message"></textarea><br><br>

    <button type="submit">Send</button>

</form>

</body>
</html>