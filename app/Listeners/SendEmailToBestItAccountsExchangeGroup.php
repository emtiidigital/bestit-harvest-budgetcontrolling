<?php

namespace App\Listeners;

use App\Events\Generators\File\ExcelFileCreatedEvent;
use Illuminate\Support\Facades\Mail;

/**
 * Class SendEmailToBestItAccountsExchangeGroup
 * @author Marcel Thiesies <marcel.thiesies@bestit-online.de>
 * @package App\Listeners
 */
class SendEmailToBestItAccountsExchangeGroup
{
    const ACCOUNTS_MAIL = 'accounts@bestit-online.de';

    /**
     * Handle the event. Main entry point for this listener.
     *
     * @param ExcelFileCreatedEvent $event
     * @return void
     */
    public function handle(ExcelFileCreatedEvent $event)
    {
        // get filename of excel file
        $fileName = $event->getFileName();

        // purpose of listener
        $this->sendReportAsEmailToAccountsExchangeGroup($fileName);
    }

    private function sendReportAsEmailToAccountsExchangeGroup($fileName = null)
    {
        if ($fileName !== null) {
            $filePath = $this->getFilePathWithFileName($fileName);

            info('send mail for: ' . $filePath);

            Mail::send('mails.customer_project_report', [], function ($m) use ($fileName, $filePath) {
                $m->to(self::ACCOUNTS_MAIL);
                $m->subject('New Customer Report generated: . ' . $fileName);
                if ($filePath !== null) {
                    $m->attach($filePath);
                }
            });

            info('mail sent.');
        }
    }

    private function getFilePathWithFileName($fileName)
    {
        return storage_path('exports/' . $fileName);
    }
}
