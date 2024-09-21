document.addEventListener('DOMContentLoaded', function() {
    getColors()
    checkUser();
    fetchDevices();
    fetchAddedDevices()
});

let deviceId;
let isEditing = false;
const createButton = document.getElementById('button-create');

function fetchAddedDevices() {
    fetch("/api/latestAddedDevices", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
    })
    .then(response => response.json())
    .then(data => {
        const tableBody = document.getElementById('added-device-table');
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
        getColors();
    })
    .catch(error => {
        console.log('Произошла ошибка: ' + error.message);
    });
}

const searchInput = document.getElementById('search-inp');//событие при записе в input 

searchInput.addEventListener('input', function() {
    const searchQuery = searchInput.value.trim();
    fetchDevices(searchQuery);
    scrollFormElement("devicesTable")
});

function fetchDevices(searchQuery = '') {
    let url = '/api/devices';

    fetch(url, { 
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ search: searchQuery }) 
    })
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
        getColors();
    })
    .catch(error => {
        console.log('Произошла ошибка: ' + error.message);
    });
}

createButton.addEventListener("click", function(event) {
    event.preventDefault();
    if (isEditing) {
        if (checkInp()) {
            updateDevice(deviceId); 
            isEditing = false; 
        }
    } else {
        if (checkInp()) {
            createDevice();
            fetchDevices();
            fetchAddedDevices();
        }
    }
});

document.addEventListener('click', function(event) { 
    if (event.target.classList.contains('delete-btn')) {
        const deviceId = event.target.dataset.deviceId;
        deleteDevice(deviceId);
    } else if (event.target.classList.contains('edit-btn')) {
        deviceId = event.target.dataset.deviceId;
        scrollFormElement("deviceManagementSection");
        editDevice(deviceId);
        isEditing = true; 
    }
});

function scrollFormElement(ElementScroll){
    const element = document.getElementById(ElementScroll)
    element.scrollIntoView({ behavior: 'smooth' });
}

function deleteDevice(deviceId) {//функция удаления
    fetch('/', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `id=${deviceId}&deleteDevice=true`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            fetchDevices();
            fetchAddedDevices()
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
    const createButton = document.getElementById('button-create'); 

    if (deviceTypeInput && manufacturerInput && modelInput && serialNumberInput && purchaseDateInput) {
        fetch(`/api/devices`,{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ 'id' : deviceId })
        }) 
            .then(response => response.json())
            .then(data => {
                if (Array.isArray(data) && data.length > 0) {
                    const deviceData = data[0]; 
                    deviceTypeInput.value = deviceData.device_type;
                    manufacturerInput.value = deviceData.manufacturer;
                    modelInput.value = deviceData.model;
                    serialNumberInput.value = deviceData.serial_number;
                    purchaseDateInput.value = deviceData.purchase_date;

                    isEditing = true;
                } else {
                    alert('Устройство не найдено или данные повреждены.');
                }
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

function updateDevice(deviceId) {//функция редактирования
    const form = document.getElementById('deviceForm');
    const formData = new FormData(form);
    formData.append('id', deviceId);
    formData.append('updateDevice', deviceId);

    fetch(`/`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            form.reset();
            createButton.textContent = 'Создать';
            alert(data.message)
        } else {
            alert(data.message);
        }
        fetchDevices();
        fetchAddedDevices();
    })
    .catch(error => {
        alert('Произошла ошибка: ' + error.message);
    });
    getColors()
}

function createDevice() {//функция добавления
    const form = document.getElementById('deviceForm');
    const formData = new FormData(form);
    formData.append('createDevice', 'true');
    
    fetch('/', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data)
        if (data.success) {
            alert(data.message);
            form.reset();
        } else {
            alert(data.message);
        }
        fetchDevices();
        fetchAddedDevices();
    })
    .catch(error => {
        alert('Произошла ошибка: ' + error.message);
    });
}

function checkInp(){
    const deviceTypeInput = document.getElementById('device_type');
    const manufacturerInput = document.getElementById('manufacturer');
    const modelInput = document.getElementById('model');
    const serialNumberInput = document.getElementById('serial_number');
    const purchaseDateInput = document.getElementById('purchase_date');

    if(deviceTypeInput.value === ''){
        alert('Введите тип устройства');
        return false;
    }

    if(manufacturerInput.value === ''){
        alert('Введите производителя');
        return false;
    }

    if(modelInput.value === ''){
        alert('Введите модель');
        return false;
    }

    if(serialNumberInput.value === ''){
        alert('Введите серийный номер');
        return false;
    }

    if(purchaseDateInput.value === ''){
        alert('Введите дату приобретения');
        return false;
    }

    return true;
}

function checkUser() {
    fetch("/check-user")
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.register) {
            console.log("User is registered");        
            if (!data.authentication) {
                console.log("User is not auth");
            }
        } else {
            console.log("User is not registered");
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

const buttonColor = document.getElementById("color-button")

buttonColor.addEventListener("click", ()=>{
    saveColors()
})

const colorBorder = document.getElementById('color_border')
const colorText = document.getElementById('color_text')
const colorBackground = document.getElementById('color_background')

const tableExample = document.getElementById("table-example")

colorBorder.addEventListener("input", () => {
        tableExample.style.border = `1px solid ${colorBorder.value}`
})

colorText.addEventListener("input", () => {
    tableExample.style.color = colorText.value
})

colorBackground.addEventListener("input", () => {
    tableExample.style.background = colorBackground.value
})


function saveColors() {
    const valueBorder = colorBorder.value;
    const valueText = colorText.value;
    const valueBackground = colorBackground.value;

    fetch('/api/custom-table', {
        method: 'POST',
        headers: {  
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            color_border: valueBorder,
            color_text: valueText,
            color_background: valueBackground,
            saveColor: true
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Ошибка сети');
        }
        return response.json();
    })
    .then(data => {
        alert(data.message);  
        location.reload();
    })
    .catch(error => {
        console.error('Ошибка:', error);
        alert('Произошла ошибка при сохранении цветов.');
    });
}

  
function getColors() {
    fetch('/?getColorTable=true',{method: "GET"})
    .then(response => response.json())
    .then(data => {
        if (data.color_border && data.color_text && data.color_background) {
            document.getElementById('color_border').value  = data.color_border;
            document.getElementById('color_text').value = data.color_text;
            document.getElementById('color_background').value = data.color_background;
        
            const allTable = document.querySelectorAll('table:not(#table-example) th, table:not(#table-example) td')
            
            allTable.forEach((table) => {
                table.style.border = `1px solid ${data.color_border}` 
                table.style.color = data.color_text
                table.style.backgroundColor = data.color_background
            })
      } else {
        console.log(data.message);
      }
    })
    .catch(error => {
      console.error('Ошибка:', error);
    });
}
