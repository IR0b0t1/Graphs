import { getData, updateData } from './graphFetchData.js';
import { deleteRecord } from './graphDeleteRecord.js';

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

const dialog = document.getElementById('editTemperatureDialog');
const dayLabel = document.getElementById('dayLabel');
const saveButton = document.getElementById('editTemperatureSave');
const illButton = document.getElementById('editTemperatureIll');
const noDataButton = document.getElementById('editTemperatureNoData');
const deleteDayButton = document.getElementById('editTemperatureDelete');
const temperatureInput = document.getElementById('editTemperatureInput');
const graphNo = document.getElementById('editTemperatureGraphNo').value;

console.log()
saveButton.addEventListener('click', async (e) => {
    console.log("saveButton eventListener called...");
    console.log(`graphNo = ${graphNo}`);
    e.preventDefault();
    await updateData(graphNo, day, temperatureInput.value, false, true);
    dialog.close();
    getData(graphNo);
});

illButton.addEventListener('click', async (e) => {
    console.log("illButton eventListener called...");
    console.log(`graphNo = ${graphNo}`);
    e.preventDefault();
    await updateData(graphNo, day, temperatureInput.value, true, true);
    dialog.close();
    getData(graphNo);
});

noDataButton.addEventListener('click', async (e) => {
    console.log("noDataButton eventListener called...");
    console.log(`graphNo = ${graphNo}`);
    e.preventDefault();
    await updateData(graphNo, day, 0, false, false);
    dialog.close();
    getData(graphNo);
});

deleteDayButton.addEventListener('click', async (e) => {
    console.log("deleteDayButton eventListener called...");
    console.log(`graphNo = ${graphNo}, day = ${day}`);
    e.preventDefault();
    await deleteRecord(day, graphNo);
    dialog.close();
    getData(graphNo);
})

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