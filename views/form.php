<h1>Введите ссылку</h1>
<form method="post">
    <input id="url" type="text" name="url">
    <button onclick="sendData()" type="button">Получить короткую ссылку</button>
    <p id="hash"></p>
</form>

<script>
    function sendData() {
        let url = document.getElementById('url').value;
        let xhr = new XMLHttpRequest();
        xhr.open("POST", '/api/links', true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById('hash').innerText = this.responseText;
            }
        };
        const data = JSON.stringify({"url": url});
        xhr.send(data);
    }
</script>