<?php

use App\Http\Controllers\BankingController;

$app->get('/ping', [BankingController::class, 'ping']);
$app->post('/reset', [BankingController::class, 'reset']);
$app->get('/balance', [BankingController::class, 'balance']);
$app->post('/event', [BankingController::class, 'event']);
