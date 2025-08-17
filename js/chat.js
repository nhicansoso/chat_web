const messageContainer = document.querySelector('#messageContainer');
const form = document.querySelector('#chatForm');
const input = document.querySelector('#messageInput');
const incomingId = document.querySelector('#incoming_id')?.value;
const imageInput = document.querySelector('#imageInput');
const previewContainer = document.querySelector('#previewContainer');

// Xem trước ảnh
function previewImage(file) {
    previewContainer.innerHTML = '';

    if (!file) {
        previewContainer.style.display = 'none';
        return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
        const wrapper = document.createElement('div');
        wrapper.classList.add('preview-img-wrapper');

        const img = document.createElement('img');
        img.src = e.target.result;
        img.classList.add('preview-img');

        const cancelBtn = document.createElement('button');
        cancelBtn.textContent = '✕';
        cancelBtn.type = 'button';
        cancelBtn.className = 'cancel-img-btn';
        cancelBtn.onclick = () => {
            imageInput.value = '';
            previewContainer.innerHTML = '';
            previewContainer.style.display = 'none';
        };

        wrapper.appendChild(img);
        wrapper.appendChild(cancelBtn);
        previewContainer.appendChild(wrapper);
        previewContainer.style.display = 'flex';
    };
    reader.readAsDataURL(file);
}

if (form && input && incomingId && messageContainer && imageInput && previewContainer) {
    // Khi chọn ảnh
    imageInput.addEventListener('change', () => {
        const file = imageInput.files[0];
        previewImage(file);
    });

    // Gửi form
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const msg = input.value.trim();
        const imageFile = imageInput.files[0];

        if (msg === "" && !imageFile) return;

        const formData = new FormData();
        formData.append('incoming_id', incomingId);
        if (msg !== "") formData.append('message', msg);
        if (imageFile) formData.append('image', imageFile);

        fetch("php/messages/send_messages.php", {
            method: "POST",
            body: formData,
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    form.reset();
                    imageInput.value = '';
                    previewContainer.innerHTML = '';
                    previewContainer.style.display = 'none';
                    loadMessages(true); // Cuộn xuống
                } else {
                    alert(data.error || "Lỗi gửi tin nhắn");
                }
            })
            .catch(err => {
                console.error("Lỗi fetch:", err);
            });
    });

    // Load tin nhắn
    function loadMessages(scrollToBottom = false) {
        if (!incomingId) return;

        fetch(`php/messages/load_messages.php?t=${Date.now()}`, {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `incoming_id=${encodeURIComponent(incomingId)}`
        })
            .then(res => res.text())
            .then(data => {
                const prevScroll = messageContainer.scrollTop;
                const atBottom = messageContainer.scrollHeight - prevScroll - messageContainer.clientHeight < 100;

                messageContainer.innerHTML = data;

                const images = messageContainer.querySelectorAll("img");

                if (images.length === 0) {
                    // Không có ảnh => cuộn luôn
                    if (scrollToBottom || atBottom) {
                        messageContainer.scrollTop = messageContainer.scrollHeight;
                    }
                } else {
                    // Có ảnh => chờ ảnh load xong
                    let loaded = 0;
                    images.forEach(img => {
                        img.onload = img.onerror = () => {
                            loaded++;
                            if (loaded === images.length && (scrollToBottom || atBottom)) {
                                messageContainer.scrollTop = messageContainer.scrollHeight;
                            }
                        };
                    });
                }
            })
            .catch(err => {
                console.error("Lỗi khi tải tin nhắn:", err);
            });
    }

    // Tải lần đầu và cập nhật mỗi 2 giây
    loadMessages(true);
    setInterval(() => loadMessages(false), 2000);
}

// ----------------------------
// Nút kết bạn / hủy / chấp nhận / từ chối
function attachFriendBtnEvents() {
    document.querySelectorAll(".friend_btn").forEach(button => {
        button.onclick = null; // Xóa sự kiện cũ (nếu có)
        button.addEventListener("click", () => {
            const receiverId = button.dataset.id;
            const action = button.dataset.action;
            if (!receiverId || !action) return;

            fetch("php/friend_action.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `receiver_id=${receiverId}&action=${action}`
            })
                .then(res => res.text())
                .then(() => location.reload())
                .catch(err => console.error(err));
        });
    });
}

document.addEventListener("DOMContentLoaded", attachFriendBtnEvents);
