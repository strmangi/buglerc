<?php



namespace App\Mail;



use Illuminate\Bus\Queueable;

use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Mail\Mailable;

use Illuminate\Queue\SerializesModels;



class SendConfirmation extends Mailable

{

    use Queueable, SerializesModels;

    public $data;

    /**

     * Create a new message instance.

     *

     * @return void

     */

    public function __construct($data)

    {

        $this->data = $data;

    }



    /**

     * Build the message.

     *

     * @return $this

     */

    public function build()

    {

         return $this->from('BuglerCoaches@support.com')->subject('Bugler Trip Confirmation')->view('mail.confirmation_mail_temp')->with('data', $this->data);

    }

}

