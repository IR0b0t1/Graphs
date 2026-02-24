export const getData = async (graphNo) => {
    console.log("getData function called...")
    console.log(`graphNo: ${graphNo}`);
    const container = document.getElementById('container');

    if (!container) {
        console.log('User not registered, no container exists');
        return;
    }

    console.log('Deleting inner HTML in container');
    container.innerHTML = '';

    const height = window.innerHeight - 320;
    const width = 1300;

    console.log('Adding image to container');
    container.innerHTML += `
        <img src="php/graph.php?width=${width}&height=${height}&margin=100&graphNo=${graphNo}&t=${Date.now()}"
             alt="Temperatura"
             usemap="#graphmap">
    `;

    const imagemapLink = `php/getdata.php?width=${width}&height=${height}&margin=100&graphNo=${graphNo}`

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
    console.log(temperature);
    await fetch(
        `php/postdata.php?graphNo=${graphNo}&day=${day}&temperature=${temperature}&isIllness=${isIllness}&isDone=${isDone}`
    )
        .then(() => { getData(graphNo) });
};
