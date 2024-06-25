document.addEventListener('DOMContentLoaded', function() {
    checkUser();
    fetchDevices();//обновление при загрузке
    fetchAddedDevices()
});

let deviceId;
let isEditing = true;
const createButton = document.getElementById('button-create');

createButton.addEventListener("click", function(event) {//событие клика 
    event.preventDefault();
    if (isEditing) {
        if(checkInp()){
            createDevice()
            deviceId = null
            fetchDevices();
            fetchAddedDevices()
        }
    }
});

function fetchAddedDevices(){
    fetch("read-addedDev.php", {
        method: "GET",
        headers: {
            "Accept": "application/json"
        }
    })
    .then(response => response.json())
    .then(data =>{
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
            getColors()
        });  
    })
    .catch(error => {
        alert('Произошла ошибка: ' + error.message);
    });
}

const searchInput = document.getElementById('search-inp');//событие при записе в input 

searchInput.addEventListener('input', function() {
    const searchQuery = searchInput.value.trim();
    fetchDevices(searchQuery);
    scrollFormElement("devicesTable")
});

function fetchDevices(searchQuery = '') {//функция вывода из бд и поиска если input не пустой
    let url = 'read.php';
    
    if (searchQuery) {// if search не ""
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
                getColors()
            });
        })
        .catch(error => {
            alert('Произошла ошибка: ' + error.message);
        });
}


document.addEventListener('click', function(event) {//удаление и редактирование
    if (event.target.classList.contains('delete-btn')) {
        const deviceId = event.target.dataset.deviceId;
        deleteDevice(deviceId);
    } else if (event.target.classList.contains('edit-btn')) {
        deviceId = event.target.dataset.deviceId;
        scrollFormElement("deviceManagementSection")
        editDevice(deviceId);
        isEditing = false
        createButton.addEventListener("click", function(event){
            event.preventDefault();
            if(checkInp()){
                updateDevice(deviceId)
                isEditing = true
            }
        })
    }
});

function scrollFormElement(ElementScroll){
    const element = document.getElementById(ElementScroll)
    element.scrollIntoView({ behavior: 'smooth' });
}

function deleteDevice(deviceId) {//функция удаления
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
            fetchAddedDevices()
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        alert('Произошла ошибка: ' + error.message);
    });
}

function editDevice(deviceId) {//функция подготовки формы к редактированию
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

function updateDevice(deviceId) {//функция редактирования
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
            fetchAddedDevices();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        alert('Произошла ошибка: ' + error.message);
    });
    getColors()
}

function createDevice() {//функция добавления
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
            fetchAddedDevices()
        } else {
            alert(data.message);
        }
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
    fetch("checkuser.php")
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log(data);
        if (data.register) {
            console.log("User is registered");        
            if (!data.authentication) {
                window.location = "login.php";
            }
        } else {
            console.log("User is not registered");
            window.location = "register.php";
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

    fetch('color_table.php', {
        method: 'POST',
        headers: {  
        'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            color_border: valueBorder,
            color_text: valueText,
            color_background: valueBackground
        })
    })
  
    .then(response => response.json())
    .then(data => {
        console.log(data.message);  
        alert("цвета успешно изменены")
        location.reload();
    })
  .catch(error => {
    console.error('Ошибка:', error);
  });
}

  
function getColors() {
    fetch('color_table.php',
        {method: "GET"}
        )
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