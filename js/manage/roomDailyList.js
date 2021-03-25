function editType(room) {
    var typeShow = document.getElementById(`typeShow${room.toString()}`)
    var typeEdit = document.getElementById(`typeEdit${room.toString()}`)
    var typeShow_btn = document.getElementById(`typeShow-btn${room.toString()}`)
    var typeEdit_btn = document.getElementById(`typeEdit-btn${room.toString()}`)
    if (typeShow.style.display == '') {
        typeShow.style.display = 'none'
        typeEdit.style.display = ''
        typeShow_btn.style.display = 'none'
        typeEdit_btn.style.display = ''
    } else {
        typeShow.style.display = ''
        typeEdit.style.display = 'none'
        typeShow_btn.style.display = ''
        typeEdit_btn.style.display = 'none'
    }
}

function acceptEdit(room) {
    var typeShow = document.getElementById(`typeShow${room.toString()}`)
    var typeEdit = document.getElementById(`typeEdit${room.toString()}`)
    var typeShow_btn = document.getElementById(`typeShow-btn${room.toString()}`)
    var typeEdit_btn = document.getElementById(`typeEdit-btn${room.toString()}`)
    typeShow.style.display = ''
    typeEdit.style.display = 'none'
    typeShow_btn.style.display = ''
    typeEdit_btn.style.display = 'none'
}

function cancelEdit(room) {
    var typeShow = document.getElementById(`typeShow${room.toString()}`)
    var typeEdit = document.getElementById(`typeEdit${room.toString()}`)
    var typeShow_btn = document.getElementById(`typeShow-btn${room.toString()}`)
    var typeEdit_btn = document.getElementById(`typeEdit-btn${room.toString()}`)
    typeShow.style.display = ''
    typeEdit.style.display = 'none'
    typeShow_btn.style.display = ''
    typeEdit_btn.style.display = 'none'
}

function addRoom(){
    var add = document.getElementById("addRoom")
    if(add.style.display == ''){
        add.style.display = 'flex'
    }else{
        add.style.display = ''
    }
}

function del(room){
    if(confirm(`คุณต้องการลบห้อง ${room} ใช่หรือไม่ ?`)){
        location.href = `function/delRoom.php?ID=${room}`
    }
}