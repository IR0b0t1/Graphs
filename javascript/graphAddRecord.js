export const newRecordDialog = () => {
    console.log('New Record Dialog');
    document.getElementById('addRecordDialog').showModal();
}

export const newGraphDialog = async (userID) => {
    await fetch(
        `php/addgraph?userID=${userID}`
    )
}

export const addRecord = async (graphID, temperature, isIll, isDone) => {
    await fetch(
        `php/addrecord.php?graphID=${graphID}&temperature=${temperature}&isIll=${isIll}&isDone=${isDone}`
    )
}