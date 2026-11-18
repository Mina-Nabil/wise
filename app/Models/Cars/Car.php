<?php

namespace App\Models\Cars;

use App\Exceptions\UnauthorizedException;
use App\Models\Base\Country;
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
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Car extends Model
{
    const MORPH_TYPE = 'car';

    const ENGINE_PETROL = 'Petrol';
    const ENGINE_ELECTRIC = 'Electric';
    const ENGINE_REEV = 'REEV';
    const ENGINE_HYBIRD = 'Hybird';
    const ENGINE_TYPES = [
        self::ENGINE_PETROL,
        self::ENGINE_ELECTRIC,
        self::ENGINE_REEV,
        self::ENGINE_HYBIRD,
    ];

    use HasFactory, SoftDeletes;
    public $timestamps = false;
    protected $fillable = ['car_model_id', 'category', 'desc', 'engine_type'];

    ///static functions
    public static function newCar(int $car_model_id, string $category, string $desc = null, $engine_type = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('create', self::class)) throw new UnauthorizedException();

        $newCar = new self([
            'car_model_id' => $car_model_id,
            'category' => $category,
            'desc' => $desc,
            'engine_type' => $engine_type,
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

        $headers = [];
        $yearColumns = [];
        for ($p_i = 1; $p_i <= $highestColIndex; $p_i++) {
            $col = Coordinate::stringFromColumnIndex($p_i);
            $header = strtolower(trim((string) $activeSheet->getCell($col . '1')->getValue()));
            if ($header !== '') {
                $headers[$header] = $col;
            }

            $year = $activeSheet->getCell($col . '2')->getValue();
            if (is_numeric($year) && (int) $year >= 1900 && (int) $year <= 2100) {
                $yearColumns[$col] = (int) $year;
            }
        }

        $idCol = $headers['id'] ?? null;
        $brandCol = $headers['brand'] ?? 'B';
        $modelCol = $headers['model'] ?? 'C';
        $categoryCol = $headers['category'] ?? 'D';
        $countryCol = $headers['country'] ?? null;
        $engineCol = $headers['engine type'] ?? null;
        $descCol = $headers['description'] ?? null;

        for ($i = 3; $i <= $highestRow; $i++) {
            $brand = null;
            $car_model = null;
            $category = trim((string) $activeSheet->getCell($categoryCol . $i)->getValue());
            //skip if no car category found
            if ($category === '') {
                continue;
            }

            $brand_cell = trim((string) $activeSheet->getCell($brandCol . $i)->getValue());
            $country = null;
            if ($countryCol) {
                $countryName = trim((string) $activeSheet->getCell($countryCol . $i)->getValue());
                if ($countryName !== '') {
                    $country = Country::whereRaw('LOWER(name) = ?', [strtolower($countryName)])->first();
                    if (!$country) {
                        $country = Country::create(['name' => $countryName]);
                    }
                }
            }

            if ($brand_cell !== '') {
                $brand = Brand::where('name', $brand_cell)->first();
                if (!$brand) {
                    $brand = Brand::create([
                        'name' => $brand_cell,
                        'country_id' => $country?->id ?? 1,
                    ]);
                } elseif ($country) {
                    $brand->country_id = $country->id;
                    $brand->save();
                }
            }

            //skip if no brand found
            if (!$brand) {
                continue;
            }

            $engineType = null;
            if ($engineCol) {
                $engineValue = trim((string) $activeSheet->getCell($engineCol . $i)->getValue());
                if ($engineValue !== '' && in_array($engineValue, self::ENGINE_TYPES, true)) {
                    $engineType = $engineValue;
                }
            }

            $model_cell = trim((string) $activeSheet->getCell($modelCol . $i)->getValue());
            if ($model_cell !== '') {
                $car_model = CarModel::firstOrCreate([
                    'brand_id' => $brand->id,
                    'name' => $model_cell,
                ]);
                if ($engineType !== null) {
                    $car_model->engine_type = $engineType;
                    $car_model->save();
                }
            }

            //skip if no car model found
            if (!$car_model) {
                continue;
            }

            $desc = null;
            if ($descCol) {
                $descText = trim((string) $activeSheet->getCell($descCol . $i)->getValue());
                $desc = $descText === '' ? null : $descText;
            }

            $car = null;
            if ($idCol) {
                $idValue = $activeSheet->getCell($idCol . $i)->getValue();
                if (is_numeric($idValue) && (int) $idValue > 0) {
                    $car = self::find((int) $idValue);
                }
            }

            $attributes = [
                'car_model_id' => $car_model->id,
                'category' => $category,
            ];
            if ($descCol) {
                $attributes['desc'] = $desc;
            }
            if ($engineType !== null) {
                $attributes['engine_type'] = $engineType;
            }

            if ($car) {
                $car->update($attributes);
            } else {
                $car = self::firstOrCreate(
                    [
                        'car_model_id' => $car_model->id,
                        'category' => $category,
                        'desc' => $attributes['desc'] ?? ('Imported from file on ' . (new Carbon())->format('Y-m-d H:i:s')),
                    ],
                    $attributes,
                );
            }
            foreach ($yearColumns as $col => $year) {
                $price = $activeSheet->getCell($col . $i)->getValue();
                if ($price === null || $price === '' || !is_numeric($price)) {
                    continue;
                }

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

    public static function exportData($filePath = null)
    {
        // Create new spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $activeSheet = $spreadsheet->getActiveSheet();
        
        // Get all cars with their relationships
        $cars = self::with(['car_model.brand.country', 'car_prices'])
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
        $activeSheet->setCellValue('E1', 'Country');
        $activeSheet->setCellValue('F1', 'Engine type');
        $activeSheet->setCellValue('G1', 'Description');

        // Year headers start at column H, on row 2 so import can find them
        $colIndex = 8;
        foreach ($allYears as $year) {
            $colLetter = Coordinate::stringFromColumnIndex($colIndex);
            $activeSheet->setCellValue($colLetter . '1', $year);
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
            $activeSheet->setCellValue('E' . $rowIndex, $car->car_model->brand->country->name ?? '');
            $activeSheet->setCellValue('F' . $rowIndex, $car->engine_type ?: ($car->car_model->engine_type ?? ''));
            $activeSheet->setCellValue('G' . $rowIndex, $car->desc ?? '');

            // Create a map of prices by year for quick lookup
            $pricesByYear = $car->car_prices->keyBy('model_year');

            // Fill price data for each year
            $colIndex = 8;
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
        
        $listLastRow = max($rowIndex + 50, 3);
        $engineList = new DataValidation();
        $engineList->setType(DataValidation::TYPE_LIST);
        $engineList->setErrorStyle(DataValidation::STYLE_STOP);
        $engineList->setAllowBlank(true);
        $engineList->setShowDropDown(true);
        $engineList->setShowErrorMessage(true);
        $engineList->setErrorTitle('Engine type');
        $engineList->setError('Choose Petrol, Electric, REEV, or Hybird.');
        $engineList->setFormula1('"' . implode(',', self::ENGINE_TYPES) . '"');
        $activeSheet->setDataValidation('F3:F' . $listLastRow, $engineList);

        $highestColIndex = Coordinate::columnIndexFromString($activeSheet->getHighestDataColumn());
        for ($colIndex = 1; $colIndex <= $highestColIndex; $colIndex++) {
            $activeSheet->getColumnDimension(Coordinate::stringFromColumnIndex($colIndex))->setAutoSize(true);
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
    public function editInfo(int $car_model_id, string $category, string $desc = null, $engine_type = false)
    {

        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) throw new UnauthorizedException();

        $attributes = [
            'car_model_id' => $car_model_id,
            'category' => $category,
            'desc' => $desc,
        ];
        if ($engine_type !== false) {
            $attributes['engine_type'] = $engine_type === '' ? null : $engine_type;
        }

        $this->update($attributes);
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
