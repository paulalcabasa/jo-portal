<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Carbon\Carbon;
use App\Models\Approval;
use App\Models\JOLine;

class SendApprovalNotif extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:approval';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    private $email;
    private $password;
    private $host;
    private $port;
    private $from;


    public function __construct()
    {
        parent::__construct();
        $this->email = config('mail.username');
        $this->password = config('mail.password');
        $this->host = config('mail.host');
        $this->port = config('mail.port');
        $this->from = config('mail.from');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $approval = new Approval;
        $approvalList = $approval->getCurrentJOApproval();

        foreach($approvalList as $row){
            
            $mail = new PHPMailer();   
            $mail->SMTPDebug = 0;                                	// Enable verbose debug output
            $mail->isSMTP();     
            $mail->isHTML(true);   
            $mail->CharSet    = "iso-8859-1";                      // Set mailer to use SMTP
            $mail->Host       = $this->host;              // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                              // Enable SMTP authentication
            $mail->Username   = $this->email;           // SMTP username
            $mail->Password   = $this->password;  // SMTP password
            $mail->SMTPSecure = 'tls';                             // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = $this->port;                               // TCP port to connect to
            //Recipients
            $mail->setFrom($this->from['address'], $this->from['name']);

            $lines =  JOLine::where('job_header_id', $row->job_order_id)->get();
            $data = [
                'header' => $row,
                'lines' => $lines,
                'confirm_api' => url('/') . '/api/job-order/approve/' . $row->approval_id,
                'reject_url' => url('/') . '/api/job-order/reject-form/' . $row->approval_id,
            ];

            //$mail->addAddress($row->email_address, $row->approver_name);
            
            $mail->addAddress('paul-alcabasa@isuzuphil.com', 'Paul');
            $mail->Subject = 'System Notification : Job Order Portal';
            $mail->Body = view('jo-approval', $data); // . $row->email_address;
            if($mail->send()){
                echo "mail sent";
                $approvalFlag = Approval::findOrFail($row->approval_id);
                $approvalFlag->date_sent = Carbon::now();
                $approvalFlag->save();
                \Log::info("Mail sent");
                
            }
            else {
                echo "mail not sent";
                \Log::info("Mail not sent");
                
            }
        }
    }
}
