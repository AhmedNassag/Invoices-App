<?php

use App\Invoice;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $invoice = Invoice::create([
            'invoice_number' => '20111993',
            'invoice_date' => '1993-11-20',
            'due_date' => '2022-08-01',
            'product' => 'Product',
            'section_id' => 1,
            'amount_collection' => 500000,
            'amount_commission' => 5000.00,
            'discount' => 0,
            'rate_vat' => '10%',
            'value_vat' => 500,
            'total' => 5500,
            'status' => 'مدفوعة',
            'value_status' => 1,
            'note' => 'Invoice Note',
            'payment_date' => '2022-05-14',
        ]);
    }
}
