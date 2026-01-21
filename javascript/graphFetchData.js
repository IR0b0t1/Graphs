export const getData = async (graphID) => {
    console.log(`Graph id: ${graphID}`);
    const container = document.getElementById('container');

    if (!container) {
        console.log('User not registered, no container exists');
        return;
    }

    console.log('Deleting inner HTML in container');
    container.innerHTML = '';

    const height = window.innerHeight - 300;

    console.log('Adding image to container');
    container.innerHTML += `
        <img src="php/graph.php?width=1000&height=${height}&margin=100&days=20&t=${Math.random()}"
             alt="Temperatura"
             usemap="#graphmap">
    `;

    console.log(`php/getdata.php?width=1000&height=${height}&margin=100&graphID=${graphID}`);
    console.log('Fetching image map data');
    try {
        const response = await fetch(
            `php/getdata.php?width=1000&height=${height}&margin=100&graphID=${graphID}`
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
