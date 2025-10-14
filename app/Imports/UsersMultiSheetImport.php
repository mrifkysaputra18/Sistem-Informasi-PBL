<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\Log;

class UsersMultiSheetImport implements WithMultipleSheets
{
    protected $dataImport;

    public function __construct()
    {
        // Use simple importer without WithHeadingRow
        $this->dataImport = new \App\Imports\UsersImportSimple();
        Log::info('UsersMultiSheetImport - Initialized with Simple Importer');
    }

    /**
     * Return array of sheet importers
     * Only process "Data Mahasiswa" sheet
     */
    public function sheets(): array
    {
        Log::info('UsersMultiSheetImport - sheets() called, will process sheet 0 only');
        
        return [
            0 => $this->dataImport,  // First sheet (Data Mahasiswa)
            // Sheet 1 (Petunjuk Penggunaan) will be ignored
        ];
    }

    /**
     * Get import statistics
     */
    public function getImportedCount()
    {
        return $this->dataImport->getImportedCount();
    }

    public function getSkippedCount()
    {
        return $this->dataImport->getSkippedCount();
    }

    public function getErrors()
    {
        return $this->dataImport->getErrors();
    }
}
