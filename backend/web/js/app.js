$(function() {
});

$('.order-user-id').on('click', function(){
    var id = $(this).data('order-id');
    getOrderInfo(id, function(result){
        $('#myModal .modal-body').html(result);
    });
});
function getOrderInfo(id, callback){
    $.ajax({
        url: "/order/order-info",
        type: "POST",       
        data: {id: id},
        success: function(result){   
            callback(result);
        }
    }); 
}