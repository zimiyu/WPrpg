// rpg-script.js
jQuery(document).ready(function($) {
    function generatePassword(length, lowercase, uppercase, numbers, symbols) {
        var charset = '';
        var password = '';

        if (lowercase) charset += 'abcdefghijklmnopqrstuvwxyz';
        if (uppercase) charset += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if (numbers) charset += '0123456789';
        if (symbols) charset += '!@#$%^&*()_+~`|}{[]\:;?><,./-=';

        for (var i = 0; i < length; i++) {
            password += charset.charAt(Math.floor(Math.random() * charset.length));
        }

        return password;
    }

    $('#rpg-generate').click(function() {
        var length = $('#rpg-length').val();
        var lowercase = $('#rpg-lowercase').is(':checked');
        var uppercase = $('#rpg-uppercase').is(':checked');
        var numbers = $('#rpg-numbers').is(':checked');
        var symbols = $('#rpg-symbols').is(':checked');

        var password = generatePassword(length, lowercase, uppercase, numbers, symbols);
        $('#rpg-password').val(password);
    });

    $('#rpg-copy').click(function() {
        $('#rpg-password').select();
        document.execCommand('copy');
        alert('密码已复制到剪切板！');
    });
});
