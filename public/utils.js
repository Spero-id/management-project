/**
 * Format angka menjadi format rupiah Indonesia
 * @param {number|string} number - Angka yang akan diformat
 * @param {boolean} showSymbol - Tampilkan simbol Rp atau tidak (default: true)
 * @param {boolean} showDecimal - Tampilkan desimal atau tidak (default: false)
 * @returns {string} String format rupiah
 */
function formatRupiah(number, showSymbol = true, showDecimal = false) {
    // Konversi ke number jika input berupa string
    const num = typeof number === 'string' ? parseFloat(number.replace(/[^\d.-]/g, '')) : number;
    
    // Validasi input
    if (isNaN(num)) {
        return showSymbol ? 'Rp 0' : '0';
    }
    
    // Format dengan pemisah ribuan
    const options = {
        minimumFractionDigits: showDecimal ? 2 : 0,
        maximumFractionDigits: showDecimal ? 2 : 0
    };
    
    const formatted = num.toLocaleString('id-ID', options);
    
    // Tambahkan simbol Rp jika diperlukan
    return showSymbol ? `Rp ${formatted}` : formatted;
}

/**
 * Format angka menjadi format rupiah sederhana dengan pemisah titik
 * @param {number|string} number - Angka yang akan diformat
 * @returns {string} String format rupiah
 */
function formatRupiahSimple(number) {
    // Konversi ke string dan hapus karakter non-digit
    let numStr = number.toString().replace(/[^\d]/g, '');
    
    // Jika kosong, return 0
    if (!numStr) return 'Rp 0';
    
    // Tambahkan pemisah ribuan (titik)
    numStr = numStr.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    
    return `Rp ${numStr}`;
}

/**
 * Hapus format rupiah dan kembalikan ke angka
 * @param {string} rupiahString - String format rupiah
 * @returns {number} Angka tanpa format
 */
function parseRupiah(rupiahString) {
    // Hapus semua karakter kecuali digit dan tanda minus
    const cleaned = rupiahString.toString().replace(/[^\d.-]/g, '');
    const num = parseFloat(cleaned);
    return isNaN(num) ? 0 : num;
}

/**
 * Format input field menjadi rupiah secara real-time
 * @param {HTMLInputElement} input - Element input yang akan diformat
 */
function formatRupiahInput(input) {
    input.addEventListener('input', function(e) {
        // Simpan posisi cursor
        let cursorPosition = e.target.selectionStart;
        
        // Hapus format lama
        let value = e.target.value.replace(/[^\d]/g, '');
        
        // Format ulang
        if (value) {
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            e.target.value = `Rp ${value}`;
        } else {
            e.target.value = '';
        }
        
        // Restore posisi cursor (kurang lebih)
        const newLength = e.target.value.length;
        const oldLength = e.target.value.length;
        cursorPosition = Math.min(cursorPosition, newLength);
        e.target.setSelectionRange(cursorPosition, cursorPosition);
    });
}

/**
 * Format input untuk form dengan pemisah titik (khusus untuk input manual)
 * @param {string} value - Nilai input yang akan diformat
 * @returns {string} String dengan format ribuan menggunakan titik
 */
function formatRupiahInput(value) {
    // Remove non-numeric characters except decimal point
    let number = value.toString().replace(/[^0-9]/g, '');
    
    // Format with thousand separators
    return number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

/**
 * Parse input yang sudah diformat kembali ke angka mentah
 * @param {string} value - Nilai input yang sudah diformat
 * @returns {string} Angka mentah tanpa format
 */
function parseRupiahInput(value) {
    // Remove dots and return numeric value
    return value.replace(/\./g, '');
}

// Export functions jika menggunakan module
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        formatRupiah,
        formatRupiahSimple,
        parseRupiah,
        formatRupiahInput,
        parseRupiahInput
    };
}



// Contoh penggunaan:
// console.log(formatRupiah(1500000)); // "Rp 1.500.000"
// console.log(formatRupiah(1500000, false)); // "1.500.000"
// console.log(formatRupiah(1500000.75, true, true)); // "Rp 1.500.000,75"
// console.log(formatRupiahSimple(1500000)); // "Rp 1.500.000"
// console.log(parseRupiah("Rp 1.500.000")); // 1500000
// console.log(formatRupiahInput("1500000")); // "1.500.000"
// console.log(parseRupiahInput("1.500.000")); // "1500000"
