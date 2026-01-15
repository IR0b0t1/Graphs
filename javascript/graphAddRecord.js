export const addRecord = async (graphID, temperature, isIll, isDone) => {
    await fetch(
        `php/addrecord.php?graphID=${graphID}&temperature=${temperature}&isIll=${isIll}&isDone=${isDone}`
    )
}