import { newRecordDialog } from './navigation.js';

export const getData = async () => {
    const container = document.getElementById('container');

    if (!container) {
        console.log('User not registered, no container exists');
        return;
    }

    console.log('Deleting inner HTML in container');
    container.innerHTML = '';

    const addButton = document.createElement('button');
    addButton.className = 'add-button';
    addButton.textContent = 'Dodaj pomiar';
    addButton.onclick = newRecordDialog;
    container.appendChild(addButton);

    const newGraphForm = document.createElement('form');
    newGraphForm.action = 'php/newgraph.php';
    newGraphForm.method = 'POST';
    const submitButton = document.createElement('button');
    submitButton.type = 'submit';
    submitButton.className = 'add-button';
    submitButton.textContent = 'Dodaj nowy wykres';
    newGraphForm.appendChild(submitButton);
    container.appendChild(newGraphForm);

    const height = window.innerHeight - 300;

    console.log('Adding image to container');
    container.innerHTML += `
        <img src="php/graph.php?width=1000&height=${height}&margin=100&days=20&t=${Math.random()}"
             alt="Temperatura"
             usemap="#graphmap">
    `;

    console.log('Fetching image map data');
    try {
        const response = await fetch(
            `php/getdata.php?width=1000&height=${height}&margin=100&days=20`
        );
        const data = await response.text();
        container.innerHTML += data;
    } catch (error) {
        console.error('Error fetching data:', error);
    }

    console.log('Fetching data complete');
};

export const updateData = async (day, temperature, isIllness, isDone) => {
    await fetch(
        `php/postdata.php?day=${day}&temperature=${temperature}&isIllness=${isIllness}&isDone=${isDone}`
    );
};
