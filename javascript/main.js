import { getData } from './graphFetchData.js';
import { loginPage, registerPage, nodeClicked } from './navigation.js';
import { addRecord } from './graphAddRecord.js';

window.loginPage = loginPage;
window.registerPage = registerPage;
window.nodeClicked = nodeClicked;
window.addRecord = addRecord;

document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('container');

    if (!container) {
        console.log('login page');
        loginPage();
    } else {
        console.log('graph page');
        getData();
    }
});
