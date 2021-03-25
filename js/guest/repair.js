function searchDate() {
    var x = document.getElementById("date_from").value
    var y = document.getElementById("date_to").value
    if (x != "" && y != "") {
        location.href = `index.php?from=${x}&to=${y}`
    } else {
        alert("กรุณาระบุวันที่ที่ต้องการค้นหา")
    }

}

function searchCheck(id) {
    var check = document.getElementById(id)
    var success = document.getElementById("success")
    var inprogress = document.getElementById("inprogress")
    var pending = document.getElementById("pending")

    success.checked = false
    inprogress.checked = false
    pending.checked = false
    check.checked = true
    if (id != "all") {
        location.href = `index.php?Status=${check.id}`
    } else {
        location.href = "index.php"
    }

}

function searchCheck2(from, to, id) {
    var check = document.getElementById(id)
    var success = document.getElementById("success")
    var inprogress = document.getElementById("inprogress")
    var pending = document.getElementById("pending")

    success.checked = false
    inprogress.checked = false
    pending.checked = false
    check.checked = true
    if (id != "all") {
        location.href = `index.php?from=${from}&to=${to}&Status=${check.id}`
    } else {
        location.href = `index.php?from=${from}&to=${to}`
    }

}


function unCheckAll() {
    var success = document.getElementById("success")
    var inprogress = document.getElementById("inprogress")
    var pending = document.getElementById("pending")
    success.checked = false
    inprogress.checked = false
    pending.checked = false
    location.href = "index.php"
}

function repair_del(id) {
    if (confirm('คุณต้องการลบรายการแจ้งซ่อมนี้ใช่หรือไม่ ?')) {
        location.href = `function/repairDel.php?repair_id=${id}`
    }
}

///sort table////
const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;

const comparer = (idx, asc) => (a, b) => ((v1, v2) =>
    v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
)(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));

// do the work...
document.querySelectorAll('th').forEach(th => th.addEventListener('click', (() => {
    const table = th.closest('table');
    Array.from(table.querySelectorAll('tr:nth-child(n+2)'))
        .sort(comparer(Array.from(th.parentNode.children).indexOf(th), this.asc = !this.asc))
        .forEach(tr => table.appendChild(tr));
})));

function formatDate(date) {
    var monthNames = [
        "ม.ค.", "ก.พ.", "มี.ค.",
        "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.",
        "ส.ค.", "ก.ย.", "ต.ค.",
        "พ.ค.", "ธ.ค."
    ];
    var day = date.getDate();
    var monthIndex = date.getMonth();
    var year = date.getFullYear();
    return day + ' ' + monthNames[monthIndex] + ' ' + year;
}

$(document).ready(function () {
    let date_from = $("#date_from")
    let date_to = $("#date_to")
    let from_date = $("#from_date")
    let to_date = $("#to_date")
    if (date_from.val() != "" && date_to.val() != "") {
        from_date.html(formatDate(new Date(date_from.val())))
        to_date.html(formatDate(new Date(date_to.val())))
    }

    $('.roundtrip-input').dateDropper({
        roundtrip: true,
        theme: "my-style",
        lang: "th",
        format: "Y-m-d",
        lock: "to",
        startFromMonday: false,
    });

    $('#date_from').change(function () {
        $('#from_date').html(formatDate(new Date($('#date_from').val())))
    })

    $('#date_to').change(function () {
        $('#to_date').html(formatDate(new Date($('#date_to').val())))
    })
})