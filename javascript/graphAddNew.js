export const addNewGraph = async (userID) => {
    console.log("newGraphDialog called")
    await fetch(
        `php/addgraph?userID=${userID}`
    )
}