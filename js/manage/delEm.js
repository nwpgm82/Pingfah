$(document).ready(function(){
    $(".del-btn").click(function(event){
        if(confirm("คุณต้องการลบข้อมูลใช่หรือไม่ ?")){
            location.href = `function/delEm.php?employee_id=${event.target.id}`
        }else{
            event.preventDefault()
        }
        
    })
})