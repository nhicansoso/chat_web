const searchInput = document.querySelector('.searchBar input');
const userList = document.querySelector('.users_list');
const userChat = document.querySelector('.users_chat');
const searchBtn = document.querySelector('.searchBtn');
const closeBtn = document.querySelector('.closeBtn');

window.addEventListener("DOMContentLoaded", () => {
    fetch("php/friend.php", {
        method: "POST"
    })
        .then((res) => res.text())
        .then((data) => {
            userChat.innerHTML = data;
        });
});

searchInput.addEventListener('focus', () => {
    userList.style.display = "block";
    userChat.style.display = "none";
    closeBtn.style.display = 'inline-block';
    searchBtn.style.display = 'none';
});

searchBtn.addEventListener('click', () => {
    closeBtn.style.display = 'inline-block';
    searchBtn.style.display = 'none';
    userList.style.display = 'block';
    userChat.style.display = 'none';
    searchInput.focus();
});

closeBtn.addEventListener('click', () => {
    closeBtn.style.display = 'none';
    searchBtn.style.display = 'inline-block';
    userList.style.display = 'none';
    userChat.style.display = 'block';
    searchInput.value = '';
});

searchInput.onkeyup = () => {
    let searchTerm = searchInput.value.trim();

    if (searchTerm !== "") {
        searchInput.classList.add('active');
        userList.style.display = "block";
        userChat.style.display = "none";

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "php/search.php", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                userList.innerHTML = xhr.response;
            }
        };
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("searchTerm=" + encodeURIComponent(searchTerm));
    } else {
        searchInput.classList.remove('active');
        userList.style.display = "none";
        userChat.style.display = "block";
    }
};
