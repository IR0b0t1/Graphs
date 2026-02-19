import { getData, updateData } from './graphFetchData.js';

export const loginPage = () => {
    console.log('loginPage changed');
    document.getElementById('loginbox').className = 'formbox';
    document.getElementById('registerbox').className = 'hidden';
    document.getElementById('login-page-button').className = 'active';
    document.getElementById('register-page-button').className = 'inactive';
};

export const registerPage = () => {
    console.log('registerPage changed');
    document.getElementById('registerbox').className = 'formbox';
    document.getElementById('loginbox').className = 'hidden';
    document.getElementById('register-page-button').className = 'active';
    document.getElementById('login-page-button').className = 'inactive';
};

let day = 0;
let temperature = 0;
let isIllness = false;
let isDone = false;
let id = 0;

const dialog = document.createElement('dialog');
dialog.className = 'dialog-window';
dialog.id = 'temperatureDialog';

const formContainer = document.createElement('div');
formContainer.className = 'form-container';

const editForm = document.createElement('form');
editForm.method = 'POST';
editForm.className = 'dialog-form';

const header = document.createElement('h2');
header.innerText = 'Edytuj dzień';

const dayLabel = document.createElement('label');

const temperatureInput = document.createElement('input');
temperatureInput.className = 'form-input';
temperatureInput.name = 'temperature-change';
temperatureInput.type = 'number';
temperatureInput.min = 36;
temperatureInput.max = 37;

const saveButton = document.createElement('button');
saveButton.className = 'form-button';
saveButton.innerText = 'Zapisz temperaturę';

const illButton = document.createElement('button');
illButton.className = 'form-button';
illButton.innerText = 'Choroba';

const noDataButton = document.createElement('button');
noDataButton.className = 'form-button';
noDataButton.innerText = 'Brak pomiaru';

saveButton.addEventListener('click', async (e) => {
    console.log("saveButton eventListener called...");
    e.preventDefault();
    await updateData(day, temperatureInput.value, false, true);
    dialog.close();
    getData();
});

illButton.addEventListener('click', async (e) => {
    console.log("illButton eventListener called...");
    e.preventDefault();
    await updateData(day, temperatureInput.value, true, true);
    dialog.close();
    getData();
});

noDataButton.addEventListener('click', async (e) => {
    console.log("noDataButton eventListener called...");
    e.preventDefault();
    await updateData(day, 0, false, false);
    dialog.close();
    getData();
});

const closeForm = document.createElement('form');
closeForm.method = 'dialog';
closeForm.className = 'dialog-form';

const closeButton = document.createElement('button');
closeButton.className = 'form-button';
closeButton.innerText = 'Zamknij';

editForm.append(
    header,
    dayLabel,
    temperatureInput,
    saveButton,
    illButton,
    noDataButton
);

closeForm.appendChild(closeButton);
formContainer.append(editForm, closeForm);
dialog.appendChild(formContainer);
document.body.appendChild(dialog);

export const nodeClicked = (
    clickedDay,
    clickedTemperature,
    clickedIsIllness,
    clickedIsDone,
    clickedId
) => {
    console.log('nodeClicked called...');

    day = clickedDay;
    temperature = clickedTemperature;
    isIllness = clickedIsIllness == 1;
    isDone = clickedIsDone == 1;
    id = clickedId;

    dayLabel.innerText = `Dzień ${day}`;
    temperatureInput.value = temperature || '';

    dialog.showModal();
};