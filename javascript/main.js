import { getData } from './graphFetchData.js';
import { loginPage, registerPage, nodeClicked } from './navigation.js';
import { addRecord, newRecordDialog, addIllness, addNotDone } from './graphAddRecord.js'
import { newGraphDialog } from './addNewGraph.js';
import { deleteGraph } from './graphDelete.js';
import { deleteRecord, deleteLastRecord } from './graphDeleteRecord.js';
import { exportGraphToPDF } from './graphExportToPDF.js';

const passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/;

async function requestPasswordReset(event) {
    event.preventDefault();

    const emailInput = document.querySelector("input[name='emaillogin']");
    const email = emailInput ? emailInput.value.trim() : '';

    if (!email) {
        alert('Podaj adres email, aby zresetować hasło.');
        return;
    }

    try {
        const response = await fetch('php/requestpasswordreset.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ email })
        });

        const text = await response.text();
        alert(text || 'Jeśli podany email istnieje, wysłaliśmy link do resetu hasła.');
    } catch (e) {
        console.error(e);
        alert('Wystąpił błąd podczas wysyłania emaila resetującego.');
    }
}

function initPasswordResetDialog() {
    const dialog = document.getElementById('resetPasswordDialog');
    const saveButton = document.getElementById('resetPasswordSave');
    const tokenInput = document.getElementById('resetToken');
    const newPassInput = document.getElementById('newPassword');
    const confirmPassInput = document.getElementById('confirmNewPassword');

    if (!dialog || !saveButton || !tokenInput || !newPassInput || !confirmPassInput) {
        return;
    }

    saveButton.addEventListener('click', async () => {
        const newPassword = newPassInput.value;
        const confirmPassword = confirmPassInput.value;

        if (newPassword !== confirmPassword) {
            alert('Hasła nie są takie same.');
            return;
        }

        if (!passwordRegex.test(newPassword)) {
            alert('Hasło musi mieć co najmniej 8 znaków i zawierać małą literę, wielką literę oraz cyfrę.');
            return;
        }

        try {
            const response = await fetch('php/resetpassword.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    token: tokenInput.value,
                    password: newPassword
                })
            });

            const text = await response.text();
            alert(text || 'Hasło zostało zmienione.');

            if (response.ok) {
                dialog.close();
                window.history.replaceState({}, document.title, 'index.php');
            }
        } catch (e) {
            console.error(e);
            alert('Wystąpił błąd podczas zmiany hasła.');
        }
    });

    const urlParams = new URLSearchParams(window.location.search);
    const tokenFromUrl = urlParams.get('resetToken');

    if (tokenFromUrl) {
        tokenInput.value = tokenFromUrl;
        dialog.showModal();
    }
}

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
window.exportGraphToPdf = exportGraphToPDF;
window.requestPasswordReset = requestPasswordReset;

initPasswordResetDialog(); 