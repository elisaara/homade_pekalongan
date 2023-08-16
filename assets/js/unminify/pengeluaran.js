let isCetak = false,
	url,
    pengeluaran = $("#pengeluaran").DataTable({
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
        data: "nama_jenis"
    }, {
        data: "deskripsi"
    }, {
        data: "jumlah"
    }, {
        data: "action"
    }]
});

function reloadTable() {
    pengeluaran.ajax.reload()
}

function addData() {
    $.ajax({
        url: addUrl,
        type: "post",
        dataType: "json",
        data: $("#form").serialize(),
        success: res => {
            $(".modal").modal("hide");
            Swal.fire("Sukses", "Sukses Menambahkan Data", "success");
            reloadTable();
        },
        error: res => {
            console.log(res);
        }
    })
}

function editData() {
    $.ajax({
        url: editUrl,
        type: "post",
        dataType: "json",
        data: $("#form").serialize(),
        success: () => {
            $(".modal").modal("hide");
            Swal.fire("Sukses", "Proses Edit dalam Persetujuan", "success");
            reloadTable();
        },
        error: err => {
            console.log(err)
        }
    })
}
function edit(id) {
    $.ajax({
        url: getDataUrl,
        type: "post",
        dataType: "json",
        data: {
            id: id
        },
        success: res => {
            $('[name="id"]').val(res.id);
            $('[name="jenis"]').val(res.jenis);
            $('[name="jumlah"]').val(res.jumlah);
            $('[name="deskripsi"]').val(res.deskripsi);
            $(".modal").modal("show");
            $(".modal-title").html("Edit Pengeluaran");
            $('.modal button[type="submit"]').html("Simpan");
            url = "edit";
            let tgl = moment(res.tanggal).format("D-MM-Y H:mm:ss");
            $('[name="tanggal"]').val(tgl);
            $('[name="nota"]').val(res.nota);
        },
        error: err => {
            console.log(err)
        }
    });
}

function nota(jumlah) {
    let hasil = "",
        char = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
        total = char.length;
    for (var r = 0; r < jumlah; r++) hasil += char.charAt(Math.floor(Math.random() * total));
    return 'OUT#'+hasil
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

function add() {
    url = "add";
    $(".modal-title").html("Tambah Pengeluaran");
    $('.modal button[type="submit"]').html("Simpan");
    let now = moment().format("D-MM-Y H:mm:ss");
    $("#tanggal").val(now);
    $("#nota").val(nota(15));
    
}
$(".modal").on("show.bs.modal", () => {
    $("#tanggal").datetimepicker({
        format: "dd-mm-yyyy h:ii:ss"
    });
});

$("#form").validate({
    errorElement: "span",
    errorPlacement: (err, el) => {
        err.addClass("invalid-feedback");
        el.closest(".form-group").append(err)
    },
    submitHandler: () => {
        "edit" == url ? editData() : addData()
    }
});
$(".modal").on("hidden.bs.modal", () => {
    $("#form")[0].reset();
    $("#form").validate().resetForm();
});
$("#jenis").select2({
        placeholder: "Jenis"
    });
let now = moment().format("D-MM-Y H:mm:ss");
$("#tanggal").val(now);
$("#nota").val(nota(15));
