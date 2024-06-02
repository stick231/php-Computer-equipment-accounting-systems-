document.addEventListener('DOMContentLoaded', function() {
        fetchDevices();
});

let deviceId;
let isEditing = true;
const createButton = document.getElementById('button-create');

createButton.addEventListener("click", function(event) {
    event.preventDefault();
    if (isEditing) {
        createDevice()
        deviceId = null
        fetchDevices();
    }
});

const searchInput = document.getElementById('search-inp');
searchInput.addEventListener('input', function() {
    const searchQuery = searchInput.value.trim();
    fetchDevices(searchQuery);
});

function fetchDevices(searchQuery = '') {
    let url = 'read.php';
    
    if (searchQuery) {
        url += `?search=${searchQuery}`;
    }

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('table-body');
            tableBody.innerHTML = '';

            data.forEach(device => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${device.id}</td>
                    <td>${device.device_type}</td>
                    <td>${device.manufacturer}</td>
                    <td>${device.model}</td>
                    <td>${device.serial_number}</td>
                    <td>${device.purchase_date}</td>
                    <td>
                        <button class="edit-btn" data-device-id="${device.id}">Редактировать</button>
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
    } else if (event.target.classList.contains('edit-btn')) {
        deviceId = event.target.dataset.deviceId;
        editDevice(deviceId);
        isEditing = false
        createButton.addEventListener("click", function(event){
            event.preventDefault();
            updateDevice(deviceId)
            isEditing = true
        })
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
            fetchDevices();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        alert('Произошла ошибка: ' + error.message);
    });
}

function editDevice(deviceId) {
    const deviceTypeInput = document.getElementById('device_type');
    const manufacturerInput = document.getElementById('manufacturer');
    const modelInput = document.getElementById('model');
    const serialNumberInput = document.getElementById('serial_number');
    const purchaseDateInput = document.getElementById('purchase_date');

    if (deviceTypeInput && manufacturerInput && modelInput && serialNumberInput && purchaseDateInput) {
        fetch(`read_one.php?id=${deviceId}`)
            .then(response => response.json())
            .then(data => {
                deviceTypeInput.value = data.device_type;
                manufacturerInput.value = data.manufacturer;
                modelInput.value = data.model;
                serialNumberInput.value = data.serial_number;
                purchaseDateInput.value = data.purchase_date;
            })
            .catch(error => {
                alert('Произошла ошибка: ' + error.message);
            });

        createButton.value = 'Сохранить';

        createButton.removeEventListener('click', updateDevice);

    } else {
        alert('Не найдены необходимые поля формы');
    }
}

function updateDevice(deviceId) {
    const form = document.getElementById('deviceForm');
    const formData = new FormData(form);
    formData.append('id', deviceId);

    fetch(`update.php`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            form.reset();
            createButton.value = 'Создать';
            createButton.textContent = 'Создать';
            fetchDevices();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        alert('Произошла ошибка: ' + error.message);
    });
}

function createDevice() {
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
}

//реализовать поиск