export const deleteGraph = (graphNo) => {
    console.log('deleteGraph function called');
    fetch(`php/deletegraph.php?graphNo=${graphNo}`)
        .then(() => {
            console.log('Redirect to previous graph');
            window.location.href = `index.php?graphNo=${graphNo - 1}`;
        });
}