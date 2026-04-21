<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountGroup;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ImportController extends Controller
{
    private array $schemas = [
        'customers' => [
            'model'   => Customer::class,
            'key'     => 'email',
            'columns' => ['name', 'email', 'phone', 'address', 'opening_balance'],
        ],
        'suppliers' => [
            'model'   => Supplier::class,
            'key'     => 'email',
            'columns' => ['name', 'email', 'phone', 'address', 'opening_balance'],
        ],
        'products' => [
            'model'   => Product::class,
            'key'     => 'sku',
            'columns' => ['sku', 'name', 'description', 'unit', 'purchase_price', 'sale_price', 'current_stock', 'reorder_level', 'category'],
        ],
        'accounts' => [
            'model'   => Account::class,
            'key'     => 'code',
            'columns' => ['code', 'name', 'type', 'group_name', 'opening_balance'],
        ],
    ];

    public function index()
    {
        return view('imports.index');
    }

    public function template(string $entity)
    {
        $this->guardEntity($entity);
        $schema = $this->schemas[$entity];

        $filename = "{$entity}-template.csv";
        $handle = fopen('php://temp', 'w+');
        fputcsv($handle, $schema['columns']);

        if ($entity === 'accounts') {
            fputcsv($handle, ['1999', 'Sample Cash Account', 'asset', 'Current Assets', '0']);
        } elseif ($entity === 'customers' || $entity === 'suppliers') {
            fputcsv($handle, ['Sample Name', 'sample@example.com', '01700-000000', 'Dhaka', '0']);
        } elseif ($entity === 'products') {
            fputcsv($handle, ['SKU-SAMPLE', 'Sample Product', 'Description', 'pcs', '100', '150', '0', '10', 'General']);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function store(Request $request, string $entity)
    {
        $this->guardEntity($entity);

        $request->validate([
            'file' => ['required', 'file', 'mimetypes:text/csv,text/plain,application/csv,application/vnd.ms-excel', 'max:5120'],
        ]);

        $schema = $this->schemas[$entity];
        $path = $request->file('file')->getRealPath();

        $handle = fopen($path, 'r');
        if (!$handle) {
            return back()->with('error', 'Unable to read CSV file.');
        }

        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            return back()->with('error', 'CSV file is empty.');
        }

        $headers = array_map(fn($h) => strtolower(trim($h)), $headers);
        $columns = array_map('strtolower', $schema['columns']);
        $missing = array_diff(['name'], $headers);

        if (!empty($missing) && $entity !== 'accounts') {
            fclose($handle);
            return back()->with('error', 'CSV missing required columns: ' . implode(', ', $missing));
        }

        $imported = 0;
        $skipped = 0;
        $errors = [];
        $row = 1;

        while (($data = fgetcsv($handle)) !== false) {
            $row++;
            $assoc = [];
            foreach ($headers as $i => $h) {
                if (in_array($h, $columns, true)) {
                    $assoc[$h] = isset($data[$i]) ? trim($data[$i]) : null;
                }
            }

            if (empty(array_filter($assoc))) { $skipped++; continue; }

            try {
                $this->importRow($entity, $schema, $assoc);
                $imported++;
            } catch (\Throwable $e) {
                $errors[] = "Row {$row}: " . $e->getMessage();
                $skipped++;
            }
        }
        fclose($handle);

        $msg = "Imported {$imported} row(s). Skipped {$skipped}.";
        if (!empty($errors)) {
            $msg .= ' First errors: ' . implode(' | ', array_slice($errors, 0, 3));
        }

        return back()->with($imported > 0 ? 'success' : 'warning', $msg);
    }

    private function importRow(string $entity, array $schema, array $data): void
    {
        if ($entity === 'accounts') {
            $groupName = $data['group_name'] ?? null;
            unset($data['group_name']);
            if ($groupName) {
                $group = AccountGroup::firstOrCreate(
                    ['name' => $groupName],
                    ['type' => $data['type'] ?? 'asset']
                );
                $data['account_group_id'] = $group->id;
            }
            $data['opening_balance'] = (float)($data['opening_balance'] ?? 0);
            Account::updateOrCreate(['code' => $data['code']], $data);
            return;
        }

        if ($entity === 'products') {
            $data['purchase_price'] = (float)($data['purchase_price'] ?? 0);
            $data['sale_price'] = (float)($data['sale_price'] ?? 0);
            $data['current_stock'] = (float)($data['current_stock'] ?? 0);
            $data['reorder_level'] = (float)($data['reorder_level'] ?? 0);
            $data['is_active'] = true;
            Product::updateOrCreate(['sku' => $data['sku']], $data);
            return;
        }

        // customers / suppliers
        $data['opening_balance'] = (float)($data['opening_balance'] ?? 0);
        $model = $schema['model'];
        if (!empty($data[$schema['key']])) {
            $model::updateOrCreate([$schema['key'] => $data[$schema['key']]], $data);
        } else {
            $model::create($data);
        }
    }

    private function guardEntity(string $entity): void
    {
        abort_unless(array_key_exists($entity, $this->schemas), 404);
    }
}
