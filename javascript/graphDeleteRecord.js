import { getData } from "./graphFetchData.js";

export const deleteRecord = async (day, graphID) => {
    fetch(`php/deleterecord.php?day=${day}&graphID=${graphID}`)
        .then(() => getData(graphID));
}