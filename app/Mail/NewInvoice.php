<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewInvoice extends Mailable
{
    use Queueable, SerializesModels;
    public $bank;
    public $inv_create;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($bank, $inv_create)
    {
        //
        $this->bank = $bank;
        $this->inv_create = $inv_create;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //$user_info = "Teste User";

        return $this->from('no-reply@moneymap.com', 'MoneyMAP')
            ->cc(User::where('group_id', $this->bank->user->group_id)->pluck('email'))
            ->view('emails.invoices.newinvoice')
            ->subject(__('invoices.notification.new_invoice_subject'))
            ->with([
                'bank' => $this->bank,
                'inv_create' => $this->inv_create
            ]);
    }
}
