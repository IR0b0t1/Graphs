export const getData = async (graphNo) => {
    console.log(`Graph id: ${graphNo}`);
    const container = document.getElementById('container');

    if (!container) {
        console.log('User not registered, no container exists');
        return;
    }

    console.log('Deleting inner HTML in container');
    container.innerHTML = '';

    const height = window.innerHeight - 320;

    console.log('Adding image to container');
    container.innerHTML += `
        <img src="php/graph.php?width=1000&height=${height}&margin=100&graphNo=${graphNo}&t=${Math.random()}"
             alt="Temperatura"
             usemap="#graphmap">
    `;

    const imagemapLink = `php/getdata.php?width=1000&height=${height}&margin=100&graphNo=${graphNo}`

    console.log(imagemapLink);
    console.log('Fetching image map data');
    try {
        const response = await fetch(imagemapLink);
        const data = await response.text();
        container.innerHTML += data;
    } catch (error) {
        console.error('Error fetching data:', error);
    }

    console.log('Fetching data complete');
};

export const updateData = async (graphNo, day, temperature, isIllness, isDone) => {
    await fetch(
        `php/postdata.php?day=${day}&temperature=${temperature}&isIllness=${isIllness}&isDone=${isDone}`
    )
        .then(getData(graphNo));
};
