<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: sans-serif;
        }

        .container {
            padding: 20px;
        }

        .button {
            background: #4f46e5;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>User Requesting Support</h2>
        <p>A visitor on <strong>{{ $conversation->chatbot->name }}</strong> has requested to speak with a human agent.
        </p>

        <p><strong>Visitor ID:</strong> {{ $conversation->visitor_id }}</p>
        <p><strong>URL:</strong> {{ $conversation->detected_url }}</p>
        <p><strong>Last Message:</strong> {{ $conversation->messages->last()->content ?? 'N/A' }}</p>

        <br>
        <a href="{{ route('live-chat') }}" class="button">Go to Live Chat</a>
    </div>
</body>

</html>