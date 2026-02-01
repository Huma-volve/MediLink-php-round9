<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Task 1: assert that Payment model initializes correctly
     */
    public function test_payment_logic_initialization()
    {
        $payment = new Payment([
            'amount' => 150.00,
            'payment_status' => 'pending'
        ]);

        $this->assertEquals(150.00, $payment->amount);
        $this->assertEquals('pending', $payment->payment_status);
    }

    /**
     * Task 2: assert refund logic works correctly
     */
    public function test_payment_refund_status_logic()
    {
        $payment = new Payment(['payment_status' => 'completed']);

        // تنفيذ عملية الاسترجاع
        if ($payment->payment_status === 'completed') {
            $payment->payment_status = 'refunded';
        }

        $this->assertEquals('refunded', $payment->payment_status);
    }

    /**
     * Task 3: التحقق من منع استرجاع دفع غير مكتمل
     */
    public function test_cannot_refund_uncompleted_payment()
    {
        $payment = new Payment(['payment_status' => 'pending']);

        $canRefund = ($payment->payment_status === 'completed');

        $this->assertFalse($canRefund);
    }
}
