document.getElementById("button-create").addEventListener("click", function(event) {
    event.preventDefault();
    const form = document.getElementById('deviceForm');
    const formData = new FormData(form);

    fetch('create.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        // Вставляем ответ сервера в DOM
        const successMessage = document.createElement('div');
        successMessage.innerHTML = data;
        document.body.appendChild(successMessage);
    })
    .catch(error => {
        alert('Произошла ошибка: ' + error.message);
    });
})
