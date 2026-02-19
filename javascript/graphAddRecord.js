export const newRecordDialog = () => {
    console.log('New Record Dialog');
    document.getElementById('addRecordDialog').showModal();
}

export const addRecord = async (graphID, temperature, isIll, isDone) => {
    await fetch(
        `php/addrecord.php?graphID=${graphID}&temperature=${temperature}&isIll=${isIll}&isDone=${isDone}`
    );
}

export const addNotDone = async (graphID) => {
    console.log("addNotDone function called");
    await fetch(
        `php/addrecord.php?graphID=${graphID}&temperature=0&isIll=false&isDone=false`
    );
}

export const addIllness = async (graphID) => {
    console.log("addIllness function called");
    await fetch(
        `php/addrecord.php?graphID=${graphID}&temperature=0&isIll=true&isDone=false`
    );
}