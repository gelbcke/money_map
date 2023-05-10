<html>

<body>
	<p>{{ $expense->user->name }}!</p>
	<p></p>
	<p>
		{{ __('expenses.notification.mail_top') }}
	</p>
	<p></p>
	<b>
		{{ __('general.details') . ': ' . $expense->details }}
		<br>
		{{ __('general.value') . ': R$ ' . $expense->value }}
	</b>
</body>

</html>
