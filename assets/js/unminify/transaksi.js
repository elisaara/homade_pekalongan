let isCetak = false,
	url,
    pemasukan = $("#pemasukan").DataTable({
    responsive: !0,
    scrollX: !0,
    ajax: readUrl,
    columnDefs: [{
        searcable: !1,
        orderable: !1,
        targets: 0
    },{
        "targets": [0],
        "visible": false,
        "searchable": false
    }],
    order: [
        [0, "desc"]
    ],
    columns: [{
        data: "id"
    }, {
        data: "nota"
    }, {
        data: "tanggal"
    }, {
        data: "pelanggan"
    }, {
        data: "total_bayar"
    }, {
        data: "status_desc"
    }, {
        data: "action"
    }]
});

function reloadTable() {
    pemasukan.ajax.reload()
}

function nota(jumlah) {
    let hasil = "",
        char = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
        total = char.length;
    for (var r = 0; r < jumlah; r++) hasil += char.charAt(Math.floor(Math.random() * total));
    return 'IN#'+hasil
}

function bayarCetak() {
    isCetak = true
}

function bayar() {
    isCetak = false
}

function remove(a) {
    Swal.fire({
        title: "Hapus",
        text: "Hapus data ini?",
        type: "warning",
        showCancelButton: !0
    }).then((e) => {
        e.value && $.ajax({
            url: deleteUrl,
            type: "post",
            dataType: "json",
            data: {
                id: a
            },
            success: () => {
                Swal.fire("Sukses", "Proses Hapus dalam Persetujuan", "success"), reloadTable()
            },
            error: a => {
                console.log(a)
            }
        })
    }, function(dismiss){
                console.log(dismiss)
    });
}












































