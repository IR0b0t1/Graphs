import { getData } from './graphFetchData.js';
import { loginPage, registerPage, nodeClicked } from './navigation.js';
import { addRecord, newRecordDialog, addIllness, addNotDone } from './graphAddRecord.js'
import { addNewGraph } from './graphAddNew.js';

window.loginPage = loginPage;
window.registerPage = registerPage;
window.nodeClicked = nodeClicked;
window.addRecord = addRecord;
window.addIllness = addIllness;
window.addNotDone = addNotDone;
window.newRecordDialog = newRecordDialog;
window.addNewGraph = addNewGraph;
window.getData = getData;

document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('container');

    if (!container) {
        console.log('login page');
        loginPage();
    } else {
        console.log('graph page');
        getData(1);
    }
});
