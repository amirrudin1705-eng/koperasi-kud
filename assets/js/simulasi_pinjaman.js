function formatRupiah(angka) {
    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

document.addEventListener('DOMContentLoaded', function () {

    const inputJumlah = document.getElementById('jumlah');
    const inputAsli = document.getElementById('jumlah_asli');

    if (inputJumlah) {
        inputJumlah.addEventListener('input', function () {
            let val = this.value.replace(/[^\d]/g, '');
            this.value = formatRupiah(val);
            inputAsli.value = val;
        });
    }

});

function simulasi() {
    let jumlah = parseInt(document.getElementById('jumlah_asli').value);

    if (!jumlah || jumlah <= 0) {
        alert('Masukkan jumlah pinjaman yang valid');
        return;
    }

    let bungaRate = 0.02; // 2% per bulan
    let tenorList = [3, 6, 12];
    let tbody = '';

    tenorList.forEach(t => {
        let bunga = jumlah * bungaRate * t;
        let total = jumlah + bunga;
        let cicilan = total / t;

        tbody += `
            <tr>
                <td>${t} Bulan</td>
                <td>${formatRupiah(Math.round(bunga))}</td>
                <td>${formatRupiah(Math.round(cicilan))}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-success"
                        onclick="pilihTenor(${t}, ${Math.round(bunga)}, ${Math.round(cicilan)})">
                        Pilih
                    </button>
                </td>
            </tr>
        `;
    });

    document.getElementById('tabelSimulasi').innerHTML = tbody;
    document.getElementById('hasil').style.display = 'block';
}

function pilihTenor(tenor, bunga, cicilan) {
    document.getElementById('tenor').value = tenor;
    document.getElementById('bunga').value = bunga;
    document.getElementById('cicilan').value = cicilan;

    alert('Tenor ' + tenor + ' bulan dipilih');
}
