<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ajax</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>
    <div class="modal" tabindex="-1" role="dialog" id="modal_frm">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">User Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" id=frm>
       <input type="hidden" name="action" id="action" value="Insert">
       <input type="hidden" name="id" id="uid" value="0">
     <div class="form-group">
        <label>Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>

    <div class="form-group">
    <label>Gender</label>
        <select name="gender" id="gender" class="form-control" required>
            <option value="">Select</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Others">Others</option>
        </select>
    </div>

    <div class="form-group">
        <label>Contact</label>
        <input type="text" class="form-control" id="contact" name="contact" required>
    </div>

<input type="submit" value="submit" class="btn btn-success">
        </form>
      </div>  
    </div>
  </div>
</div>

    <div class="container mt-5">
       <p class="text-right"><a href="#" class="btn btn-success" id="add_record">Add Record</a></p>

<table class="table table-bordered">
<thead>
    <th>Name</th>
    <th>Gender</th>
    <th>Contact</th>
    <th>Edit</th>
    <th>Delete</th>
</thead>
<tbody id="tbody">
<?php
$con=mysqli_connect("localhost","root","aarthi@17","ajax_crud");
$sql="Select * from data";
$res=$con->query($sql);
while($row=$res->fetch_assoc()){
    echo "
    <tr uid='{$row["id"]}'>
    <td>{$row["Name"]}</td>
    <td>{$row["Gender"]}</td>
    <td>{$row["Contact"]}</td>
    <td><a href ='#' class='btn btn-primary edit'>Edit</a></td>
    <td><a href ='#' class='btn btn-danger delete'>Delete</a></td>
    </tr>
    ";
}
?>
</tbody>
</table>
</div>

    <script>
$(document).ready(function(){

     var current_row=null;
     $("#add_record").click(function(){
 
        $("#modal_frm").modal();
     });

        $('#frm').submit(function(event){
         event.preventDefault();
           $.ajax({
           url:"ajax_action.php",
           type:"post",
           data:$("#frm").serialize(),
           beforeSend:function(){
            $("#frm").find("input[type='submit']").val('Loading....');
           },
           success:function(res){
              if(res){
                if($('#uid').val()=="0"){
              $("#tbody").append(res);
            }else{
                $(current_row).html(res);
            }
              }else{
                alert("Failed try again");
              }
            $("#frm").find("input[type='submit']").val('Submit');
            clear_input();
            $("#modal_frm").modal('hide');

           }
           });

        });

          $("body").on("click",".edit",function(event){
             event.preventDefault();
             current_row=$(this).closest("tr");
             $("#modal_frm").modal();
            var id=$(this).closest("tr").attr("uid");
            var name=$(this).closest("tr").find("td:eq(0)").text();
            var gender=$(this).closest("tr").find("td:eq(1)").text();
            var contact=$(this).closest("tr").find("td:eq(2)").text();

            $("#action").val("Update");
            $("#uid").val(id);
            $("#name").val(name);
            $("#gender").val(gender);
            $("#contact").val(contact);

          });

          $("body").on("click",".delete",function(event){
            event.preventDefault();
            var id=$(this).closest("tr").attr("uid");
            var cls=$(this);
            if(confirm("Are you sure")){
            $.ajax({
           url:"ajax_action.php",
           type:"post",
           data:{uid:id,action:'Delete'},
           beforeSend:function(){
            $(cls).text("Loading...");
           },
           success:function(res){
             if(res){
                $(cls).closest("tr").remove();
             }else{
                alert("Failled try again");
                $(cls).text("Try again");

             }

           }
           });
        }
          });

        function clear_input(){
            $("#frm").find(".form-control").val("");
            $("#action").val("Insert");
            $("#uid").val("0");

        }
});
    </script>

</body>
</html>