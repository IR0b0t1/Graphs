import { getData } from "./graphFetchData.js";

export const deleteRecord = async (day, graphNo) => {
    console.log("deleteRecord function called");
    console.log(`day: ${day}, graphNo: ${graphNo}`);
    fetch(`php/deleterecord.php?day=${day}&graphNo=${graphNo}`);
}

export const deleteLastRecord = async (graphNo) => {
    console.log("deleteLastRecord function called");
    console.log(`graphID: ${graphNo}`);
    fetch(`php/deletelastrecord.php?graphID=${graphNo}`)
        .then(() => getData(graphNo));
}