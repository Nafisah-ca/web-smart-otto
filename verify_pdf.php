<?php
// Verification script — checks PDF package + route registration
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->boot();

$log = [];

// 1. Check Pdf facade class exists
$log[] = 'Pdf facade: ' . (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class) ? 'OK' : 'MISSING');

// 2. Check dompdf installed
$log[] = 'DomPDF lib: ' . (class_exists(\Dompdf\Dompdf::class) ? 'OK' : 'MISSING');

// 3. Check the route is registered
$routes = \Illuminate\Support\Facades\Route::getRoutes();
$found = false;
foreach ($routes as $r) {
    if ($r->getName() === 'customer.history.report.pdf') {
        $found = true;
        $log[] = 'Route customer.history.report.pdf: OK';
        $log[] = '  URI: ' . $r->uri();
        $log[] = '  Action: ' . $r->getActionName();
        $log[] = '  Methods: ' . implode(',', $r->methods());
        break;
    }
}
if (!$found) {
    $log[] = 'Route customer.history.report.pdf: MISSING';
}

// 4. Lint the controller via token_get_all (basic syntax check)
$ctrl = file_get_contents(__DIR__ . '/app/Http/Controllers/Customer/HistoryController.php');
$tokens = @token_get_all($ctrl);
$log[] = 'HistoryController tokens parsed: ' . (is_array($tokens) ? 'OK (' . count($tokens) . ' tokens)' : 'FAIL');

// 5. Check report_pdf view exists
$log[] = 'report_pdf.blade.php: ' . (file_exists(__DIR__ . '/resources/views/customer/history/report_pdf.blade.php') ? 'OK' : 'MISSING');

// 6. Check the report.blade.php contains the PDF button
$blade = file_get_contents(__DIR__ . '/resources/views/customer/history/report.blade.php');
$log[] = 'PDF button in report.blade.php: ' . (str_contains($blade, 'history.report.pdf') ? 'OK' : 'MISSING');

@file_put_contents(__DIR__ . '/verify_pdf_result.txt', implode("\n", $log));
echo implode("\n", $log);
