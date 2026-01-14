export const getData = async () => {
    const container = document.getElementById('container');

    if (!container) {
        console.log('User not registered, no container exists');
        return;
    }

    console.log('Deleting inner HTML in container');
    container.innerHTML = '';

    container.innerHTML +=
        "<button onclick='newRecordDialog()' class='add-button'>Dodaj pomiar</button>";

    container.innerHTML +=
        "<form action='php/newgraph.php' method='POST'>" +
        "<button type='submit' class='add-button'>Dodaj nowy wykres</button>" +
        "</form>";

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
