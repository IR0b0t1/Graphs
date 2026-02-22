import { getData } from "./graphFetchData.js";

// Function calls deletegraph.php using AJAX, then redirects to a graph with 1 less number
export const deleteGraph = async (graphNo) => {
    console.log('deleteGraph function called');
    fetch(`php/deletegraph.php?graphNo=${graphNo}`)
        .then(() => {
            console.log('Redirect to previous graph');
            window.location.href = `index.php?graphNo=${graphNo - 1}`;
        });
}

// Function calls deleteday.php, then refreshes a graph using getData function
export const deleteDay = async (graphNo, dayNo) => {
    console.log('deleteDay function called');
    fetch(`php/deleteday.php?graphNo=${graphNo}&dayNo=${dayNo}`)
        .then(() => {
            console.log('Refresh graph');
            getData(graphNo);
        })
}