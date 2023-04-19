<html>

<body>
	<p>{{ $bank->user->name }}!</p>
	<p></p>
	<p>
		{{ __('invoices.notification.mail_top') }}
	</p>
	<p></p>
	<b>
		{{ __('bank.credit_card') . ': ' . $inv_create->bank->name }}
		<br>
		{{ __('invoices.value_open') . ': R$ ' . $inv_create->value }}
		<br>
		{{ __('bank.due_date') . ': ' . $inv_create->due_date->format('d/m/Y') }}
	</b>
	<p></p>
	<p>
		<i>{{ __('invoices.notification.mail_botton') }}</i>
	</p>
</body>

</html>
