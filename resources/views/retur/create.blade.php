@extends('layouts.app')

@section('content')
<div class="card border-0 shadow-sm p-4 mb-4 rounded-3">
    <h4 class="fw-bold mb-4">Pencatatan Retur Penjualan</h4>
    <form action="{{ route('retur.store') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="penjualan_id" class="form-label">Nomor Invoice Penjualan</label>
                <select name="penjualan_id" id="penjualan_id" class="form-select" required>
                    <option value="">-- Pilih Invoice --</option>
                    @foreach($penjualans as $p)
                        <option value="{{ $p->id }}">Invoice: {{ $p->nomor_invoice }} - Total: Rp {{ number_format($p->total_bayar,0,',','.') }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="tanggal_retur" class="form-label">Tanggal Retur</label>
                <input type="date" name="tanggal_retur" id="tanggal_retur" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="alasan_retur" class="form-label">Alasan Retur</label>
            <textarea name="alasan_retur" id="alasan_retur" rows="2" class="form-control"></textarea>
        </div>

        <h6 class="fw-bold mt-4 mb-3">Item Retur</h6>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="bg-light">
                    <tr>
                        <th>Pilih Barang</th>
                        <th>Harga Satuan</th>
                        <th>Beli</th>
                        <th>Qty Retur</th>
                        <th>Subtotal Retur</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="returItems">
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total Retur</th>
                        <th colspan="2">
                            <input type="number" name="total_retur" id="total_retur" class="form-control-plaintext fw-bold" readonly value="0">
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <button type="button" class="btn btn-secondary btn-sm mb-3" id="addItemBtn">Tambah Barang</button>

        <div class="text-end">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Retur</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const penjualanSelect = document.getElementById('penjualan_id');
        const returItemsBody = document.getElementById('returItems');
        const totalReturEl = document.getElementById('total_retur');
        const addItemBtn = document.getElementById('addItemBtn');
        let currentProducts = [];
        let index = 0;

        penjualanSelect.addEventListener('change', async function() {
            const id = this.value;
            returItemsBody.innerHTML = '';
            calculateTotal();
            if (!id) return;
            
            try {
                const response = await fetch(`/api/penjualan/${id}`);
                currentProducts = await response.json();
            } catch (error) {
                console.error("Gagal mengambil data produk", error);
            }
        });

        addItemBtn.addEventListener('click', function() {
            if (currentProducts.length === 0) {
                alert('Pilih invoice penjualan terlebih dahulu!');
                return;
            }

            let options = '<option value="">-- Pilih --</option>';
            currentProducts.forEach(p => {
                options += `<option value="${p.produks_id}" data-harga="${p.harga_satuan}" data-qty="${p.jumlah}">${p.nama_produk}</option>`;
            });

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <select name="items[${index}][produk_id]" class="form-select produk-select" required>
                        ${options}
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${index}][harga]" class="form-control-plaintext harga-input" readonly value="0">
                </td>
                <td class="qty-beli">0</td>
                <td>
                    <input type="number" name="items[${index}][qty]" class="form-control qty-input" min="1" value="1" required>
                </td>
                <td>
                    <input type="number" class="form-control-plaintext subtotal-input" readonly value="0">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                </td>
            `;
            returItemsBody.appendChild(tr);
            index++;
        });

        returItemsBody.addEventListener('change', function(e) {
            if (e.target.classList.contains('produk-select')) {
                const tr = e.target.closest('tr');
                const selectedList = e.target.options[e.target.selectedIndex];
                const harga = selectedList.getAttribute('data-harga') || 0;
                const qtyBeli = selectedList.getAttribute('data-qty') || 0;
                
                tr.querySelector('.harga-input').value = harga;
                tr.querySelector('.qty-beli').textContent = qtyBeli;
                const qtyInput = tr.querySelector('.qty-input');
                qtyInput.max = qtyBeli;
                
                updateRow(tr);
            }
        });

        returItemsBody.addEventListener('input', function(e) {
            if (e.target.classList.contains('qty-input')) {
                const tr = e.target.closest('tr');
                updateRow(tr);
            }
        });

        returItemsBody.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                e.target.closest('tr').remove();
                calculateTotal();
            }
        });

        function updateRow(tr) {
            const harga = parseFloat(tr.querySelector('.harga-input').value) || 0;
            const qty = parseFloat(tr.querySelector('.qty-input').value) || 0;
            const maxQty = parseFloat(tr.querySelector('.qty-beli').textContent) || 0;
            
            if(qty > maxQty) {
                alert('Qty retur melebihi qty beli!');
                tr.querySelector('.qty-input').value = maxQty;
                tr.querySelector('.subtotal-input').value = harga * maxQty;
            } else {
                tr.querySelector('.subtotal-input').value = harga * qty;
            }
            calculateTotal();
        }

        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.subtotal-input').forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            totalReturEl.value = total;
        }
    });
</script>
@endsection
