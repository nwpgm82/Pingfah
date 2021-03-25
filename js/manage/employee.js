$(document).ready(function(){
    $("#search_em").click(function(){
        if($("#firstname").val() != "" && $("#lastname").val() == ""){
            location.href = `index.php?firstname=${$("#firstname").val()}`
        }else if($("#firstname").val() == "" && $("#lastname").val() != ""){
            location.href = `index.php?lastname=${$("#lastname").val()}`
        }else if($("#firstname").val() != "" && $("#lastname").val() != ""){
            location.href = `index.php?firstname=${$("#firstname").val()}&lastname=${$("#lastname").val()}`
        }else{
            $("#firstname").css("border-color","red")
            $("#firstname").addClass("placeholder-error")
            $("#lastname").css("border-color","red")
            $("#lastname").addClass("placeholder-error")
            $("#input_error").html("โปรดระบุข้อความที่ต้องการค้นหาช่องใดช่องหนึ่ง")
        }
    })
    $("input").keyup(function(){
        if($("#firstname").val() != "" || $("#lastname").val() != ""){
            $("#firstname").css("border-color","")
            $("#firstname").removeClass("placeholder-error")
            $("#lastname").css("border-color","")
            $("#lastname").removeClass("placeholder-error")
            $("#input_error").html("")
        }else{
            $("#firstname").css("border-color","red")
            $("#firstname").addClass("placeholder-error")
            $("#lastname").css("border-color","red")
            $("#lastname").addClass("placeholder-error")
            $("#input_error").html("โปรดระบุข้อความที่ต้องการค้นหาช่องใดช่องหนึ่ง")
        }
    })
    $(".del-btn").click(function(event){
        if(confirm("คุณต้องการลบข้อมูลใช่หรือไม่ ?")){
            location.href = `function/delEm.php?employee_id=${event.target.id}`
        }else{
            event.preventDefault()
        }
        
    })
    $("#all").click(function(){
        if($("#firstname").val() != "" && $("#lastname").val() == ""){
            location.href = `index.php?firstname=${$("#firstname").val()}`
        }else if($("#firstname").val() == "" && $("#lastname").val() != ""){
            location.href = `index.php?lastname=${$("#lastname").val()}`
        }else if($("#firstname").val() != "" && $("#lastname").val() != ""){
            location.href = `index.php?firstname=${$("#firstname").val()}&lastname=${$("#lastname").val()}`
        }else{
            location.href = "index.php"
        }
    })
    $("#c_admin").click(function(){
        if($("#firstname").val() != "" && $("#lastname").val() == ""){
            location.href = `index.php?firstname=${$("#firstname").val()}&position=admin`
        }else if($("#firstname").val() == "" && $("#lastname").val() != ""){
            location.href = `index.php?lastname=${$("#lastname").val()}&position=admin`
        }else if($("#firstname").val() != "" && $("#lastname").val() != ""){
            location.href = `index.php?firstname=${$("#firstname").val()}&lastname=${$("#lastname").val()}&position=admin`
        }else{
            location.href = "index.php?position=admin"
        }
    })
    $("#c_employee").click(function(){
        if($("#firstname").val() != "" && $("#lastname").val() == ""){
            location.href = `index.php?firstname=${$("#firstname").val()}&position=employee`
        }else if($("#firstname").val() == "" && $("#lastname").val() != ""){
            location.href = `index.php?lastname=${$("#lastname").val()}&position=employee`
        }else if($("#firstname").val() != "" && $("#lastname").val() != ""){
            location.href = `index.php?firstname=${$("#firstname").val()}&lastname=${$("#lastname").val()}&position=employee`
        }else{
            location.href = "index.php?position=employee"
        }
    })
})