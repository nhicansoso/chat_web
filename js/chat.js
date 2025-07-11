const messageContainer = document.querySelector('#messageContainer');
const form = document.querySelector('#chatForm');
const input = document.querySelector('#messageInput');
const incomingId = document.querySelector('#incoming_id').value;

form.addEventListener('submit', function (e) {
    e.preventDefault();

    const msg = input.value.trim();
    if (msg === "") return;

    fetch("php/send_messages.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `message=${encodeURIComponent(msg)}&incoming_id=${encodeURIComponent(incomingId)}`
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                input.value = "";
                loadMessages();
                setTimeout(() => {
                    messageContainer.scrollTop = messageContainer.scrollHeight;
                }, 100);
            }
        });
});

function loadMessages(scrollToBottom = false) {
    fetch("php/load_messages.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `incoming_id=${encodeURIComponent(incomingId)}`
    })
        .then(res => res.text())
        .then(data => {
            messageContainer.innerHTML = data;
            if (scrollToBottom) {
                messageContainer.scrollTop = messageContainer.scrollHeight;
            }
        });
}

loadMessages(true);
setInterval(loadMessages, 2000);
