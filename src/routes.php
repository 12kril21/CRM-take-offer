<?php
declare(strict_types=1);

use Slim\App;
use App\Controllers\LeadController;

return function(App $app) {
    $app->get('/api/leads', [LeadController::class, 'index']);
    $app->post('/api/leads', [LeadController::class, 'store']);
    $app->get('/api/leads/{id}', [LeadController::class, 'show']);
    $app->put('/api/leads/{id}', [LeadController::class, 'update']);
    $app->delete('/api/leads/{id}', [LeadController::class, 'delete']);
};
