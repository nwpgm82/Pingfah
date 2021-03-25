function delImg(id,name){
    if(confirm("คุณต้องการลบรูปภาพนี้ใช่หรือไม่ ?")){
        location.href = `function/delImage.php?daily_id=${id}&name=${name}`
    }
}
// $(document).ready(function(){
//     $("#edit").click(function(){
//         let inputs = $("input")
//         inputs.each(function(i){
//             if(i == 0 || i== 7 || i == 8 || i == 9 || i == 13){
//                 return
//             }else{
//                 $(this).prop("disabled",false)
//             }
//         })
//         $("#edit").hide()
//         $("#edit-option").css("display","flex")
//     })
//     $("#cancel-edit").click(function(){
//         let inputs = $("input")
//         inputs.each(function(i){
//             if(i == 0 || i== 7 || i == 8 || i == 9 || i == 13){
//                 return
//             }else{
//                 $(this).prop("disabled",true)
//             }
//         })
//         $("#edit").show()
//         $("#edit-option").hide()
//     })
// })