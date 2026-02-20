export const addNewGraph = async (graphNo) => {
    console.log("newGraphDialog called...");
    await fetch(
        `php/addgraph?graphNo=${graphNo}`
    );
}