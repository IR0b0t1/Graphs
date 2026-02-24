import { getData } from './graphFetchData.js';
import { loginPage, registerPage, nodeClicked } from './navigation.js';
import { addRecord, newRecordDialog, addIllness, addNotDone } from './graphAddRecord.js'
import { newGraphDialog } from './addNewGraph.js';
import { deleteGraph } from './graphDelete.js';
import { deleteRecord, deleteLastRecord } from './graphDeleteRecord.js';
import { exportGraphToPDF } from './graphExportToPDF.js';

window.loginPage = loginPage;
window.registerPage = registerPage;
window.nodeClicked = nodeClicked;
window.addRecord = addRecord;
window.addIllness = addIllness;
window.addNotDone = addNotDone;
window.newRecordDialog = newRecordDialog;
window.newGraphDialog = newGraphDialog;
window.getData = getData;
window.deleteGraph = deleteGraph;
window.deleteRecord = deleteRecord;
window.deleteLastRecord = deleteLastRecord;
window.exportGraphToPDF = exportGraphToPDF;