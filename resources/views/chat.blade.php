<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://js.pusher.com/8.0/pusher.min.js"></script>
</head>
<body>

<div id="chat-box" style="max-width:500px;margin:auto;">
    <div id="messages" style="border:1px solid #ccc;padding:10px;height:300px;overflow-y:scroll;">
        @foreach($messages as $message)
            <p><strong>{{ $message->sender->name }}:</strong> {{ $message->content }}</p>
        @endforeach
    </div>

    <input type="text" id="message" placeholder="Type a message..." style="width:80%;">
    <button onclick="sendMessage()">Send</button>
</div>

<script>
    const userId = {{ auth()->id() }};
    const receiverId = {{ $userId }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Pusher setup
    Pusher.logToConsole = true;
    const pusher = new Pusher('bc7ae6b4ecf59791199d', {
        cluster: 'mt1',
        forceTLS: true
    });

    const channel = pusher.subscribe('chat.' + userId);
    channel.bind('MessageSent', function(data) {
        const msgBox = document.getElementById('messages');
        msgBox.innerHTML += `<p><strong>${data.message.sender.name}:</strong> ${data.message.content}</p>`;
        msgBox.scrollTop = msgBox.scrollHeight;
    });

    function sendMessage() {
        const content = document.getElementById('message').value;
        if (!content) return;

        axios.post('/chat/send', {
            receiver_id: receiverId,
            content: content
        }, {
            headers: { 'X-CSRF-TOKEN': csrfToken }
        }).then(response => {
            const msgBox = document.getElementById('messages');
            msgBox.innerHTML += `<p><strong>You:</strong> ${response.data.content}</p>`;
            msgBox.scrollTop = msgBox.scrollHeight;
            document.getElementById('message').value = '';
        });
    }
</script>

</body>
</html>
