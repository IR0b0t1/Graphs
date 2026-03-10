export const exportGraphToPDF = (graphNo) => {
    if (!graphNo) {
        console.error('Brak numeru wykresu do eksportu.');
        return;
    }

    const url = `php/exportgraphtopdf.php?graphNo=${encodeURIComponent(graphNo)}`;

    // Otwórz PDF w nowej karcie/przeglądarce
    window.open(url, '_blank');
};