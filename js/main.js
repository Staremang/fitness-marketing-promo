$(document).ready(function () {

	$("[data-fancybox]").fancybox({
		margin: [0, 0]
	});

	$('input[type=tel]').inputmask({
		'mask': '+7 (999) 999-99-99'
	})

	$('input[type=email]').inputmask({
		'alias': 'email'
	})



	$('form').on('submit', function (e) {
		e.preventDefault();

		var form = $(this),
			data = $(this).serialize(),
			id = $(this).attr('id'),
			submitBtn = $(this).find('button[type="submit"]'),
			submitBtnText = submitBtn.text();




		$.ajax({
			type: "POST",
			url: '/mail.php',
			data: data,
			beforeSend: function () {
				submitBtn.attr('disabled', '');
				submitBtn.text('Отправка...');
			},
			error: function (error) {
				alert('Ошибка ' + error.status + '. Повторите позднее.');
				submitBtn.removeAttr('disabled');
				submitBtn.text(submitBtnText);
			},
			success: function (data) {
				submitBtn.removeAttr('disabled');
				submitBtn.text(submitBtnText);

				data = JSON.parse(data);

				var targetName = '';

				if (data.sended) {
					switch (id) {
						case 'call-me':

							targetName = 'gostevoi';
							$.fancybox.close();
							$.fancybox.open($('#thanks'));
							break;

						case 'anketa':
							targetName = 'gostevoi';
							$.fancybox.close();
							$.fancybox.open($('#thanks-2'));
							break;

					}

					// if (typeof yaCounter49821397 != 'undefined') {
					// 	yaCounter49821397.reachGoal(targetName);
					// } 
					// gtag('event', 'sendforms', { 'event_category': 'zayavka', 'event_action': 'podtverdit'});

				} else {
					alert(data.message);
				}

			}
		});
	});

})
