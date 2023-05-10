<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewRecExpense extends Mailable
{
    use Queueable, SerializesModels;

    public $expense;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($expense)
    {
        $this->expense = $expense;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), 'MoneyMAP')
            ->cc(User::where('group_id', $this->expense->user->group_id)->pluck('email'))
            ->view('emails.expenses.new_topay')
            ->subject(__('expenses.notification.new_expense_subject'))
            ->with([
                'expense' => $this->expense,
            ]);
    }
}
