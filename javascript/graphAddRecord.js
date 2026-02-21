import { getData } from './graphFetchData.js';

export const newRecordDialog = () => {
    console.log('New Record Dialog');
    document.getElementById('addRecordDialog').showModal();
}

export const addRecord = async (graphNo) => {
    const dialog = document.getElementById("addRecordDialog");
    const temperature = document.getElementById("temperatureNew").value;
    const isIll = false;
    const isDone = true;
    console.log("addRecord function called");
    console.table([`graphNo: ${graphNo}`,
    `temperature: ${temperature}`,
    `isIll: ${isIll}`,
    `isDone: ${isDone}`]);
    await fetch(`php/addrecord.php?graphNo=${graphNo}&temperature=${temperature}&isIll=${isIll}&isDone=${isDone}`)
        .then(dialog.close())
        .then(() => getData(graphNo))
}

export const addNotDone = async (graphNo) => {
    const dialog = document.getElementById("addRecordDialog");
    const temperature = document.getElementById("temperatureNew").value;
    const isIll = false;
    const isDone = false;
    console.log("addNotDone function called");
    console.table([`graphNo: ${graphNo}`,
    `temperature: ${temperature}`,
    `isIll: ${isIll}`,
    `isDone: ${isDone}`]);
    await fetch(`php/addrecord.php?graphNo=${graphNo}&temperature=${temperature}&isIll=${isIll}&isDone=${isDone}`)
        .then(dialog.close())
        .then(() => getData(graphNo))
}

export const addIllness = async (graphNo) => {
    const dialog = document.getElementById("addRecordDialog");
    const temperature = document.getElementById("temperatureNew").value;
    const isIll = true;
    const isDone = false;
    console.log("addIllness function called");
    console.table([`graphNo: ${graphNo}`,
    `temperature: ${temperature}`,
    `isIll: ${isIll}`,
    `isDone: ${isDone}`]);
    await fetch(`php/addrecord.php?graphNo=${graphNo}&temperature=${temperature}&isIll=${isIll}&isDone=${isDone}`)
        .then(dialog.close())
        .then(() => getData(graphNo))
}