<?php

declare(strict_types=1);

namespace Fina\Sdk\Laravel\Tests\Unit;

use Carbon\CarbonImmutable;
use Fina\Sdk\Laravel\Exceptions\FinaValidationException;
use Fina\Sdk\Laravel\Operation\Dto\BonusCardPayload;
use Fina\Sdk\Laravel\Operation\Dto\GiftPaymentPayload;
use Fina\Sdk\Laravel\Operation\Dto\Production\ProductionChildLine;
use Fina\Sdk\Laravel\Operation\Dto\Production\ProductionProductLine;
use Fina\Sdk\Laravel\Operation\Dto\Production\SaveProductionPayload;
use Fina\Sdk\Laravel\Operation\Dto\ReceivedServicePayload;
use Fina\Sdk\Laravel\Operation\Dto\ServiceLine;
use Fina\Sdk\Laravel\Support\PayloadValidator;
use Fina\Sdk\Laravel\Tests\TestCase;

final class NewDtoValidationTest extends TestCase
{
    public function test_valid_bonus_card_payload_passes(): void
    {
        $payload = new BonusCardPayload(
            id: 0,
            date: CarbonImmutable::parse('2024-01-01'),
            numPrefix: '',
            num: 0,
            purpose: 'Bonus card issuance',
            customer: 31,
            store: 1,
            user: 1,
            cardCode: '231',
            personCode: '0100010101',
            personName: 'Test User',
            personAddress: 'Address',
            personTel: '+995597222222',
            status: true,
        );

        PayloadValidator::validate($payload);
        $this->assertArrayHasKey('card_code', $payload->toArray());
        $this->assertSame('231', $payload->toArray()['card_code']);
    }

    public function test_bonus_card_without_card_code_fails(): void
    {
        $payload = new BonusCardPayload(
            id: 0,
            date: CarbonImmutable::parse('2024-01-01'),
            numPrefix: '',
            num: 0,
            purpose: 'Test',
            customer: 1,
            store: 1,
            user: 1,
            cardCode: '',
            personCode: '0100010101',
            personName: 'Test',
            personAddress: '',
            personTel: '',
            status: true,
        );

        $this->expectException(FinaValidationException::class);
        PayloadValidator::validate($payload);
    }

    public function test_valid_gift_payment_payload_passes(): void
    {
        $payload = new GiftPaymentPayload(
            date: CarbonImmutable::parse('2024-05-22'),
            cardId: 100009,
            numPrefix: '',
            num: 0,
            purpose: 'Gift card payment',
            amount: 20.4,
            store: 1,
            user: 1,
            staff: 3,
            project: 2,
            customer: 8,
            refId: 133,
            makeEntry: true,
        );

        PayloadValidator::validate($payload);
        $this->assertSame(100009, $payload->toArray()['card_id']);
    }

    public function test_gift_payment_with_zero_amount_fails(): void
    {
        $payload = new GiftPaymentPayload(
            date: CarbonImmutable::parse('2024-05-22'),
            cardId: 100009,
            numPrefix: '',
            num: 0,
            purpose: 'Test',
            amount: 0.0,
            store: 1,
            user: 1,
            staff: 0,
            project: 0,
            customer: 8,
            refId: 0,
            makeEntry: true,
        );

        $this->expectException(FinaValidationException::class);
        PayloadValidator::validate($payload);
    }

    public function test_valid_received_service_payload_passes(): void
    {
        $payload = new ReceivedServicePayload(
            id: 0,
            date: CarbonImmutable::parse('2024-01-01'),
            numPrefix: '',
            num: 0,
            purpose: 'Service received',
            amount: 2.0,
            currency: 'GEL',
            rate: 1.0,
            user: 2,
            project: 2,
            vendor: 3,
            isVat: true,
            makeEntry: true,
            payType: 1,
            services: [new ServiceLine(id: 2, quantity: 1.0, price: 2.0)],
        );

        PayloadValidator::validate($payload);
        $arr = $payload->toArray();
        $this->assertSame(3, $arr['vendor']);
        $this->assertCount(1, $arr['services']);
    }

    public function test_received_service_without_services_fails(): void
    {
        $payload = new ReceivedServicePayload(
            id: 0,
            date: CarbonImmutable::parse('2024-01-01'),
            numPrefix: '',
            num: 0,
            purpose: 'Test',
            amount: 2.0,
            currency: 'GEL',
            rate: 1.0,
            user: 2,
            project: 2,
            vendor: 3,
            isVat: true,
            makeEntry: true,
            payType: 1,
            services: [],
        );

        $this->expectException(FinaValidationException::class);
        PayloadValidator::validate($payload);
    }

    public function test_valid_save_production_payload_passes(): void
    {
        $payload = new SaveProductionPayload(
            id: 0,
            date: CarbonImmutable::parse('2026-03-03'),
            numPrefix: '',
            num: 0,
            purpose: 'Production',
            type: 0,
            store: 1,
            user: 2,
            staff: 3,
            makeEntry: true,
            products: [
                new ProductionProductLine(
                    id: 107,
                    subId: 0,
                    quantity: 1.0,
                    childProducts: [
                        new ProductionChildLine(id: 27, subId: 0, quantity: 2.0, price: 0.0),
                        new ProductionChildLine(id: 28, subId: 0, quantity: 3.0, price: 0.0),
                    ]
                ),
            ],
        );

        PayloadValidator::validate($payload);
        $arr = $payload->toArray();
        $this->assertSame(0, $arr['type']);
        $this->assertCount(1, $arr['products']);
        $this->assertCount(2, $arr['products'][0]['child_products']);
    }

    public function test_save_production_without_products_fails(): void
    {
        $payload = new SaveProductionPayload(
            id: 0,
            date: CarbonImmutable::parse('2026-03-03'),
            numPrefix: '',
            num: 0,
            purpose: 'Test',
            type: 0,
            store: 1,
            user: 2,
            staff: 3,
            makeEntry: true,
            products: [],
        );

        $this->expectException(FinaValidationException::class);
        PayloadValidator::validate($payload);
    }
}
