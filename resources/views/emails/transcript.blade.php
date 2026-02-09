<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .chat-log {
            background-color: #fff;
            border: 1px solid #e9ecef;
            border-top: none;
            padding: 20px;
            border-radius: 0 0 8px 8px;
        }

        .message {
            margin-bottom: 15px;
        }

        .message.user {
            text-align: right;
        }

        .message.assistant {
            text-align: left;
        }

        .bubble {
            display: inline-block;
            padding: 10px 15px;
            border-radius: 15px;
            max-width: 80%;
        }

        .user .bubble {
            background-color: #007bff;
            color: white;
            border-bottom-right-radius: 4px;
        }

        .assistant .bubble {
            background-color: #f1f3f5;
            color: #333;
            border-bottom-left-radius: 4px;
        }

        .meta {
            font-size: 12px;
            color: #888;
            margin-top: 4px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Chat Transcript</h2>
            <p>{{ $chatbot->name }}</p>
        </div>

        <div class="chat-log">
            @foreach($messages as $msg)
                <div class="message {{ $msg['role'] }}">
                    <div class="bubble">
                        @if(is_array($msg['content']))
                            @foreach($msg['content'] as $content)
                                @if($content['type'] === 'text')
                                    <p>{{ $content['text'] }}</p>
                                @elseif($content['type'] === 'image_url')
                                    <img src="{{ $content['image_url']['url'] }}" alt="Uploaded Image"
                                        style="max-width: 100%; border-radius: 8px; margin-top: 5px;">
                                @endif
                            @endforeach
                        @else
                            {!! nl2br(e($msg['content'])) !!}
                        @endif
                    </div>
                    <div class="meta">
                        {{ $msg['role'] === 'user' ? 'You' : $chatbot->name }} •
                        {{ $msg['time'] }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="footer">
            <p>This transcript was sent from the {{ $chatbot->name }} widget.</p>
        </div>
    </div>
</body>

</html>