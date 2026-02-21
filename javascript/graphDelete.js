export const deleteGraph = (graphNo) => {
    console.log('deleteGraph function called');
    fetch(`php/deletegraph.php?graphNo=${graphNo}`);
}