console.log('init ok');

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
            console.log('i see off hover');
            $(this).closest('.add-to-cart').removeClass("active");
        }
    );
});

document.querySelectorAll('.ul-colors li').forEach(item => {
    item.addEventListener('click', function() {
        // Удаляем класс 'active' у всех элементов
        document.querySelectorAll('.ul-colors li').forEach(el => el.classList.remove('active'));
        // Добавляем класс 'active' к текущему элементу
        this.classList.add('active');
    });
});

document.querySelector('.heartBlock').addEventListener('click', function() {
    // Переключаем класс 'active' при каждом клике
    this.classList.toggle('active');
});

document.addEventListener('DOMContentLoaded', function () {
    const productImages = document.querySelectorAll('.product-image');

    productImages.forEach(function (img) {
        const originalSrc = img.src;
        const hoverSrc = img.getAttribute('data-hover-image');

        img.addEventListener('mouseenter', function () {
            img.src = hoverSrc; // Меняем изображение при наведении
        });

        img.addEventListener('mouseleave', function () {
            img.src = originalSrc; // Возвращаем исходное изображение при уходе
        });
    });
});

document.getElementById('emailForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Предотвращаем стандартное поведение формы

    const emailInput = document.getElementById('email');
    const messageDiv = document.getElementById('message');

    // Проверка наличия emailInput и messageDiv
    if (!emailInput || !messageDiv) {
        console.error('Email input or message div is missing.');
        return;
    }

    const email = emailInput.value.trim(); // Удаляем пробелы в начале и конце строки

    // Проверка пустого значения email
    if (!email) {
        messageDiv.innerText = 'Please enter your email address.';
        messageDiv.style.color = 'red';
        return;
    }

    const checkbox = document.getElementById('checkbox');

    // Проверка чекбокса
    if (!checkbox.checked) {
        messageDiv.innerText = 'You must agree to the terms.';
        messageDiv.style.color = 'red';
        return;
    }

    // Валидация email через REGEX
    const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!regex.test(email)) {
        messageDiv.innerText = 'Invalid email address.';
        messageDiv.style.color = 'red';
        return;
    }

    // Очистка сообщения перед отправкой
    messageDiv.innerText = 'Sending...';
    messageDiv.style.color = 'blue';

    // Отправка данных через AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('POST', ajax_object.ajax_url, true); // Используем ajax_url из локализованного объекта
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                messageDiv.innerText = 'Email saved successfully!';
                messageDiv.style.color = 'green';
                emailInput.value = ''; // Очистить поле email после успешной отправки
                checkbox.checked = false; // Снять галочку с чекбокса после успешной отправки
            } else {
                messageDiv.innerText = response.data; // Показываем сообщение об ошибке
                messageDiv.style.color = 'red';
            }
        } else {
            messageDiv.innerText = 'Failed to save email. Please try again.';
            messageDiv.style.color = 'red';
        }
    };
    xhr.onerror = function () {
        messageDiv.innerText = 'An error occurred while sending the request.';
        messageDiv.style.color = 'red';
    };
    xhr.send('action=save_email&email=' + encodeURIComponent(email) + '&nonce=' + ajax_object.nonce); // Добавляем nonce
});

console.log('init finish');
