// Пример JavaScript для добавления интерактивности
jQuery(document).ready(function($) {
    $('.product-color').on('click', function() {
        $('.product-color').removeClass('selected');
        $(this).addClass('selected');
        // Логика смены цвета продукта
    });


    $('.product').hover(

        function () {
            console.log('i see hover');
          $(this).closest('.add-to-cart').addClass("active");
        },
        function () {
            console.log('i see of hover');
          $(this).closest('.add-to-cart').removeClass("active");
        }
      );
});
