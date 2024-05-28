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
                        <button class="delete-btn" data-device-id="${device.id}">Удалить</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => {
            alert('Произошла ошибка: ' + error.message);
        });
}

document.addEventListener('click', function(event) {
    if (event.target.classList.contains('delete-btn')) {
        const deviceId = event.target.dataset.deviceId;
        deleteDevice(deviceId);
    }
});

function deleteDevice(deviceId) {
    fetch('delete.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `id=${deviceId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            fetchDevices(); // Обновляем таблицу после удаления
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        alert('Произошла ошибка: ' + error.message);
    });
}