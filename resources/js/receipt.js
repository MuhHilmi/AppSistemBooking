import html2canvas from 'html2canvas';
import { jsPDF } from 'jspdf';

/**
 * Render elemen DOM (struk) menjadi canvas beresolusi tinggi.
 */
async function renderElementToCanvas(element) {
    return html2canvas(element, {
        scale: 2, // biar hasil download tidak pecah/blur
        useCORS: true,
        backgroundColor: '#ffffff',
    });
}

/**
 * Download elemen struk sebagai file PNG.
 */
async function downloadReceiptAsPng(elementId, filename) {
    const element = document.getElementById(elementId);
    if (!element) return;

    const canvas = await renderElementToCanvas(element);
    const link = document.createElement('a');
    link.download = `${filename}.png`;
    link.href = canvas.toDataURL('image/png');
    link.click();
}

/**
 * Download elemen struk sebagai file PDF (ukuran menyesuaikan proporsi struk).
 */
async function downloadReceiptAsPdf(elementId, filename) {
    const element = document.getElementById(elementId);
    if (!element) return;

    const canvas = await renderElementToCanvas(element);
    const imgData = canvas.toDataURL('image/png');

    // Konversi ukuran canvas (px) ke mm supaya PDF pas dengan proporsi struk,
    // bukan dipaksa ke ukuran kertas A4 standar.
    const pxToMm = (px) => px * 0.264583;
    const widthMm = pxToMm(canvas.width / 2); // dibagi 2 karena scale: 2 di atas
    const heightMm = pxToMm(canvas.height / 2);

    const pdf = new jsPDF({
        orientation: widthMm > heightMm ? 'landscape' : 'portrait',
        unit: 'mm',
        format: [widthMm, heightMm],
    });

    pdf.addImage(imgData, 'PNG', 0, 0, widthMm, heightMm);
    pdf.save(`${filename}.pdf`);
}

window.downloadReceiptAsPng = downloadReceiptAsPng;
window.downloadReceiptAsPdf = downloadReceiptAsPdf;
