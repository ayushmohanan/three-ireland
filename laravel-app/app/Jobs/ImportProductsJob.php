<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class ImportProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle(): void
    {
        $fileFullPath = Storage::path($this->filePath);

        if (! file_exists($fileFullPath)) {
            Log::error(" CSV file not found: {$fileFullPath}");

            return;
        }

        $csv = Reader::createFromPath($fileFullPath, 'r');
        $csv->setHeaderOffset(0);

        $imported = 0;

        foreach ($csv->getRecords() as $index => $record) {
            try {
                Log::debug("Processing row {$index}: ".json_encode($record));

                if (empty($record['name']) || empty($record['sku'])) {
                    Log::warning(" Skipping row {$index}: missing name or sku");

                    continue;
                }

                Product::updateOrCreate(
                    ['sku' => strtoupper($record['sku'])],

                    [
                        'name' => $record['name'],
                        'price' => (float) ($record['price'] ?? 0),
                        'stock_on_hand' => (int) ($record['stock_on_hand'] ?? 0),
                        'reorder_threshold' => (int) ($record['reorder_threshold'] ?? 0),
                        'status' => strtolower($record['status'] ?? 'inactive'),

                    ]
                );

                $imported++;
            } catch (\Throwable $e) {
                Log::error(" CSV import error on row {$index}: {$e->getMessage()}");
            }
        }

        Log::info("✅ Successfully imported {$imported} products from CSV");
    }
}
