$(document).ready(function() {
    $('form').on('submit', function(e) {
        e.preventDefault();

        var name = $('input[name="name"]').val();
        var address = $('input[name="address"]').val();
        var phone = $('input[name="phone"]').val();
        var email = $('input[name="email"]').val();

        $.ajax({
            url: 'add_user.php',
            type: 'POST',
            data: {
                name: name,
                address: address,
                phone: phone,
                email: email
            },
            success: function(response) {
                alert(response);  // Hiển thị thông báo thành công hoặc lỗi
                if (response.includes('Khách hàng đã được thêm thành công')) {
                    $('form')[0].reset();  // Làm mới form sau khi thêm khách hàng
                }
            }
        });
    });
});
