<?php

namespace App\Models\Cars;

use App\Exceptions\UnauthorizedException;
use App\Models\Users\AppLog;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Car extends Model
{
    const MORPH_TYPE = 'car';

    use HasFactory, SoftDeletes;
    public $timestamps = false;
    protected $fillable = ['car_model_id', 'category', 'desc'];

    ///static functions
    public static function newCar(int $car_model_id, string $category, string $desc = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('create', self::class)) throw new UnauthorizedException();

        $newCar = new self([
            'car_model_id' => $car_model_id,
            'category' => $category,
            'desc' => $desc,
        ]);
        try {
            $newCar->save();
            return $newCar;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public static function importData($file)
    {
        $spreadsheet = IOFactory::load($file);
        if (!$spreadsheet) {
            throw new Exception('Failed to read files content');
        }
        $activeSheet = $spreadsheet->getActiveSheet();
        $highestRow = $activeSheet->getHighestDataRow();
        $highestCol = $activeSheet->getHighestDataColumn();
        $highestColIndex = Coordinate::columnIndexFromString($highestCol);

        for ($i = 3; $i <= $highestRow; $i++) {
            $category = $activeSheet->getCell('D' . $i)->getValue();
            //skip if no car category found
            if (!$category) {
                continue;
            }

            $brand_cell = $activeSheet->getCell('B' . $i)->getValue();

            if ($brand_cell) {
                $brand = Brand::firstOrCreate([
                    'country_id' => 1,
                    'name' => $brand_cell,
                ]);
            }

            //skip if no brand found
            if (!$brand) {
                continue;
            }

            $model_cell = $activeSheet->getCell('C' . $i)->getValue();
            if ($model_cell) {
                $car_model = CarModel::firstOrCreate([
                    'brand_id' => $brand->id,
                    'name' => $model_cell,
                ]);
            }

            //skip if no car model found
            if (!$car_model) {
                continue;
            }

            $car = Car::firstOrCreate([
                'car_model_id' => $car_model->id,
                'category' => $category,
                'desc' => 'Imported from file on ' . (new Carbon())->format('Y-m-d H:i:s'),
            ]);
            for ($p_i = 5; $p_i <= $highestColIndex; $p_i++) {
                $cellCharFromIndex = Coordinate::stringFromColumnIndex($p_i);
                $price = $activeSheet->getCell($cellCharFromIndex . $i)->getValue();
                $year = $activeSheet->getCell($cellCharFromIndex . '2')->getValue();
                if (!$year || !$price) continue;

                if (is_numeric($price) && is_numeric($year)) {
                    $car->car_prices()->updateOrCreate(
                        [
                            'model_year' => $year,
                        ],
                        [
                            'price' => $price,
                            'desc' => 'Imported from file on ' . (new Carbon())->format('Y-m-d H:i:s'),
                        ],
                    );
                }
            }
        }
    }

    public static function exportData($filePath = null)
    {
        // Create new spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $activeSheet = $spreadsheet->getActiveSheet();
        
        // Get all cars with their relationships
        $cars = self::with(['car_model.brand', 'car_prices'])
            ->orderBy('id')
            ->get();
        
        // Get all unique years from car prices
        $allYears = CarPrice::select('model_year')
            ->distinct()
            ->orderBy('model_year')
            ->pluck('model_year')
            ->toArray();
        
        // Set up headers
        $activeSheet->setCellValue('A1', 'ID');
        $activeSheet->setCellValue('B1', 'Brand');
        $activeSheet->setCellValue('C1', 'Model');
        $activeSheet->setCellValue('D1', 'Category');
        $activeSheet->setCellValue('E1', 'Description');
        
        // Set year headers starting from column F
        $colIndex = 6; // Column F
        foreach ($allYears as $year) {
            $colLetter = Coordinate::stringFromColumnIndex($colIndex);
            $activeSheet->setCellValue($colLetter . '1', $year);
            $colIndex++;
        }
        
        // Set year headers in row 2 as well (for compatibility with import format)
        $colIndex = 6; // Column F
        foreach ($allYears as $year) {
            $colLetter = Coordinate::stringFromColumnIndex($colIndex);
            $activeSheet->setCellValue($colLetter . '2', $year);
            $colIndex++;
        }
        
        // Fill data starting from row 3
        $rowIndex = 3;
        foreach ($cars as $car) {
            $activeSheet->setCellValue('A' . $rowIndex, $car->id);
            $activeSheet->setCellValue('B' . $rowIndex, $car->car_model->brand->name ?? '');
            $activeSheet->setCellValue('C' . $rowIndex, $car->car_model->name ?? '');
            $activeSheet->setCellValue('D' . $rowIndex, $car->category);
            $activeSheet->setCellValue('E' . $rowIndex, $car->desc ?? '');
            
            // Create a map of prices by year for quick lookup
            $pricesByYear = $car->car_prices->keyBy('model_year');
            
            // Fill price data for each year
            $colIndex = 6; // Column F
            foreach ($allYears as $year) {
                $colLetter = Coordinate::stringFromColumnIndex($colIndex);
                $price = $pricesByYear->get($year);
                if ($price) {
                    $activeSheet->setCellValue($colLetter . $rowIndex, $price->price);
                }
                $colIndex++;
            }
            
            $rowIndex++;
        }
        
        // Auto-size columns
        foreach (range('A', $activeSheet->getHighestDataColumn()) as $col) {
            $activeSheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Create writer and save file
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        
        if ($filePath) {
            $writer->save($filePath);
            return $filePath;
        } else {
            // Generate default filename with timestamp
            $filename = 'cars_export_' . (new Carbon())->format('Y-m-d_H-i-s') . '.xlsx';
            $filePath = storage_path('exports/' . $filename);
            
            // Ensure exports directory exists
            if (!file_exists(dirname($filePath))) {
                mkdir(dirname($filePath), 0755, true);
            }
            
            $writer->save($filePath);
            return $filePath;
        }
    }

    public static function downloadCarsExport()
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('create', self::class)) return;

        $filePath = self::exportData();
        
        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public static function getByBrandAndModel($brand_name, $model_name)
    {
        return self::select("cars.*")
            ->join("car_models", "cars.car_model_id", "=", "car_models.id")
            ->join("brands", "car_models.brand_id", "=", "brands.id")
            ->where("brands.name", $brand_name)
            ->where("car_models.name", $model_name)
            ->first();
    }

    ///model functions
    public function editInfo(int $car_model_id, string $category, string $desc = null)
    {

        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) throw new UnauthorizedException();

        $this->update([
            'car_model_id' => $car_model_id,
            'category' => $category,
            'desc' => $desc,
        ]);
        try {
            return $this->save();
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    /**
     * @param array $prices array of 'model_year', 'price' and 'desc' values
     */
    public function setPrices(array $prices)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) throw new UnauthorizedException();

        try {
            DB::transaction(function () use ($prices) {
                $this->car_prices()->delete();
                $this->car_prices()->createMany($prices);
                AppLog::info('Prices update', "Car($this->id) price updated", $this);
            });
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error('Prices update failed', $e->getMessage());
            return false;
        }
    }

    public function addPrice($year, $price, $desc = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) throw new UnauthorizedException();

        try {
            $this->car_prices()->updateOrCreate([
                "model_year"    =>  $year,
            ], [
                "price"         =>  $price,
                "desc"          =>  $desc
            ]);
            $this->load('car_model');
            AppLog::info('New Price', "New price added for {$this->car_model->name} - $this->name", $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error('Prices update failed', $e->getMessage());
            return false;
        }
    }

    //scopes
    public function scopeWithPrices($query)
    {
        return $query->with('car_prices');
    }

    public function scopeTableData($query)
    {
        return $query
            ->select('cars.*', 'car_models.name as car_model_name', 'brands.name as brand_name')
            ->join('car_models', 'car_models.id', '=', 'cars.car_model_id')
            ->join('brands', 'brands.id', '=', 'car_models.brand_id');
    }

    public function scopeSearchBy($query, $text)
    {
        $words = explode(" ", $text);
        foreach ($words as $w)
            $query->where(function ($query) use ($w) {
                $query
                    ->where('cars.category', 'LIKE', "%$w%")
                    ->orWhere('car_models.name', 'LIKE', "%$w%")
                    ->orWhere('brands.name', 'LIKE', "%$w%");
            });

        return $query;
    }

    //must include table data scope before this one
    public function scopeSortByCar($query, $sort = 'asc')
    {
        return $query->orderBy('cars.category', $sort);
    }

    //must include table data scope before this one
    public function scopeSortByModel($query, $sort = 'asc')
    {
        return $query->orderBy('car_model_name', $sort);
    }

    //must include table data scope before this one
    public function scopeSortByBrand($query, $sort = 'asc')
    {
        return $query->orderBy('brand_name', $sort);
    }

    ///relations
    public function car_prices(): HasMany
    {
        return $this->hasMany(CarPrice::class);
    }

    public function car_model(): BelongsTo
    {
        return $this->belongsTo(CarModel::class);
    }
}
