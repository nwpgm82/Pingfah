function searchDate(){
    var x = document.getElementById("date_from").value
    var y = document.getElementById("date_to").value
    if(x != "" && y != ""){
       location.assign(`index.php?from=${x}&to=${y}`) 
    }else{
        alert("กรุณาระบุเดือนที่ต้องการค้นหา")
    }
    
}

function confirmStatus(room,date){
    if(confirm(`ยืนยันการชำระเงินห้อง ${room}`)){
        location.href = `function/confirmStatus.php?room_id=${room}&date=${date}`
    }
}

function searchCheck(id){
    var check = document.getElementById(id)
    var success = document.getElementById("success")
    var unsuccess = document.getElementById("unsuccess")

    success.checked = false
    unsuccess.checked = false 
    check.checked = true
    if(id != "all"){
       location.href = `index.php?Status=${check.id}` 
    }else{
        location.href = "index.php"
    }
    
}

function searchCheck2(from,to,id){
    var check = document.getElementById(id)
    var success = document.getElementById("success")
    var unsuccess = document.getElementById("unsuccess")

    success.checked = false
    unsuccess.checked = false 
    check.checked = true
    if(id != "all"){
       location.href = `index.php?from=${from}&to=${to}&Status=${check.id}` 
    }else{
        location.href = `index.php?from=${from}&to=${to}`
    }
    
}


function unCheckAll(){
    var success = document.getElementById("success")
    var unsuccess = document.getElementById("unsuccess")
    success.checked = false
    unsuccess.checked = false
    location.href = "index.php"
}

function delcost(room,date){
    if(confirm("คุณต้องการลบรายการชำระเงินนี้ใช่หรือไม่ ?")){
        location.href = `function/delcostData.php?room_id=${room}&date=${date}`
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
        .forEach(tr => table.appendChild(tr) );
})));

function formatDate(date) {
    var monthNames = [
        "ม.ค.", "ก.พ.", "มี.ค.",
        "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.",
        "ส.ค.", "ก.ย.", "ต.ค.",
        "พ.ค.", "ธ.ค."
    ];
    var monthIndex = date.getMonth();
    var year = date.getFullYear();
    return monthNames[monthIndex] + ' ' + year;
}

function formatDate2(inputDate) {
    var date = new Date(inputDate);
    if (!isNaN(date.getTime())) {
        // Months use 0 index.
        return date.getMonth() + 1 + '/1/' + date.getFullYear();
    }
}

$(document).ready(function () {
    let from = $("#date_from")
    let to = $("#date_to")
    let from_date = $("#from_date")
    let to_date = $("#to_date")
    if(from.val() != "" && to.val() != ""){
        from_date.html(formatDate(new Date(from.val())))
        to_date.html(formatDate(new Date(to.val())))
    }
    
    $('#date_from').dateDropper({
        // roundtrip: true,
        theme: "my-style",
        lang: "th",
        format: "Y-m",
        lock: "to",
        hideDay: true,
        hideMonth: false,
        hideYear: false,
        startFromMonday: false,
    });

    $('#date_to').dateDropper({
        // roundtrip: true,
        theme: "my-style",
        lang: "th",
        format: "Y-m",
        lock: "to",
        hideDay: true,
        hideMonth: false,
        hideYear: false,
        startFromMonday: false,
        // minDate: formatDate2($('#date_from').val())
    });

    $('#date_from').change(function () {
        $('#from_date').html(formatDate(new Date($('#date_from').val())))
        $('#date_to').dateDropper('set', {
            minDate: formatDate2($('#date_from').val())
        });
    })

    $('#date_to').change(function () {
        $('#to_date').html(formatDate(new Date($('#date_to').val())))
    })
})
