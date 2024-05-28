document.addEventListener('DOMContentLoaded', function() {
    fetchDevices();
});

document.getElementById("button-create").addEventListener("click", function(event) {
    event.preventDefault();
    const form = document.getElementById('deviceForm');
    const formData = new FormData(form);

    fetch('create.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            form.reset();
            fetchDevices();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        alert('Произошла ошибка: ' + error.message);
    });
});

function fetchDevices() {
    fetch('read.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('table-body');
            tableBody.innerHTML = '';

            data.forEach(device => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${device.id}</td>
                    <td>${device.type}</td>
                    <td>${device.manufacturer}</td>
                    <td>${device.model}</td>
                    <td>${device.serialNumber}</td>
                    <td>${device.purchaseDate}</td>
                    <td>
                        <button class="edit-btn">Редактировать</button>
                        <button class="delete-btn">Удалить</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => {
            alert('Произошла ошибка: ' + error.message);
        });
}
